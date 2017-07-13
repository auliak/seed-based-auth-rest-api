<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\UbahPasswordForm */
/* @var $form ActiveForm */

$this->title = 'Ubah Password';
//$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Data Developer', 'url' => ['view', 'id' => Yii::$app->user->identity->id]];
$this->params['breadcrumbs'][] = 'Ubah password';
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="user-ubahpassword">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'new_password')->passwordInput() ?>
        <?= $form->field($model, 'new_password_repeat')->passwordInput() ?>
    
        <div class="form-group">
            <?= Html::submitButton('Ubah password', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- user-ubahpassword -->
