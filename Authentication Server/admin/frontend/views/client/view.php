<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\Client */

$this->title = $model->nama_app;
//$this->params['breadcrumbs'][] = ['label' => 'Data User', 'url' => ['user/view','id'=>Yii::$app->user->id]];
$this->params['breadcrumbs'][] = ['label' => 'Client', 'url' => ['client/index','id'=>Yii::$app->user->id]];
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="client-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
		
    </p>

	<?php Pjax::begin(['enablePushState'=>FALSE,'id' => 'detail-view',]) ?>
	
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'nama_app',
            //'tipe_id',
			[
				'attribute'=>'tipe_app',
				'value'=>$model->tipe->tipe_app,
			],
            'root_file',
            //'unm_seed',
            //'url_seed:url',
            //'seq_num',
            //'unm_token',
            //'url_token:url',
            //'next_unm_token',
            //'next_url_token:url',
			//'status_id',
			[
				'attribute'=>'status_app',
				'format'=>'raw',
				'value'=>$model->status->status,
			],
			'access_token',
			'access_token_t',
			//'created_at',
			[
				'attribute'=>'init_key',
				'format'=>'raw',
				'value'=>Html::a('Request Key', '#', ['id'=>'btn-init', 'class' => 'btn btn-success']).' '.$model->init_key,
			],
			'init_key_t',
			[
				'attribute'=>'sync_key',
				'format'=>'raw',
				'value'=>Html::a('Request Key', '#', ['id'=>'btn-sync', 'class' => 'btn btn-success']).' '.$model->sync_key,
			],
			'sync_key_t',
            //'user_id',
			/*[
				'attribute'=>'username',
				'value'=>$model->user->username,
			],*/
        ],
    ]) ?>
	
	<?php
	
	$this->registerJs(
	   '$("document").ready(function(){ 
			$("#btn-init").click(function(){
				requestInitKey();
				return false;
			});
			
			$("#btn-sync").click(function(){
				requestSyncKey();
				return false;
			});
		});
		
		function requestInitKey(){
			$.ajax({
				url: "'.Yii::$app->getUrlManager()->createUrl('client/initkey').'",
				data:"id='.$model->id.'",
				type: "GET",
				success: function(result){
					$.pjax.reload({container:"#detail-view"});  
				}
			});
		}
		
		function requestSyncKey(){
			$.ajax({
				url: "'.Yii::$app->getUrlManager()->createUrl('client/synckey').'",
				data:"id='.$model->id.'",
				type: "GET",
				success: function(result){
					$.pjax.reload({container:"#detail-view"});  
				}
			});
		}
		'
	);
	
	?>
	
	<?php Pjax::end(); ?>

</div>
