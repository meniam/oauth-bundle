<?php

namespace Meniam\OauthBundle;

use Meniam\OauthBundle\Oauth\ResourceOwnerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Service
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $redirectUriRoute;

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var ResourceOwnerInterface
     */
    protected $resourceOwner;

    /**
     * @var string
     */
    protected $title;

    public function __construct($id, $title, ResourceOwnerInterface $resourceOwner)
    {
        $this->id = $id;
        $this->title = $title;
        $this->resourceOwner = $resourceOwner;
    }

    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function setRedirectUriRoute($route)
    {
        $this->redirectUriRoute = $route;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return ResourceOwnerInterface
     */
    public function getResourceOwner()
    {
        return $this->resourceOwner;
    }

    public function getRedirectUri()
    {
        return $this->urlGenerator->generate($this->redirectUriRoute, ['service' => $this->id], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function getAuthorizationUrl(array $extraParameters = array())
    {
        return $this->resourceOwner->getAuthorizationUrl($this->getRedirectUri(), $extraParameters);
    }

    public function getAccessToken(Request $request, array $extraParameters = array())
    {
        return $this->resourceOwner->getAccessToken($request, $this->getRedirectUri(), $extraParameters);
    }

    public function getUserInformation(array $accessToken, array $extraParameters = array())
    {
        return $this->resourceOwner->getUserInformation($accessToken, $extraParameters);
    }
}
