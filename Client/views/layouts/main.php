<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Aplikasi Client',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
			['label' => 'Uji Fungsional', 'items' => [
				['label' => 'Inisialisasi', 'url' => ['auth/inisialisasi']],
				['label' => 'Identifikasi', 'url' => ['request/identifikasi']],
				['label' => 'Autentikasi', 'url' => ['request/autentikasi']],
				['label' => 'Sinkronisasi', 'url' => ['auth/sinkronisasi']],
				['label' => 'Request Data', 'url' => ['request/index']],
			]],
			['label' => 'Uji Kinerja', 'items' => [
				'<li class="dropdown-header">Seed Based</li>',
				['label' => 'Inisialisasi', 'url' => ['time/inisialisasi']],
				['label' => 'Identifikasi', 'url' => ['time/identifikasi']],
				['label' => 'Autentikasi', 'url' => ['time/autentikasi']],
				['label' => 'Sinkronisasi', 'url' => ['time/sinkronisasi']],
				['label' => 'Ident + Auth', 'url' => ['time/identauth']],
				['label' => 'Request Data', 'url' => ['time/reqdata']],
				'<li class="divider"></li>',
                '<li class="dropdown-header">Token Based</li>',
				['label' => 'Autentikasi', 'url' => ['time/tokenbased']],
				['label' => 'Request Data', 'url' => ['time/reqtokenbased']],
			]],
			['label' => 'Xdebug', 'items' => [
				['label' => 'Inisialisasi', 'url' => ['xdebug/inisialisasi']],
				['label' => 'Identifikasi', 'url' => ['xdebug/identifikasi']],
				['label' => 'Autentikasi', 'url' => ['xdebug/autentikasi']],
				['label' => 'Sinkronisasi', 'url' => ['xdebug/sinkronisasi']],
				['label' => 'Request Data', 'url' => ['xdebug/index']],
			]],
            ['label' => 'Data Autentikasi', 'url' => ['/auth/view']],
            //['label' => 'About', 'url' => ['/site/about']],
            //['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Auliak Amri <?= date('Y') ?></p>

        <p class="pull-right">Rekayasa dan Manajemen Keamanan Informasi - ITB</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
