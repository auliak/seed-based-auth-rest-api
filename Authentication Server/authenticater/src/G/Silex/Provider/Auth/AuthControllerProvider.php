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
    const INISIALISASI = '/inisialisasi';
	const IDENTIFIKASI = '/identifikasi';
	const AUTENTIKASI = '/autentikasi';
    const SINKRONISASI = '/sinkronisasi';

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
		
		// proses inisialisasi
		$controllers->post(self::INISIALISASI, function (Request $request) use ($app) {
			$unmtoken   = $request->get('unmtoken');
            $urltoken   = $request->get('urltoken');
			$seqnum = $request->get('seqnum');
			$initkey   = $request->get('initkey');
			
			return $app->json($app[AuthServiceProvider::AUTH_INITIALIZATION]($unmtoken, $urltoken, $seqnum, $initkey));
		});
		
		// proses identifikasi dan autentikasi
        $controllers->post(self::IDENTIFIKASI.'/{urltoken}/{unmtoken}/{seqnum}', function (Request $request, $urltoken, $unmtoken, $seqnum) use ($app) {
			
            $client = $app[AuthServiceProvider::AUTH_FIND_CLIENT]($urltoken, $unmtoken, $seqnum);
			if(isset($client))
				$client2 = $app[AuthServiceProvider::AUTH_NEXT_TOKENS]($client);
		
            return $app->json([
                'status' => true,
                'info'   => ['n'=>$app[AuthServiceProvider::AUTH_RAND_SEQ_NUM]($client2)]
            ]);
        });
		
        $controllers->post(self::AUTENTIKASI.'/{urltoken}/{unmtoken}/{seqnum}', function (Request $request, $urltoken, $unmtoken, $seqnum) use ($app) {
			
            $hashvalue   = $request->get('hashvalue');
			$cclient = new CClient($app['db']);
			$client = $app[AuthServiceProvider::AUTH_FIND_CLIENT]($urltoken, $unmtoken, $seqnum);
            $status = $app[AuthServiceProvider::AUTH_VALIDATE_HASH_VALUE]($hashvalue, $client);
			
            return $app->json([
                'status' => $status,
                'info'   => $status ? ['token' => $app[AuthServiceProvider::AUTH_NEW_TOKEN]($client)] : []
            ]);
        });
		
		// proses sinkronisasi
		$controllers->post(self::SINKRONISASI, function (Request $request) use ($app) {
			$hashvalue   = $request->get('hashvalue');
			$unmtoken   = $request->get('unmtoken');
            $urltoken   = $request->get('urltoken');
			$seqnum = $request->get('seqnum');
			$synckey = $request->get('synckey');
			
			if(isset($hashvalue))
			{
				$status = $app[AuthServiceProvider::SYNC_VALIDATE_HASH_VALUE]($hashvalue, $synckey);
				return $app->json($status);
			}
			else{
				$client = $app[AuthServiceProvider::SYNC_FIND_CLIENT]($synckey);
				return $app->json([
					'status' => $client?true:false,
					'info'   => $client?['n'=>$app[AuthServiceProvider::SYNC_RAND_SEQ_NUM]($client)]:[]
				]);
			}
		});

        return $controllers;
    }

    private function setUpMiddlewares(Application $app)
    {
        $app->before(function (Request $request) use ($app) {
			$app->register(new AuthServiceProvider($app['db']));
        });
    }
}