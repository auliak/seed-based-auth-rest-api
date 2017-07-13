<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

define('WEB_DIRECTORY', __DIR__);

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('G\Silex', __DIR__.'/../src');

use G\Silex\Application;

$app = new Application();
$app['debug'] = true;

// Koneksi database
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
	'db.options' => array(
		'driver' => 'pdo_mysql',
		'host' => 'localhost',
		'dbname' => 'authdb',
		'user' => 'root',
		'password' => '',
	),
));

$app->error(function ( \Exception $e, $code ) use ($app) {
    // Mengirim pesan error
    return $e->getMessage();
});

$app->run();
