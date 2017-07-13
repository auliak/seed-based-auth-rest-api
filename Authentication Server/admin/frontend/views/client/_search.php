<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ClientSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nama_app') ?>

    <?= $form->field($model, 'tipe_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'status_id') ?>

    <?php // echo $form->field($model, 'root_file') ?>

    <?php // echo $form->field($model, 'url_seed') ?>

    <?php // echo $form->field($model, 'unm_seed') ?>

    <?php // echo $form->field($model, 'seq_num') ?>

    <?php // echo $form->field($model, 'url_token') ?>

    <?php // echo $form->field($model, 'unm_token') ?>

    <?php // echo $form->field($model, 'access_token') ?>

    <?php // echo $form->field($model, 'access_token_t') ?>

    <?php // echo $form->field($model, 'access_token_to') ?>

    <?php // echo $form->field($model, 'init_key') ?>

    <?php // echo $form->field($model, 'init_key_t') ?>

    <?php // echo $form->field($model, 'init_key_to') ?>

    <?php // echo $form->field($model, 'sync_key') ?>

    <?php // echo $form->field($model, 'sync_key_t') ?>

    <?php // echo $form->field($model, 'sync_key_to') ?>

    <?php // echo $form->field($model, 'token_hash') ?>

    <?php // echo $form->field($model, 'token_hash_t') ?>

    <?php // echo $form->field($model, 'token_hash_to') ?>

    <?php // echo $form->field($model, 'sync_token_hash') ?>

    <?php // echo $form->field($model, 'sync_token_hash_t') ?>

    <?php // echo $form->field($model, 'sync_token_hash_to') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
