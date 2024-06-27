<?php
set_time_limit (3600);
// Registry
$registry = new Registry();


$registry->set('detect', new Mobile_Detect());
$registry->set('geoip', new GeoIp());
$registry->set('subdomains', new SubDomains($registry));

// $registry->set('parser', new Parser("http://vend-shop.com/sitemap-shop.xml"));

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$config->load('default');
$config->load($application_config);
$registry->set('config', $config);

// Request
$registry->set('request', new Request());

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);



// Database
if ($config->get('db_autostart')) {
	$registry->set('db', new DB($config->get('db_type'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port')));
}



// Session
if ($config->get('session_autostart')) {
	$session = new Session();
	$session->start();
	$registry->set('session', $session);
}

// Cache 
$registry->set('cache', new Cache($config->get('cache_type'), $config->get('cache_expire')));

// Url
// var_dump($config->get('site_ssl'));
$registry->set('url', new Url(true));

// Language
// echo $config->get('language_default');
$language = new Language($config->get('language_default'));
$language->load($config->get('language_default'));

$registry->set('language', $language);

// Document
$registry->set('document', new Document());

// Event
$event = new Event($registry);
$registry->set('event', $event);

// Event Register
if ($config->has('action_event')) {
	foreach ($config->get('action_event') as $key => $value) {
		$event->register($key, new Action($value));
	}
}

// Config Autoload
if ($config->has('config_autoload')) {
	foreach ($config->get('config_autoload') as $value) {
		$loader->config($value);
	}
}

// Language Autoload
if ($config->has('language_autoload')) {
	foreach ($config->get('language_autoload') as $value) {
		$loader->language($value);
	}
}

$registry->set('dlc', new DLClient("DC2C88F4-51F1-43C3-BF32-F19713364476","https://api.dellin.ru", "xml", "array", "xml", array("RETURN_XML_AS_OBJECT" => true, "USE_XML_AS_OBJECT" => true, "DEBUG" => false)));



$registry->set('unisender', new Unisender());

// Library Autoload
if ($config->has('library_autoload')) {
	foreach ($config->get('library_autoload') as $value) {
		$loader->library($value);
	}
}

// Model Autoload
if ($config->has('model_autoload')) {
	foreach ($config->get('model_autoload') as $value) {
		$loader->model($value);
	}
}

// Front Controller
$controller = new Front($registry);

// Pre Actions
if ($config->has('action_pre_action')) {
	foreach ($config->get('action_pre_action') as $value) {
		$controller->addPreAction(new Action($value));
	}
}

$registry->set('dlc', new DLClient("DC2C88F4-51F1-43C3-BF32-F19713364476","https://api.dellin.ru", "xml", "array", "xml", array("RETURN_XML_AS_OBJECT" => true, "USE_XML_AS_OBJECT" => true, "DEBUG" => false)));

// $registry->set('dadata', new DaDataWrapper("e9727b1bc5cbaf447c27f332e90e25399f2b221b", "c4e1e1cecac985fa9d49e87b1ffe1b42e60143c7"));


$registry->set('youtube', new Youtube($registry));

// if(isset($_GET['ytu'])){
 // $registry->get('youtube')->update();
// }

$registry->set('pusher', new Pusher($registry));
// last id 2920
if(isset($_GET['csv'])) {

//     $db = $registry->get('db');

//     $q = $db->query("SELECT * FROM oc_notifications WHERE 1");

//     $arr = [['Email']];

//     foreach ($q->rows as $row) {
//         $arr[] = [$row['email']];
//     }
//     // Open a file in write mode ('w') 
// $fp = fopen(DIR_SYSTEM . 'contacts3.csv', 'w'); 
//   // print_r($arr);exit;
// // Loop through file pointer and a line 
// foreach ($arr as $fields) { 
//     fputcsv($fp, $fields); 
// } 
  
// fclose($fp); 
}


// Dispatch
$controller->dispatch(new Action($config->get('action_router')), new Action($config->get('action_error')));

// Output
$response->setCompression($config->get('config_compression'));
$response->output();