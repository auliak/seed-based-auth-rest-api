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
		
		return new Client($data['id'], $data['nama_app'], $data['access_token'], $data['access_token_t'], $data['unm_token'], $data['url_token'], $data['root_file'], $data['unm_seed'], $data['url_seed'], $data['token_hash'], $data['token_hash_t'], $data['token_hash_to'], $data['sync_token_hash'], $data['sync_token_hash_t'], $data['sync_token_hash_to'], $data['seq_num'], $data['access_token_to'], $data['init_key_to'], $data['sync_key_to'], $data['sync_key'], $data['sync_key_t'], $data['init_key'], $data['init_key_t']);
    }
	
	public function loadClientByUrlTokenUnmTokenSeqNum($urltoken, $unmtoken, $seqnum)
    {
        $stmt = $this->conn->executeQuery('SELECT * FROM client WHERE url_token = ? AND unm_token = ? AND seq_num = ?', array($urltoken, $unmtoken, $seqnum));
		
		if (!$data = $stmt->fetch()) {
			throw new AccessDeniedHttpException(sprintf('Client tidak ditemukan.', $unmtoken));
		}

		return new Client($data['id'], $data['nama_app'], $data['access_token'], $data['access_token_t'], $data['unm_token'], $data['url_token'], $data['root_file'], $data['unm_seed'], $data['url_seed'], $data['token_hash'], $data['token_hash_t'], $data['token_hash_to'], $data['sync_token_hash'], $data['sync_token_hash_t'], $data['sync_token_hash_to'], $data['seq_num'], $data['access_token_to'], $data['init_key_to'], $data['sync_key_to'], $data['sync_key'], $data['sync_key_t'], $data['init_key'], $data['init_key_t']);
    }
}

?>