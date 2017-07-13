<?php

namespace G\Silex\Entity;

class Client
{
	private $id;
	private $nama_app;
	private $access_token;
	private $access_token_t;
	private $unm_token;
	private $url_token;
	private $root_file;
	private $unm_seed;
	private $url_seed;
	private $token_hash;
	private $token_hash_t;
	private $token_hash_to;
	private $sync_token_hash;
	private $sync_token_hash_t;
	private $sync_token_hash_to;
	private $seq_num;
	private $access_token_to;
	private $init_key_to;
	private $sync_key_to;
	private $sync_key;
	private $sync_key_t;
	private $init_key;
	private $init_key_t;
	private $status_id;
	
	public function __construct($id, $nama_app, $access_token, $access_token_t, $unm_token, $url_token, $root_file, $unm_seed, $url_seed, $token_hash, $token_hash_t, $token_hash_to, $sync_token_hash, $sync_token_hash_t, $sync_token_hash_to, $seq_num, $access_token_to, $init_key_to, $sync_key_to, $sync_key, $sync_key_t, $init_key, $init_key_t,$status_id)
    {
        $this->id = $id;
        $this->nama_app = $nama_app;
		$this->access_token = $access_token;
		$this->access_token_t = $access_token_t;
		$this->unm_token = $unm_token;
		$this->url_token = $url_token;
		$this->root_file = $root_file;
		$this->unm_seed = $unm_seed;
		$this->url_seed = $url_seed;
		$this->token_hash = $token_hash;
		$this->token_hash_t = $token_hash_t;
		$this->token_hash_to = $token_hash_to;
		$this->sync_token_hash = $sync_token_hash;
		$this->sync_token_hash_t = $sync_token_hash_t;
		$this->sync_token_hash_to = $sync_token_hash_to;
		$this->seq_num = $seq_num;
		$this->access_token_to = $access_token_to;
		$this->init_key_to = $init_key_to;
		$this->sync_key_to = $sync_key_to;
		$this->sync_key = $sync_key;
		$this->sync_key_t = $sync_key_t;
		$this->init_key = $init_key;
		$this->init_key_t = $init_key_t;
		$this->status_id = $status_id;
    }
	
	public function getId()     {
        return $this->id;
    }
 
    public function getNamaapp()
    {
        return $this->nama_app;
    }
 
    public function getAccessToken()
    {
        return $this->access_token;
    }
 
    public function getAccessTokenT()
    {
        return $this->access_token_t;
    }
	
    public function getUnmToken()
    {
        return $this->unm_token;
    }
	
    public function getUrlToken()
    {
        return $this->url_token;
    }
	
    public function getRootFile()
    {
        return $this->root_file;
    }
	
    public function getUnmSeed()
    {
        return $this->unm_seed;
    }
	
    public function getUrlSeed()
    {
        return $this->url_seed;
    }
	
    public function getTokenHash()
    {
        return $this->token_hash;
    }
	
    public function getTokenHashT()
    {
        return $this->token_hash_t;
    }
	
    public function getTokenHashTo()
    {
        return $this->token_hash_to;
    }
	
    public function getSyncTokenHash()
    {
        return $this->sync_token_hash;
    }
	
    public function getSyncTokenHashT()
    {
        return $this->sync_token_hash_t;
    }
	
    public function getSyncTokenHashTo()
    {
        return $this->sync_token_hash_to;
    }
	
    public function getSeqNum()
    {
        return $this->seq_num;
    }
	
    public function getAccessTokenTo()
    {
        return $this->access_token_to;
    }
	
    public function getInitKeyTo()
    {
        return $this->init_key_to;
    }
	
    public function getSyncKeyTo()
    {
        return $this->sync_key_to;
    }
	
    public function getSyncKey()
    {
        return $this->sync_key;
    }
	
    public function getSyncKeyT()
    {
        return $this->sync_key_t;
    }
	
    public function getInitKey()
    {
        return $this->init_key;
    }
	
    public function getInitKeyT()
    {
        return $this->init_key_t;
    }
	
    public function getStatusId()
    {
        return $this->status_id;
    }
	
	public function setUnmToken($unm_token)
	{
		$this->unm_token = $unm_token;
	}
	
	public function setUrlToken($url_token)
	{
		$this->url_token = $url_token;
	}
	
	public function setSeqNum($seq_num)
	{
		$this->seq_num = $seq_num;
	}
}

?>