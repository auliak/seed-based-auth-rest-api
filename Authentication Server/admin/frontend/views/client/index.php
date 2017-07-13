<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Client';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <h1><?= Html::encode('Daftar Aplikasi') ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Registrasi Aplikasi', ['create', 'user_id' => Yii::$app->user->identity->id], ['class' => 'btn btn-success']) ?>
    </p>
	
    <?php yii\widgets\Pjax::begin(['enablePushState'=>FALSE,'id' => 'grid-view',]) ?>
	
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'project_id',
            'nama_app',
            //'tipe_id',
			[
				'attribute'=>'tipe',
				'value'=>function ($data) {
					return $data->tipe->tipe_app;
				},
			],
            'root_file',
			[
				'attribute'=>'status',
				'value'=>function ($data) {
					return $data->status->status;
				},
			],
            //'unm_seed',
            // 'url_seed:url',
            // 'seq_num',
            // 'unm_token',
            // 'url_token:url',
            // 'next_unm_token',
            // 'next_url_token:url',
            // 'user_id',

            [
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view} {update} {delete}',
				'buttons' => [
					'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['client/view','id'=>$model->id], [
							'title' => Yii::t('app', 'View'),
						]);
					},
					'update' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['client/update','id'=>$model->id], [
							'title' => Yii::t('app', 'Update'),
						]);
					},
					'delete' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['client/delete','id'=>$model->id], [
							'title' => Yii::t('app', 'Delete'),
							'data' => [
								'confirm' => 'Are you sure you want to delete this item?',
								'method' => 'post',
							],
						]);
					}
				],
			],
        ],
    ]); ?>
	
	<?php \yii\widgets\Pjax::end(); ?>
</div>
