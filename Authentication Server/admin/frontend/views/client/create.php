<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Client */

$this->title = 'Registrasi Aplikasi';
$this->params['breadcrumbs'][] = ['label' => 'Data User', 'url' => ['user/view','id'=>Yii::$app->user->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
