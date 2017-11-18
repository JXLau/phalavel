<?php
/**
 * Author: Jason
 */
/*
 * Autoloader
 */

$loader = require ROOT . '/bootstrap/autoload.php';

$app = new \App\Foundation\Application($loader);

return $app;
