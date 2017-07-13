<?php

namespace app\controllers;

use app\models\Auth;

class TimeController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
	
	public function actionIdentifikasi()
    {
        return $this->render('identifikasi', array(
		));
    }
	
	public function actionAutentikasi()
	{
        return $this->render('autentikasi', array(
		));
	}
	
	public function actionSinkronisasi()
	{
        return $this->render('sinkronisasi', array(
		));
	}
	
	public function actionInisialisasi()
	{
		return $this->render('inisialisasi', [
        ]);
	}
	
	public function actionIdentauth()
    {
        return $this->render('identauth', array(
		));
    }
	
	public function actionReqdata()
    {
        return $this->render('reqdata', array(
		));
    }
	
	public function actionTokenbased()
    {
        return $this->render('authtokenbased', array(
		));
    }
	
	public function actionReqtokenbased()
    {
        return $this->render('reqtokenbased', array(
		));
    }
}
