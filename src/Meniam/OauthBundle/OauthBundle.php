<?php

namespace Meniam\OauthBundle;

use Meniam\OauthBundle\DependencyInjection\MeOauthExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OauthBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new MeOauthExtension();
    }
}
