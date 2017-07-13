<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

define('WEB_DIRECTORY', __DIR__);

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('G\Silex', __DIR__.'/../src');

use G\Silex\Application;

$app = new Application();
$app['debug'] = true;

// Koneksi database autentikasi
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
	'db.options' => array(
		'driver' => 'pdo_mysql',
		'host' => '192.168.182.136',
		'dbname' => 'authdb',
		'user' => 'root',
		'password' => '',
	),
));

// Koneksi database resource
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => array (
        'auth' => array(
            'driver' => 'pdo_mysql',
			'host' => '192.168.182.136',
			'dbname' => 'authdb',
			'user' => 'root',
			'password' => '',
        ),
        'resource' => array(
            'driver' => 'pdo_mysql',
			'host' => 'localhost',
			'dbname' => 'resourcedb',
			'user' => 'root',
			'password' => '',
        ),
    ),
));

$app->options('/data'.'/{urltoken}/{unmtoken}/{seqnum}', function (Request $request) use ($app) {
	return true;
});

$app->get('/data'.'/{urltoken}/{unmtoken}/{seqnum}', function (Application $app, Request $request, $urltoken, $unmtoken, $seqnum) {
	
	// Resource dari database
	$sql = "SELECT * FROM products";
	
	$products = $app['dbs']['resource']->fetchAll($sql);
	
	foreach($products as $p){
		$data[]=array(
			'productCode'=>$p['productCode'],
			'productName'=>iconv('UTF-8', 'UTF-8//IGNORE', $p['productName']),
			'productLine'=>$p['productLine'],
			'productVendor'=>$p['productVendor'],
			'productDescription'=>iconv('UTF-8', 'UTF-8//IGNORE', $p['productDescription']),
			'quantityInStock'=>$p['quantityInStock'],
			'buyPrice'=>$p['buyPrice'],
		);
	}
	
	return $app->json($data);
});

$app->run();
