<?php 
require_once __DIR__.'/../config.php';
require_once('vendor/autoload.php');


$session = new Gears\Session();
// Configure the session container
$session->dbConfig = 
[
	'driver'    => 'mysql',
	'host'      => HOST,
	'database'  => DBNAME,
	'username'  => USERNAME,
	'password'  => PASSWORD,
	'charset'   => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix'    => '',
];

// Install the session api
$session->install();

// Next you will probably want to make the session object global.
$session->globalise();

