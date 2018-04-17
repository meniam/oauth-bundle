<?php

namespace Meniam\OauthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class OauthExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->createHttplugClient($container, $config);
        $this->enableServices($config['services'], $config, $container);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    protected function createHttplugClient(ContainerBuilder $container, array $config)
    {
        $httpClientId = $config['http']['client'];
        $httpMessageFactoryId = $config['http']['message_factory'];
        $bundles = $container->getParameter('kernel.bundles');
        if ('httplug.client.default' === $httpClientId && !isset($bundles['HttplugBundle'])) {
            throw new InvalidConfigurationException(
                'You must setup php-http/httplug-bundle to use the default http client service.'
            );
        }
        if ('httplug.message_factory.default' === $httpMessageFactoryId && !isset($bundles['HttplugBundle'])) {
            throw new InvalidConfigurationException(
                'You must setup php-http/httplug-bundle to use the default http message factory service.'
            );
        }
        $container->setAlias('me.oauth.http.client', new Alias($config['http']['client'], true));
        $container->setAlias('me.oauth.http.message_factory', new Alias($config['http']['message_factory'], true));
    }

    private function enableServices($config, $globalConfig, ContainerBuilder $container)
    {
        foreach ($config as $id => $serviceConfig) {
            $className = 'Meniam\\OauthBundle\\Oauth\\ResourceOwner\\'.ucfirst($serviceConfig['resource_owner']).'ResourceOwner';

            $definition = new Definition($className);
            $definition->addArgument(new Reference('me.oauth.http_client'));
            $definition->addArgument(new Reference('security.http_utils'));
            $definition->addArgument($serviceConfig['options']);
            $definition->addArgument($serviceConfig['resource_owner']);
            $definition->addArgument(new Reference('me.oauth.storage.session'));
            $container->setDefinition('me.oauth.service.'.$id.'.resource_owner', $definition);

            $definition = new Definition('Meniam\\OauthBundle\\Service');
            $definition->addArgument($id);
            $definition->addArgument($serviceConfig['title']);
            $definition->addArgument(new Reference('me.oauth.service.'.$id.'.resource_owner'));
            $definition->addMethodCall('setUrlGenerator', [new Reference('router')]);
            $definition->addMethodCall('setRedirectUriRoute', [$globalConfig['redirect_uri_route']]);
            $container->setDefinition('me.oauth.service.'.$id, $definition);
        }
    }
}
