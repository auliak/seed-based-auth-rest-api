<?php

namespace G\Silex\Provider\Auth;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Request;
use Silex\ControllerProviderInterface;
use Silex\Application;
use G\Silex\Entity\Client;
use G\Silex\Entity\CClient;

class AuthControllerProvider implements ControllerProviderInterface
{
    const TOKEN_HEADER_KEY = 'X-Token';
    const TOKEN_REQUEST_KEY = '_token';

    private $baseRoute;

    public function setBaseRoute($baseRoute)
    {
        $this->baseRoute = $baseRoute;

        return $this;
    }

    public function connect(Application $app)
    {
        $this->setUpMiddlewares($app);

        return $this->extractControllers($app);
    }

    private function extractControllers(Application $app)
    {
        $controllers = $app['controllers_factory'];

        return $controllers;
    }

    private function setUpMiddlewares(Application $app)
    {
        $app->before(function (Request $request) use ($app) {
			
			$app->register(new AuthServiceProvider($app['db']));
			
			$urltoken = $request->get('urltoken');
			$unmtoken = $request->get('unmtoken');
			$seqnum = $request->get('seqnum');
            
			if (!$this->isAuthRequiredForPath($request->getPathInfo()) && $request->getMethod()!= 'OPTIONS') {
                if (!$this->isValidTokenForApplication($app, $this->getTokenFromRequest($request), $urltoken, $unmtoken, $seqnum)) {
                    throw new AccessDeniedHttpException('Access Denied');
                }
            }
        });

	
    }

    private function getTokenFromRequest(Request $request)
    {
		// 1. ambil id client dan tokennya
        return $request->headers->get(self::TOKEN_HEADER_KEY, $request->get(self::TOKEN_REQUEST_KEY));
    }
	
	// route yang tidak perlu autentikasi
    private function isAuthRequiredForPath($path)
    {
		$path_arr = explode('/', $path);
		$path_new = implode('/', array_slice($path_arr, 0, 3));
		
        return in_array($path_new, [
		]);
    }

    private function isValidTokenForApplication(Application $app, $token, $urltoken, $unmtoken, $seqnum)
    {
        return $app[AuthServiceProvider::AUTH_VALIDATE_TOKEN]($token, $urltoken, $unmtoken, $seqnum);
    }
}
