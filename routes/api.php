<?php

$routers = [
    "/api/:controller/:action/:params" => [
        "methods"    => ['GET', 'POST'],
        "namespace"  => "App\Controllers\Api",
        "controller" => 1,
        "action"     => 2,
        "params"     => 3,
    ],
];

return $routers;
