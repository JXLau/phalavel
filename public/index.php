<?php

use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\WebServiceProvider;

define('ROOT', dirname(realpath(__DIR__)));

$app = require ROOT . '/bootstrap/app.php';
$app->setFactory('web');
$app->registerServiceProviders([
    AppServiceProvider::class,
    WebServiceProvider::class,
    RouteServiceProvider::class,
]);
$app->run();
