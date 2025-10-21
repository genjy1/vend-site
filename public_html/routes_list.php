<?php
define('DIR_APPLICATION', __DIR__ . '/catalog/');
require_once(__DIR__ . '/config.php');
require_once(DIR_SYSTEM . 'startup.php');

// Загрузка класса FrontController
$registry = new Registry();
$loader = new Loader($registry);
$registry->set('load', $loader);

// Функция для получения всех маршрутов
function get_routes($dir, $prefix = '') {
    $routes = [];
    foreach (glob($dir . '/*') as $file) {
        if (is_dir($file)) {
            $routes = array_merge($routes, get_routes($file, $prefix . basename($file) . '/'));
        } elseif (is_file($file)) {
            $routes[] = $prefix . basename($file, '.php');
        }
    }
    return $routes;
}

// Путь к папке с контроллерами
$catalog_routes = get_routes(DIR_APPLICATION . 'controller');
$admin_routes = get_routes(DIR_SYSTEM . '../admin/controller');

// Вывод всех маршрутов
echo "<h3>Маршруты каталога:</h3><ul>";
foreach ($catalog_routes as $route) {
    echo "<li>catalog/controller/" . $route . ".php => index.php?route=" . $route . "</li>";
}
echo "</ul><h3>Маршруты админки:</h3><ul>";
foreach ($admin_routes as $route) {
    echo "<li>admin/controller/" . $route . ".php => admin/index.php?route=" . $route . "</li>";
}
echo "</ul>";
