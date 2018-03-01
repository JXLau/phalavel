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

    'session' => [
        'adapter' => 'files', // Files, Memcache, Libmemcached, Redis

        // file
        // 'uniqueId' => 'my-app-1',

        // memcache
        // 'host' => '127.0.0.1',
        // 'port' => 11211,
        // 'persistent' => true,
        // 'lifetime' => 3600,
        // 'prefix' => 'prefix_'
        
        // libmemcached
        // 'servers' => array(
        //     ['host' => 'localhost', 'port' => 11211, 'weight' => 1],
        //     // ['host' => 'localhost', 'port' => 11212, 'weight' => 1],
        // ),
        // 'client' => array(
        //     Memcached::OPT_HASH => Memcached::HASH_MD5,
        //     Memcached::OPT_PREFIX_KEY => 'prefix_',
        // ),
        
        // redis
        // "host"       => "127.0.0.1",
        // "port"       => 6379,
        // "auth"       => "foobared",
        // "persistent" => false,
        // "lifetime"   => 3600,
        // "prefix"     => "prefix_",
        // "index"      => 1,
    ],

    'database' => [
        'db'       => [
            'adapter'  => 'Mysql',
            'host'     => '127.0.0.1',
            'username' => 'root',
            'password' => 'root123',
            'dbname'   => 'phalavel',
            'prefix'   => '',
            'charset'  => 'utf8',
        ],
        'db_slave' => [
            'adapter'  => 'Mysql',
            'host'     => '127.0.0.1',
            'username' => 'root',
            'password' => 'root123',
            'dbname'   => 'phalavel',
            'prefix'   => '',
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
