<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Auth */

$this->title = 'Update Autentikasi Client';
$this->params['breadcrumbs'][] = ['label' => 'Autentikasi Client', 'url' => ['view']];
//$this->params['breadcrumbs'][] = ['label' => $model->client_id, 'url' => ['view', 'id' => $model->client_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auth-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
