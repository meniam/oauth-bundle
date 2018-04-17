<?php

/*
 * This file is part of the HWIOAuthBundle package.
 *
 * (c) Hardware.Info <opensource@hardware.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meniam\OauthBundle\Oauth\ResourceOwner;

use Meniam\OauthBundle\Oauth\OauthToken;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SinaWeiboResourceOwner extends GenericOauth2ResourceOwner
{
    /**
     * {@inheritdoc}
     */
    protected $paths = array(
        'identifier' => 'id',
        'nickname' => 'screen_name',
        'realname' => 'screen_name',
        'profilepicture' => 'profile_image_url',
    );

    /**
     * {@inheritdoc}
     */
    public function getUserInformation(array $accessToken = null, array $extraParameters = array())
    {
        $url = $this->normalizeUrl($this->options['infos_url'], array(
            'access_token' => $accessToken['access_token'],
            'uid' => $accessToken['uid'],
        ));

        $content = $this->doGetUserInformationRequest($url)->getBody();

        $response = $this->getUserResponse();
        $response->setData((string) $content);
        $response->setResourceOwner($this);
        $response->setOauthToken(new OauthToken($accessToken));

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'authorization_url' => 'https://api.weibo.com/oauth2/authorize',
            'access_token_url' => 'https://api.weibo.com/oauth2/access_token',
            'infos_url' => 'https://api.weibo.com/2/users/show.json',
        ));
    }
}
