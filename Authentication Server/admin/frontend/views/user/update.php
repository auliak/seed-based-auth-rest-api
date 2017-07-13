<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */

$this->title = 'Update Data Developer';
//$this->params['breadcrumbs'][] = ['label' => 'Data Developer', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Data Developer', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
