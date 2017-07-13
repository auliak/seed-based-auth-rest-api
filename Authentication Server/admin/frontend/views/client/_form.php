<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model frontend\models\Client */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs("
	$('input:file#client-rootfile').change(function(){
			$('#client-rootfile_rule').val('1');
		});
", View::POS_READY, 'upload-file-rule');

?>

<div class="client-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'nama_app')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'tipe_id')->dropDownList($model->tipeoptions,['prompt'=>'-- Pilih Jenis Aplikasi --','style'=>'width:400px;']) ?>
	
	<?= $form->field($model, 'rootFile')->fileInput() ?>
	<?= $form->field($model, 'rootFile_rule')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
