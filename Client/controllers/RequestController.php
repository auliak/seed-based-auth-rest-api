<?php

namespace app\controllers;

use Yii;
use app\models\Auth;

class RequestController extends \yii\web\Controller
{
    public function actionIndex()
    {
		$auth = Auth::findOne(array('is_active'=>1));
		$access_token = $auth->access_token;
		
		$seed = Auth::generateSeed($auth->root_file);
		$seq_num = $auth->seq_num;
		$unm_token = Auth::generateToken($seed['unmseed'],$seq_num);
		$url_token = Auth::generateToken($seed['urlseed'],$seq_num);
		
        return $this->render('index', array(
			'access_token' => $access_token,
			'url_token' => $url_token,
			'unm_token' => $unm_token,
			'seq_num' => $seq_num,
		));
    }
	
	public function actionIdentifikasi()
    {
		$auth = Auth::findOne(array('is_active'=>1));
		
		$seed = Auth::generateSeed($auth->root_file);
		$seq_num = $auth->seq_num;
		$unm_token = Auth::generateToken($seed['unmseed'],$seq_num);
		$url_token = Auth::generateToken($seed['urlseed'],$seq_num);
		
        return $this->render('identifikasi', array(
			'url_token' => $url_token,
			'unm_token' => $unm_token,
			'seq_num' => $seq_num,
		));
    }
	
	public function actionAutentikasi()
    {
		$auth = Auth::findOne(array('is_active'=>1));
		
		$seed = Auth::generateSeed($auth->root_file);
		$seq_num = $auth->seq_num;
		$unm_token = Auth::generateToken($seed['unmseed'],$seq_num);
		$url_token = Auth::generateToken($seed['urlseed'],$seq_num);
		
        return $this->render('autentikasi', array(
			'url_token' => $url_token,
			'unm_token' => $unm_token,
			'seq_num' => $seq_num,
		));
    }

}
