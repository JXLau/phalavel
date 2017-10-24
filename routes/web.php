<?php

$routers = [
    "/"                                  => [
        "methods"    => ['GET'],
        "namespace"  => "App\Controllers",
        "controller" => "Home",
        "action"     => "index",
    ],
    "/:controller/:action/:params"       => [
        "methods"    => ['GET', 'POST'],
        "namespace"  => "App\Controllers",
        "controller" => 1,
        "action"     => 2,
        "params"     => 3,
    ],
    "/admin/:controller/:action/:params" => [
        "methods"    => ['GET', 'POST'],
        "namespace"  => "App\Controllers\Admin",
        "controller" => 1,
        "action"     => 2,
        "params"     => 3,
    ],
];

return $routers;
