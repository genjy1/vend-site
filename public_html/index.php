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

$subdomains = [
    "moskva",
    "sankt-peterburg",
    "novosibirsk",
    "ekaterinburg",
    "nizhnii-novgorod",
    "samara",
    "omsk",
    "kazan",
    "cheliabinsk",
    "rostov-na-donu",
    "ufa",
    "volgograd",
    "perm",
    "krasnoiarsk",
    "voronezh",
    "saratov",
    "krasnodar",
    "toliatti",
    "izhevsk",
    "ulianovsk",
    "barnaul",
    "vladivostok",
    "iaroslavl",
    "irkutsk",
    "tiumen",
    "makhachkala",
    "khabarovsk",
    "orenburg",
    "novokuznetck",
    "kemerovo",
    "riazan",
    "tomsk",
    "astrakhan",
    "penza",
    "naberezhnye-chelny",
    "lipetck",
    "tula",
    "kirov",
    "cheboksary",
    "kaliningrad",
    "briansk",
    "kursk",
    "ivanovo",
    "magnitogorsk",
    "tver",
    "stavropol",
    "nizhnii-tagil",
    "belgorod",
    "arkhangelsk",
    "vladimir",
    "sochi",
    "kurgan",
    "smolensk",
    "kaluga",
    "chita",
    "orel",
    "volzhskii",
    "cherepovetc",
    "vladikavkaz",
    "murmansk",
    "surgut",
    "vologda",
    "saransk",
    "tambov",
    "sterlitamak",
    "groznyi",
    "iakutsk",
    "kostroma",
    "komsomolsk-na-amure",
    "petrozavodsk",
    "taganrog",
    "nizhnevartovsk",
    "ioshkar-ola",
    "bratsk",
    "novorossiisk",
    "dzerzhinsk",
    "shakhty",
    "nalchik",
    "orsk",
    "syktyvkar",
    "nizhnekamsk",
    "angarsk",
    "staryi-oskol",
    "velikii-novgorod",
    "balashikha",
    "blagoveshchensk",
    "prokopevsk",
    "biisk",
    "khimki",
    "pskov",
    "engels",
    "rybinsk",
    "balakovo",
    "iuzhno-sakhalinsk",
    "severodvinsk",
    "armavir",
    "podolsk",
    "korolev",
    "petropavlovsk-kamchatskii",
    "syzran",
    "norilsk",
    "zlatoust",
    "mytishchi",
    "liubertcy",
    "volgodonsk",
    "novocherkassk",
    "abakan",
    "nakhodka",
    "ussuriisk",
    "berezniki",
    "salavat",
    "elektrostal",
    "miass",
    "pervouralsk",
    "rubtcovsk",
    "almetevsk",
    "kovrov",
    "kolomna",
    "maikop",
    "piatigorsk",
    "odintcovo",
    "kopeisk",
    "khasaviurt",
    "zheleznodorojniy",
    "novomoskovsk",
    "kislovodsk",
    "serpukhov",
    "novocheboksarsk",
    "nefteiugansk",
    "dimitrovgrad",
    "neftekamsk",
    "cherkessk",
    "orekhovo-zuevo",
    "derbent",
    "kamyshin",
    "nevinnomyssk",
    "krasnogorsk",
    "murom",
    "bataisk",
    "novoshakhtinsk",
    "sergiev-posad",
    "noiabrsk",
    "shchelkovo",
    "kyzyl",
    "oktiabrskii",
    "achinsk",
    "seversk",
    "novokuibyshevsk",
    "eletc",
    "arzamas",
    "obninsk",
    "novyi-urengoi",
    "kaspiisk",
    "elista",
    "pushkino",
    "zhukovskii",
    "essentuki",
    "noginsk",
    "ramenskoe",
    "domodedovo",
    "nazran",
    "dolgoprudnyi",
    "reutov",
    "klin",
    "troitsk",
    "dubna",
    "stupino",
    "pavlovskiy-posad",
    "dmitrov",
    "chehov",
    "vidnoe",
    "solnechnogorsk",
    "chernogolovka",
    "puschino",
];


$host = $_SERVER['HTTP_HOST'];
$parts = explode('.', $host);

// Если домен типа "example.com" или "www.example.com"
if (count($parts) <= 2 || $parts[0] === 'www') {
    require_once(DIR_SYSTEM . 'framework.php');
    exit;
}

// Если 3 и более частей — это поддомен
$subdomain = $parts[0];

// Проверяем, есть ли поддомен в списке
if (!in_array($subdomain, $subdomains, true)) {
    header('HTTP/1.1 404 Not Found');
    exit();
}

// Поддомен разрешён → грузим сайт
require_once(DIR_SYSTEM . 'framework.php');
exit;


