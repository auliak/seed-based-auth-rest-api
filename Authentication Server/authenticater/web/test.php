<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

define('WEB_DIRECTORY', __DIR__);

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

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

$app->get('/', function (Silex\Application $app, Request $request) {

	// tes database
	$sql = "SELECT * FROM employees";
	
	$pelanggan = $app['db']->fetchAll($sql);
	
	foreach($pelanggan as $p){
		$data[]=array(
			'id'=>$p['employeeNumber'],
			'nama'=>$p['firstName'].' '.$p['lastName'],
			'email'=>$p['email'],
		);
	}
	
	return $app->json($pelanggan);
});

$app->get('/statusinisialisasi', function (Silex\Application $app, Request $request) {
	
	$sql = "UPDATE client SET status_id = 0, seq_num = 0  WHERE nama_app= 'app-kinerja'";
	$app['db']->executeUpdate($sql, array());

	$path = WEB_DIRECTORY.'/file/temp/tulips_backup.png';
	$path2 = WEB_DIRECTORY.'/file/temp/tulips.png';
	
	if(file_exists($path))
	{
		if(!copy($path,$path2))
			$status = 'gagal copy';
		else
			$status = 'berhasil copy';
	}
	else
		$status = 'file tidak ada';
	
	return $app->json([
		'status' => $status,
		'info'   => [],
	]);
});

$app->run();
