<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Auth */

$this->title = 'Data Autentikasi Client';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->client_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'client_id',
            //'client_secret',
            'access_token',
			'root_file',
            //'url_token:url',
            //'unm_token',
            'seq_num',
			'init_key',
			'sync_key',
			'is_active'
        ],
    ]) ?>

</div>
