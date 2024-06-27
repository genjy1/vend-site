<?php


require_once(realpath(dirname(__FILE__)) . '/../../admin/config.php');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

$registry->set('youtube', new Youtube($registry));

$registry->get('youtube')->update();

error_log("youtube videos updated");

?>