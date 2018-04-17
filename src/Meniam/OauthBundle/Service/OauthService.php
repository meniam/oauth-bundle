<?php

namespace Meniam\OauthBundle\Service;

use Meniam\OauthBundle\Exception\ServiceNotFoundException;
use Meniam\OauthBundle\Service;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

class OauthService
{
    use ContainerAwareTrait;

    /**
     * @param $id
     *
     * @return Service|object
     *
     * @throws ServiceNotFoundException
     */
    public function getService($id)
    {
        if (!$this->container->has('me.oauth.service.'.$id)) {
            throw new ServiceNotFoundException();
        }
        $service = $this->container->get('me.oauth.service.'.$id);

        return $service;
    }

    /**
     * @param Request $request
     *
     * @return Service|object
     *
     * @throws ServiceNotFoundException
     */
    public function getServiceByRequest(Request $request)
    {
        $service = $this->getService($request->get('service'));
        if (!$service->getResourceOwner()->handles($request)) {
            throw new ServiceNotFoundException();
        }

        return $service;
    }
}
