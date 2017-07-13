<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Client */

$this->title = 'Update Aplikasi: ' . $model->nama_app;
//$this->params['breadcrumbs'][] = ['label' => 'Data User', 'url' => ['user/view','id'=>Yii::$app->user->id]];
$this->params['breadcrumbs'][] = ['label' => 'Client', 'url' => ['client/index','id'=>Yii::$app->user->id]];
$this->params['breadcrumbs'][] = ['label' => $model->nama_app, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="client-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
