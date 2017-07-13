<?php

namespace frontend\controllers;
error_reporting(E_ALL ^ E_NOTICE);
use Yii;
use frontend\models\Client;
use frontend\models\ClientSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
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
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function(){
							return Yii::$app->user->identity->id == $_GET['user_id'];
						},
                        'roles' => ['@'],
                    ],
					[
                        'actions' => ['update','view','delete','initkey','synckey','index'],
                        'allow' => true,
                        'matchCallback' => function(){
							return Yii::$app->user->identity->id == $this->findModel($_GET['id'])->user_id;
						},
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Client model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Client();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->rootFile_rule = 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Client model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
		
		if(!empty($model->root_file)){
			$path = '../../authenticater/web/file/temp/'.$model->root_file;
			
			if(file_exists($path))
				unlink($path);
		}

        return $this->redirect(['user/view','id'=>Yii::$app->user->identity->id]);
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionInitkey($id)
	{
		$model = $this->findModel($id);
		
		$initkey = Yii::$app->security->generateRandomString();
		
		$model->init_key = $initkey;
		$model->init_key_t = time();
		$model->rootFile_rule=1;
		
		if($model->save())
			return true;
		else
			return false;
	}
	
	public function actionSynckey($id)
	{
		$model = $this->findModel($id);
		
		$synckey = Yii::$app->security->generateRandomString();
		
		$model->sync_key = $synckey;
		$model->sync_key_t = time();
		$model->rootFile_rule=1;
		$model->sync_key_to = 1000;
		
		if($model->save())
			return true;
		else
			return false;
	}
}
