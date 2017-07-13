<?php

namespace app\controllers;

use Yii;
use app\models\Auth;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AuthController implements the CRUD actions for Auth model.
 */
class AuthController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
					[
                        'actions' => ['index','create','update','view','delete',],
                        'allow' => true,
                        'matchCallback' => function(){
							return Yii::$app->user->identity->username == 'admin';
						},
                        'roles' => ['@'],
                    ],
					[
                        'actions' => ['savetoken','saveseqnum','gettoken','geturlunmtoken','inisialisasi','sinkronisasi','gettokenhash','gettokenhashforsync','getauth','getauthtokenbased','getsynckey','getinitkey'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Auth models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Auth::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Auth model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id=29)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Auth model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Auth();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->client_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Auth model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->client_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Auth model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Auth model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Auth the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Auth::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	
	public function actionSavetoken($token)
	{
		$auth = Auth::findOne(array('is_active'=>1));
		$auth->access_token = $token;
		$auth->save();
		
		$info = array(
			'status' => 'true',
			'token' => $token,
		);
		
		return json_encode($info);
	}
	
	public function actionSaveseqnum($seq_num)
	{
		$auth = Auth::findOne(array('is_active'=>1));
		$auth->seq_num = $seq_num;
		$auth->save();
		
		$info = array(
			'status' => 'true'
		);
		
		return json_encode($info);
	}
	
	public function actionGettoken()
	{
		$auth = Auth::findOne(array('is_active'=>1));
		
		return $auth->access_token;
	}
	
	public function actionInisialisasi()
	{
		$auth = Auth::findOne(array('is_active'=>1));
		
		$init_key = $auth->init_key;
		
		$seed = Auth::generateSeed($auth->root_file);
		$seq_num = $auth->seq_num;
		$unm_token = Auth::generateToken($seed['unmseed'],$seq_num);
		$url_token = Auth::generateToken($seed['urlseed'],$seq_num);
		
		return $this->render('inisialisasi', [
            'init_key' => $init_key,
            'unm_token' => $unm_token,
            'url_token' => $url_token,
            'seq_num' => $seq_num,
        ]);
	}
	
	public function actionSinkronisasi()
	{
		$auth = Auth::findOne(array('is_active'=>1));
		$sync_key = $auth->sync_key;
		
        return $this->render('sinkronisasi', array(
			'sync_key' => $sync_key,
		));
	}
	
	public function actionGettokenhash()
	{
		$n1 = $_GET['n1'];
		$n2 = $_GET['n2'];
		$n3 = $_GET['n3'];
		$n4 = $_GET['n4'];
		
		$authdata = Auth::findOne(array('is_active'=>1));
		$rootfile = $authdata->root_file;
		
		$auth = new Auth;
		$hashvalue = $auth->generateTokenHash($rootfile, $n1,$n2,$n3,$n4);
		
		$seed = Auth::generateSeed($rootfile);
		$seq_num = $authdata->seq_num;
		$unm_token = Auth::generateToken($seed['unmseed'],$seq_num);
		$url_token = Auth::generateToken($seed['urlseed'],$seq_num);
		
		$hasil = array(
			'unm_token' => $unm_token,
            'url_token' => $url_token,
			'seq_num' => $seq_num,
			'hash_value' => $hashvalue,
		);
		
		return json_encode($hasil);
	}
	
	public function actionGettokenhashforsync()
	{
		$n1 = $_GET['n1'];
		$n2 = $_GET['n2'];
		$n3 = $_GET['n3'];
		$n4 = $_GET['n4'];
		
		$authdata = Auth::findOne(array('is_active'=>1));
		$rootfile = $authdata->root_file;
		
		$auth = new Auth;
		$hashvalue = $auth->generateTokenHash($rootfile, $n1,$n2,$n3,$n4);
		
		return $hashvalue;
	}
	
	public function actionGetauth()
	{
		$auth = Auth::findOne(array('is_active'=>1));
		$access_token = $auth->access_token;
		
		$seed = Auth::generateSeed($auth->root_file);
		$seq_num = $auth->seq_num;
		$unm_token = Auth::generateToken($seed['unmseed'],$seq_num);
		$url_token = Auth::generateToken($seed['urlseed'],$seq_num);
		
		$hasil = array(
			'url_token' => $url_token,
			'unm_token' => $unm_token,
			'seq_num' => $seq_num,
			'token' => $access_token,
		);
		
		return json_encode($hasil);
	}
	
	public function actionGetauthtokenbased()
	{
		$auth = Auth::findOne(array('is_active'=>1));
		$access_token = $auth->access_token;
		$user = $auth->username;
		$pass = $auth->password;
		
		$hasil = array(
			'user' => $user,
			'pass' => $pass,
			'token' => $access_token,
		);
		
		return json_encode($hasil);
	}
	
	public function actionGetsynckey()
	{
		$auth = Auth::findOne(array('is_active'=>1));

		$sync_key = $auth->sync_key;
		
		$hasil = array(
			'sync_key' => $sync_key
		);
		
		return json_encode($hasil);
	}
	
	public function actionGetinitkey()
	{
		$auth = Auth::findOne(array('is_active'=>1));
		
		$seed = Auth::generateSeed($auth->root_file);
		$seq_num = $auth->seq_num;
		$unm_token = Auth::generateToken($seed['unmseed'],$seq_num);
		$url_token = Auth::generateToken($seed['urlseed'],$seq_num);
		$init_key = $auth->init_key;
		
		$hasil = array(
			'url_token' => $url_token,
			'unm_token' => $unm_token,
			'seq_num' => $seq_num,
			'init_key' => $init_key
		);
		
		return json_encode($hasil);
	}
}
