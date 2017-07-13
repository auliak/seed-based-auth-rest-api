<?php

namespace G\Silex\Provider\Auth;

use Silex\Application;

class AuthBuilder
{
    public static function mountProviderIntoApplication($route, Application $app)
    {
        $app->mount($route, (new AuthControllerProvider())->setBaseRoute($route));
    }
}