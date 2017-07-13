<?php

/* @var $this yii\web\View */

$this->title = 'Aplikasi Administrator';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Aplikasi Administrator</h1>

        <p class="lead">Sistem pengelolaan aplikasi client oleh developer aplikasi</p>

        
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Aplikasi Administrator</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="<?php echo Yii::$app->urlManager->createUrl('site/index');?>">Aplikasi Administrator &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Aplikasi API</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://<?php echo Yii::$app->request->hostName;?>/tesis/webservice/api/web/test.php">Aplikasi API &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Aplikasi Client</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://<?php echo Yii::$app->request->hostName;?>/tesis/webservice/client/web/index.php/site/index">Aplikasi Client &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
