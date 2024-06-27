<?php
// $username = posix_getpwuid(posix_geteuid())['name'];
// die($username);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ERROR);
// test ftp
if(preg_match("/(\/tag\/)/i", $_SERVER['REQUEST_URI'])){
header("Location: /",TRUE,301);
}
// Version
define('VERSION', '2.2.0.0');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// //V2Pagecache fix

/* $str = $_SERVER['HTTP_HOST'];

$array = explode('.', $str);

$array = array_slice ($array, 0, -2);

$array = array_reverse($array);


if( !isset($_SERVER['HTTPS'] ) ) {
	if ($array[0] == 'abakan') { header("HTTP/1.1 301 Moved Permanently"); header("Location: https://abakan.vend-shop.com/"); exit(); }
} */


// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}

if (file_exists($li = DIR_APPLICATION.'/controller/extension/lightning/gamma.php')) require_once($li); //Lightning

// Startup
require_once(DIR_SYSTEM . 'startup.php');

$application_config = 'catalog';


// Application
require_once(DIR_SYSTEM . 'framework.php');

