<?php

namespace G\Silex\Entity;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CClient
{
	private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }
	
	public function loadClientById($id)
    {
        $stmt = $this->conn->executeQuery('SELECT * FROM client WHERE id = ?', array($id));

        if (!$data = $stmt->fetch()) {
            throw new AccessDeniedHttpException('Client tidak ditemukan.');
        }
		
		return new Client($data['id'], $data['nama_app'], $data['access_token'], $data['access_token_t'], $data['unm_token'], $data['url_token'], $data['root_file'], $data['unm_seed'], $data['url_seed'], $data['token_hash'], $data['token_hash_t'], $data['token_hash_to'], $data['sync_token_hash'], $data['sync_token_hash_t'], $data['sync_token_hash_to'], $data['seq_num'], $data['access_token_to'], $data['init_key_to'], $data['sync_key_to'], $data['sync_key'], $data['sync_key_t'], $data['init_key'], $data['init_key_t'], $data['status_id']);
    }
	
	public function loadClientByUnmToken($unm_token)
    {
        $stmt = $this->conn->executeQuery('SELECT * FROM client WHERE unm_token = ?', array($unm_token));

        if (!$data = $stmt->fetch()) {
            throw new AccessDeniedHttpException('Client tidak ditemukan.');
        }
		
		return new Client($data['id'], $data['nama_app'], $data['access_token'], $data['access_token_t'], $data['unm_token'], $data['url_token'], $data['root_file'], $data['unm_seed'], $data['url_seed'], $data['token_hash'], $data['token_hash_t'], $data['token_hash_to'], $data['sync_token_hash'], $data['sync_token_hash_t'], $data['sync_token_hash_to'], $data['seq_num'], $data['access_token_to'], $data['init_key_to'], $data['sync_key_to'], $data['sync_key'], $data['sync_key_t'], $data['init_key'], $data['init_key_t'], $data['status_id']);
    }
	
	public function loadClientByUrlTokenUnmTokenSeqNum($urltoken, $unmtoken, $seqnum)
    {
        $stmt = $this->conn->executeQuery('SELECT * FROM client WHERE url_token = ? AND unm_token = ? AND seq_num = ?', array($urltoken, $unmtoken, $seqnum));
		
		if (!$data = $stmt->fetch()) {
			throw new AccessDeniedHttpException('Client tidak ditemukan.');
		}

		return new Client($data['id'], $data['nama_app'], $data['access_token'], $data['access_token_t'], $data['unm_token'], $data['url_token'], $data['root_file'], $data['unm_seed'], $data['url_seed'], $data['token_hash'], $data['token_hash_t'], $data['token_hash_to'], $data['sync_token_hash'], $data['sync_token_hash_t'], $data['sync_token_hash_to'], $data['seq_num'], $data['access_token_to'], $data['init_key_to'], $data['sync_key_to'], $data['sync_key'], $data['sync_key_t'], $data['init_key'], $data['init_key_t'], $data['status_id']);
		
		//$this->root_file = $data['root_file'];
		
		//return $this;
    }
	
	public function loadClientBySyncKey($synckey)
    {
        $stmt = $this->conn->executeQuery('SELECT * FROM client WHERE sync_key = ?', array($synckey));

        if (!$data = $stmt->fetch()) {
            throw new AccessDeniedHttpException('Client tidak ditemukan.');
        }
		
		return new Client($data['id'], $data['nama_app'], $data['access_token'], $data['access_token_t'], $data['unm_token'], $data['url_token'], $data['root_file'], $data['unm_seed'], $data['url_seed'], $data['token_hash'], $data['token_hash_t'], $data['token_hash_to'], $data['sync_token_hash'], $data['sync_token_hash_t'], $data['sync_token_hash_to'], $data['seq_num'], $data['access_token_to'], $data['init_key_to'], $data['sync_key_to'], $data['sync_key'], $data['sync_key_t'], $data['init_key'], $data['init_key_t'], $data['status_id']);
    }
	
	public function updateAccessToken($url_token, $unm_token, $seq_num, $new_token)
	{
		$sql = "UPDATE client SET access_token_to = 1000, access_token = ? , access_token_t = ? WHERE unm_token = ? AND url_token = ? AND seq_num = ?";
		
		$this->conn->executeUpdate($sql, array($new_token, time(), $unm_token, $url_token, $seq_num));
		
		return true;
	}
	
	public function updateTokens($url_token, $unm_token, $seq_num, $next_unmtoken, $next_urltoken)
	{
		$sql = "UPDATE client SET seq_num = seq_num+1, unm_token = ?, url_token=? WHERE unm_token = ? AND url_token = ? AND seq_num = ?";
		
		$this->conn->executeUpdate($sql, array($next_unmtoken, $next_urltoken, $unm_token, $url_token, $seq_num));
		
		return true;
	}
	
	public function updateTokenSeqNumAndStatus($unm_seed, $url_seed, $next_seqnum, $next_unmtoken, $next_urltoken, $initkey)
	{
		$sql = "UPDATE client SET status_id = 1, unm_seed = ?, url_seed = ?, seq_num = ?, unm_token = ?, url_token = ?  WHERE init_key = ?";
		$this->conn->executeUpdate($sql, array($unm_seed, $url_seed, $next_seqnum, $next_unmtoken, $next_urltoken, $initkey));
		return true;
	}
	
	public function updateAuthTokenHash($hashvalue_s, $token_hash_t, $token_hash_to, $unmtoken, $urltoken, $seqnum)
	{
		$sql = "UPDATE client SET token_hash = ?, token_hash_t = ?, token_hash_to = ?  WHERE unm_token = ? AND url_token = ? AND seq_num = ?";
		$this->conn->executeUpdate($sql, array($hashvalue_s, $token_hash_t, $token_hash_to, $unmtoken, $urltoken, $seqnum));
		return true;
	}
	
	public function updateSyncTokenHash($hashvalue_s, $sync_token_hash_t, $sync_token_hash_to, $synckey)
	{
		$sql = "UPDATE client SET sync_token_hash = ? , sync_token_hash_t = ?, sync_token_hash_to = ?  WHERE sync_key = ?";
		$this->conn->executeUpdate($sql, array($hashvalue_s, $sync_token_hash_t, $sync_token_hash_to, $synckey));
		
		return true;
	}
	
	public function loadClientByInitkey($initkey)
	{
		$stmt = $this->conn->executeQuery('SELECT * FROM client WHERE init_key = ?', array($initkey));

        if (!$data = $stmt->fetch()) {
			throw new AccessDeniedHttpException('Client tidak ditemukan.');
        }
		
		return new Client($data['id'], $data['nama_app'], $data['access_token'], $data['access_token_t'], $data['unm_token'], $data['url_token'], $data['root_file'], $data['unm_seed'], $data['url_seed'], $data['token_hash'], $data['token_hash_t'], $data['token_hash_to'], $data['sync_token_hash'], $data['sync_token_hash_t'], $data['sync_token_hash_to'], $data['seq_num'], $data['access_token_to'], $data['init_key_to'], $data['sync_key_to'], $data['sync_key'], $data['sync_key_t'], $data['init_key'], $data['init_key_t'], $data['status_id']);
    }
}

?>