<?php
/**
 * Author: Jason
 */
return [
    'debug'    => true,

    'timezone' => 'PRC',

    'cache'    => [
        'driver'    => 'redis',
        'lifetime' => 86400,
        'file'      => [
            'dir' => ROOT . '/storage/framework/cache/',
        ],
        'prefix' => 'cache_prefix_',
        'memcached' => [
            [
                'host'   => '127.0.0.1',
                'port'   => '11211',
                'weight' => '100'
            ],
        ],
        'redis'     => [
            'host'       => 'localhost',
            'port'       => 6379,
            // 'auth'       => 'foobared',
            'persistent' => false,
        ]
    ],

    'database' => [
        'db'       => [
            'adapter'  => 'Mysql',
            'host'     => '127.0.0.1',
            'username' => 'username',
            'password' => 'password',
            'dbname'   => 'dbname',
            'prefix'   => 'prefix',
            'charset'  => 'utf8',
        ],
        'db_slave' => [
            'adapter'  => 'Mysql',
            'host'     => '127.0.0.1',
            'username' => 'username',
            'password' => 'password',
            'dbname'   => 'dbname',
            'prefix'   => 'prefix',
            'charset'  => 'utf8',
        ],
    ],

    'log'      => [
        'path'  => ROOT . '/storage/logs/',
        'period' => 'daily',
        'level' => 'debug',
    ],
    'queue' => [
        'driver' => 'redis',
    ]
];
