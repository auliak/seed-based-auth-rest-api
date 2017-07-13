<?php

namespace G\Silex\Provider\Auth;

use Silex\Application;
use Silex\ServiceProviderInterface;
use G\Silex\Entity\CClient;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthServiceProvider implements ServiceProviderInterface
{
    const AUTH_VALIDATE_TOKEN       = 'auth.validate.token';
  
	private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function register(Application $app)
    {
        $app[self::AUTH_VALIDATE_TOKEN] = $app->protect(function ($token, $urltoken, $unmtoken, $seqnum) {
            return $this->validateToken($token, $urltoken, $unmtoken, $seqnum);
        });
    }

    public function boot(Application $app)
    {
    }

    private function getClient($urltoken, $unmtoken, $seqnum)
    {		
		$cclient = new CClient($this->conn);
		$client = $cclient->loadClientByUrlTokenUnmTokenSeqNum($urltoken, $unmtoken, $seqnum);
		
		return $client;
    }

    private function validateToken($token, $urltoken, $unmtoken, $seqnum)
    {
		$client = $this->getClient($urltoken, $unmtoken, $seqnum);
		$access_token = $client->getAccessToken();
		$created_at = $client->getAccessTokenT();
		$timeout = $client->getAccessTokenTo();
		
		if(time() - $created_at >= $timeout)
			throw new AccessDeniedHttpException('Access Token telah expired.');
		else if($this->compareString($token, $access_token))
			return true;
		else
			throw new AccessDeniedHttpException('Access Token tidak sesuai.');
    }
	
	private function compareString($expected, $actual)
    {
        $expected .= "\0";
        $actual .= "\0";
        $expectedLength = mb_strlen($expected, '8bit');
        $actualLength =  mb_strlen($actual, '8bit');
        $diff = $expectedLength - $actualLength;
        for ($i = 0; $i < $actualLength; $i++) {
            $diff |= (ord($actual[$i]) ^ ord($expected[$i % $expectedLength]));
        }
        return $diff === 0;
    }
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, 403);
    }
}