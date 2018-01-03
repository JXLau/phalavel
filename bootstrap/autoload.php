<?php
/**
 * Author: Jason
 */

/*
 * Autoloader
 */

require ROOT . '/vendor/autoload.php';

/*
 * Constant definitions
 */

if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT . '/app');
}

if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', ROOT . '/config');
}

if (!defined('VIEW_PATH')) {
    define('VIEW_PATH', ROOT . '/resources/views');
}

if (!defined('STORAGE_PATH')) {
    define('STORAGE_PATH', ROOT . '/storage');
}

if (!defined('ROUTES_PATH')) {
    define('ROUTES_PATH', ROOT . '/routes');
}

// Register some namespaces
$loader = new \Phalcon\Loader();
$loader->registerNamespaces([
    'App' => ROOT . '/app/',
]);
$loader->register();

return $loader;
