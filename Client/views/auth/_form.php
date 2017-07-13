<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Auth */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'access_token')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'rootFile')->fileInput() ?>

    <?= $form->field($model, 'seq_num')->textInput() ?>
    <?= $form->field($model, 'init_key')->textInput() ?>
    <?= $form->field($model, 'sync_key')->textInput() ?>
    <?= $form->field($model, 'is_active')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
