<?php

namespace G\Silex\Provider\Auth;

use Silex\Application;
use Silex\ServiceProviderInterface;
use G\Silex\Entity\CClient;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthServiceProvider implements ServiceProviderInterface
{
    const AUTH_FIND_CLIENT = 'auth.find.client';
    const AUTH_NEXT_TOKENS = 'auth.next.tokens';
    const AUTH_VALIDATE_HASH_VALUE = 'auth.validate.hash.value';
    const AUTH_NEW_TOKEN            = 'auth.new.token';
	const AUTH_INITIALIZATION		= 'auth.initialization';
	const AUTH_RAND_SEQ_NUM	= 'auth.rand.seq.num';
	const SYNC_FIND_CLIENT = 'sync.find.client';
	const SYNC_VALIDATE_HASH_VALUE = 'sync.validate.hash.value';
	const SYNC_RAND_SEQ_NUM	= 'sync.rand.seq.num';
	
	private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function register(Application $app)
    {
        $app[self::AUTH_FIND_CLIENT] = $app->protect(function ($urltoken, $unmtoken, $n) {
            return $this->getClient($urltoken, $unmtoken, $n);
        });
		
        $app[self::AUTH_NEXT_TOKENS] = $app->protect(function ($client) {
            return $this->generateNextTokens($client);
        });
		
        $app[self::AUTH_VALIDATE_HASH_VALUE] = $app->protect(function ($hashvalue, $client) {
            return $this->validateHashValue($hashvalue, $client);
        });

        $app[self::AUTH_NEW_TOKEN] = $app->protect(function ($client) {
            return $this->getNewTokenForUser($client);
        });

        $app[self::AUTH_INITIALIZATION] = $app->protect(function ($unmtoken, $urltoken, $seqnum, $initkey) {
            return $this->initialization($unmtoken, $urltoken, $seqnum, $initkey);
        });

        $app[self::AUTH_RAND_SEQ_NUM] = $app->protect(function ($client) {
            return $this->generateSeqNumAndHashValue($client);
        });
		
		$app[self::SYNC_FIND_CLIENT] = $app->protect(function ($synckey) {
            return $this->syncGetClient($synckey);
        });
		
		$app[self::SYNC_VALIDATE_HASH_VALUE] = $app->protect(function ($hashvalue, $synckey) {
            return $this->syncValidateHashValue($hashvalue, $synckey);
        });
		
		$app[self::SYNC_RAND_SEQ_NUM] = $app->protect(function ($client) {
            return $this->syncGenerateSeqNumAndHashValue($client);
        });
    }

    public function boot(Application $app)
    {
    }
	
	private function initialization($unmtoken, $urltoken, $seqnum, $initkey)
	{
		$cclient = new CClient($this->conn);
		$client = $cclient->loadClientByInitkey($initkey);
		$created_at = $client->getInitKeyT();
		$timeout = $client->getInitKeyTo();
		
		if($client->getStatusId()==1)
			throw new AccessDeniedHttpException('Proses inisialisasi telah dilakukan. Client berstatus aktif.');
		else if(time() - $created_at >= $timeout)
			throw new AccessDeniedHttpException('Initialization key telah expired.');
		else
		{
			$root_file = $client->getRootFile();
			$path = 'file/temp/'.$root_file;
			
			if(file_exists($path))
			{
				// Generate seed dan token untuk proses inisialisasi
				$seed = $this->generateSeed($root_file);
				$seqnum_s = 0;
				$unmtoken_s = $this->generateToken($seed['unmseed'],$seqnum_s);
				$urltoken_s = $this->generateToken($seed['urlseed'],$seqnum_s);
				
				// Membandingkan token client dan server
				if($this->compareString($unmtoken, $unmtoken_s) && $this->compareString($urltoken, $urltoken_s) && $seqnum==$seqnum_s)
				{
					// Hapus root file
					unlink($path);
					
					// Generate next token
					$next_seqnum = 1;
					$next_urltoken = $this->generateToken($seed['urlseed'],$next_seqnum);
					$next_unmtoken = $this->generateToken($seed['unmseed'],$next_seqnum);
					
					// Simpan next token dan sequence number		
					$cclient = new CClient($this->conn);
					$cclient->updateTokenSeqNumAndStatus($seed['unmseed'], $seed['urlseed'], $next_seqnum, $next_unmtoken, $next_urltoken, $initkey);
					
					return true;
				}
				else
					throw new AccessDeniedHttpException('Urltoken, Unmtoken, atau sequence number tidak sesuai.');
			}
			throw new AccessDeniedHttpException('Root file tidak ditemukan.');
		}
	}

    private function getClient($urltoken, $unmtoken, $seqnum)
    {		
		$cclient = new CClient($this->conn);
		$client = $cclient->loadClientByUrlTokenUnmTokenSeqNum($urltoken, $unmtoken, $seqnum);
		
		return $client;
    }
	
	private function generateNextTokens($client)
	{
		$unmtoken = $client->getUnmToken();
		$urltoken = $client->getUrlToken();
		$seqnum = $client->getSeqNum();
		
		$next_unmtoken = $this->generateToken($client->getUnmSeed(), $client->getSeqNum() + 1);
		$next_urltoken = $this->generateToken($client->getUrlSeed(), $client->getSeqNum() + 1);
		
		// update sequence number dan username token and url token
		$cclient = new CClient($this->conn);
		$cclient->updateTokens($urltoken, $unmtoken, $seqnum, $next_unmtoken, $next_urltoken);
		
		$client->setUnmToken($next_unmtoken);
		$client->setUrlToken($next_urltoken);
		$client->setSeqNum($seqnum + 1);
		
		return $client;
	}
	
	private function generateSeqNumAndHashValue($client)
	{		
		$client = $client;
		$unm_seed = $client->getUnmSeed();
		$url_seed = $client->getUrlSeed();
		$unmtoken = $client->getUnmToken();
		$urltoken = $client->getUrlToken();
		$seqnum = $client->getSeqNum();
		
		$n1 = rand();
		$n2 = rand();
		$n3 = rand();
		$n4 = rand();
		
		$hashvalue_s = $this->generateTokenHash($unm_seed, $url_seed, $n1,$n2,$n3,$n4);
		$token_hash_t = time();
		$token_hash_to = 1000;
		
		$cclient = new CClient($this->conn);
		$cclient->updateAuthTokenHash($hashvalue_s, $token_hash_t, $token_hash_to, $unmtoken, $urltoken, $seqnum);
			
		$seq_num = array(
			'n1'=>$n1,
			'n2'=>$n2,
			'n3'=>$n3,
			'n4'=>$n4
		);
		
		return $seq_num;
	}
	
	private function validateHashValue($hashvalue, $client)
	{			
		$hashvalue_s = $client->getTokenHash();
		
		$created_at = $client->getTokenHashT();
		$timeout = $client->getTokenHashTo();
		
		if(time() - $created_at >= $timeout)
			throw new AccessDeniedHttpException('Hash value telah expired.');
		else
		{
			$status = $this->compareString($hashvalue, $hashvalue_s);
		
			if($status==false)
				throw new AccessDeniedHttpException('Hash value tidak sesuai.');
			else
				return $status;
		}
	}
	
	private function getNewTokenForUser($client)
    {	
		$new_token = $this->generateRandomString(32);
		
		$unmtoken = $client->getUnmToken();
		$urltoken = $client->getUrlToken();
		$seqnum = $client->getSeqNum();
		
		// update access token 
		$cclient = new CClient($this->conn);
		$cclient->updateAccessToken($urltoken, $unmtoken, $seqnum, $new_token);
		
        return $new_token;
    }
	
	private function syncGetClient($synckey)
    {
		$cclient = new CClient($this->conn);
		$client = $cclient->loadClientBySyncKey($synckey);
		$created_at = $client->getSyncKeyT();
		$timeout = $client->getSyncKeyTo();
		
		if(time() - $created_at >= $timeout)
			throw new AccessDeniedHttpException('Synchronization key telah expired.');
		else
			return $client;
    }
	
	private function syncValidateHashValue($hashvalue, $synckey)
	{	
		$cclient = new CClient($this->conn);
		$client = $cclient->loadClientBySyncKey($synckey);
		$hashvalue_s = $client->getSyncTokenHash();
		
		$created_at = $client->getSyncTokenHashT();
		$timeout = $client->getSyncTokenHashTo();
		
		if(time() - $created_at >= $timeout)
			throw new AccessDeniedHttpException('Hash value telah expired.');
		else
		{
			$status = $this->compareString($hashvalue, $hashvalue_s);
		
			if($status==false)
				throw new AccessDeniedHttpException('Hash value tidak sesuai.');
			
			return [
				'status' => $status,
				'info'   => $status ? ['seqnum' => $client->getSeqNum()] : []
			];
		}
		
			
	}
	
	private function syncGenerateSeqNumAndHashValue($client)
	{
		$unm_seed = $client->getUnmSeed();
		$url_seed = $client->getUrlSeed();
		$synckey = $client->getSyncKey();
		
		$n1 = rand();
		$n2 = rand();
		$n3 = rand();
		$n4 = rand();
		
		$hashvalue_s = $this->generateTokenHash($unm_seed, $url_seed, $n1,$n2,$n3,$n4);
		$sync_token_hash_t = time();
		$sync_token_hash_to = 1000;
		
		$cclient = new CClient($this->conn);
		$cclient->updateSyncTokenHash($hashvalue_s, $sync_token_hash_t, $sync_token_hash_to, $synckey);
			
		$seq_num = array(
			'n1'=>$n1,
			'n2'=>$n2,
			'n3'=>$n3,
			'n4'=>$n4
		);
		
		return $seq_num;
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

        );

        return new JsonResponse($data, 403);
    }
	
	private function generateRandomString($length)
	{
		if (!is_int($length)) {
            throw new InvalidParamException('First parameter ($length) must be an integer');
        }
        if ($length < 1) {
            throw new InvalidParamException('First parameter ($length) must be greater than 0');
        }
        $bytes = $this->generateRandomKey($length);
		
        // '=' character(s) returned by base64_encode() are always discarded because
        // they are guaranteed to be after position $length in the base64_encode() output.
		
        return strtr(substr(base64_encode($bytes), 0, $length), '+/', '_-');
	}
	
	private function generateRandomKey($length = 32)
    {
        if (!is_int($length)) {
            throw new InvalidParamException('First parameter ($length) must be an integer');
        }
        if ($length < 1) {
            throw new InvalidParamException('First parameter ($length) must be greater than 0');
        }
        // always use random_bytes() if it is available
        if (function_exists('random_bytes')) {
            return random_bytes($length);
        }
        // The recent LibreSSL RNGs are faster and likely better than /dev/urandom.
        // Parse OPENSSL_VERSION_TEXT because OPENSSL_VERSION_NUMBER is no use for LibreSSL.
        // https://bugs.php.net/bug.php?id=71143
        if ($this->_useLibreSSL === null) {
            $this->_useLibreSSL = defined('OPENSSL_VERSION_TEXT')
                && preg_match('{^LibreSSL (\d\d?)\.(\d\d?)\.(\d\d?)$}', OPENSSL_VERSION_TEXT, $matches)
                && (10000 * $matches[1]) + (100 * $matches[2]) + $matches[3] >= 20105;
        }
        // Since 5.4.0, openssl_random_pseudo_bytes() reads from CryptGenRandom on Windows instead
        // of using OpenSSL library. LibreSSL is OK everywhere but don't use OpenSSL on non-Windows.
        if ($this->_useLibreSSL
            || (
                DIRECTORY_SEPARATOR !== '/'
                && substr_compare(PHP_OS, 'win', 0, 3, true) === 0
                && function_exists('openssl_random_pseudo_bytes')
            )
        ) {
            $key = openssl_random_pseudo_bytes($length, $cryptoStrong);
            if ($cryptoStrong === false) {
                throw new Exception(
                    'openssl_random_pseudo_bytes() set $crypto_strong false. Your PHP setup is insecure.'
                );
            }
            if ($key !== false && StringHelper::byteLength($key) === $length) {
                return $key;
            }
        }
        // mcrypt_create_iv() does not use libmcrypt. Since PHP 5.3.7 it directly reads
        // CryptGenRandom on Windows. Elsewhere it directly reads /dev/urandom.
        if (function_exists('mcrypt_create_iv')) {
            $key = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
            if (StringHelper::byteLength($key) === $length) {
                return $key;
            }
        }
        // If not on Windows, try to open a random device.
        if ($this->_randomFile === null && DIRECTORY_SEPARATOR === '/') {
            // urandom is a symlink to random on FreeBSD.
            $device = PHP_OS === 'FreeBSD' ? '/dev/random' : '/dev/urandom';
            // Check random device for special character device protection mode. Use lstat()
            // instead of stat() in case an attacker arranges a symlink to a fake device.
            $lstat = @lstat($device);
            if ($lstat !== false && ($lstat['mode'] & 0170000) === 020000) {
                $this->_randomFile = fopen($device, 'rb') ?: null;
                if (is_resource($this->_randomFile)) {
                    // Reduce PHP stream buffer from default 8192 bytes to optimize data
                    // transfer from the random device for smaller values of $length.
                    // This also helps to keep future randoms out of user memory space.
                    $bufferSize = 8;
                    if (function_exists('stream_set_read_buffer')) {
                        stream_set_read_buffer($this->_randomFile, $bufferSize);
                    }
                    // stream_set_read_buffer() isn't implemented on HHVM
                    if (function_exists('stream_set_chunk_size')) {
                        stream_set_chunk_size($this->_randomFile, $bufferSize);
                    }
                }
            }
        }
        if (is_resource($this->_randomFile)) {
            $buffer = '';
            $stillNeed = $length;
            while ($stillNeed > 0) {
                $someBytes = fread($this->_randomFile, $stillNeed);
                if ($someBytes === false) {
                    break;
                }
                $buffer .= $someBytes; // buffer = buffer.somebite
                $stillNeed -= StringHelper::byteLength($someBytes); // length = length - somebyte
                if ($stillNeed === 0) {
                    // Leaving file pointer open in order to make next generation faster by reusing it.
                    return $buffer;
                }
            }
            fclose($this->_randomFile);
            $this->_randomFile = null;
        }
        throw new Exception('Unable to generate a random key');
    }
	
	private function generateTokenHash($unm_seed, $url_seed, $n1,$n2,$n3,$n4)
	{
		$urltoken1 = $this->generateToken($url_seed,$n1);
		$urltoken2 = $this->generateToken($url_seed,$n2);
		
		$unmtoken1 = $this->generateToken($unm_seed,$n3);
		$unmtoken2 = $this->generateToken($unm_seed,$n4);
		
		return md5($urltoken1.$urltoken2.$unmtoken1.$unmtoken2);
	}
	
	private function generateToken($seed, $seqnum){
		$seed = crc32($seed.$seqnum);
		mt_srand($seed);
		$token = mt_rand();
		
		return $token;
	}
	
	private function generateSeed($root_file){		
		$file = WEB_DIRECTORY.'/file/temp/'.$root_file;
		$fp = fopen($file, 'r');
		$fsize = filesize($file); 
		$seed = fread($fp, $fsize); 
		$urlseed = substr($seed, $fsize/2, 12);
		$unmseed = substr($seed, -12);
		fclose($fp);
		
		$seed = array(
			'urlseed'=>$urlseed,
			'unmseed'=>$unmseed,
		);
		
		return $seed;
	}
}
