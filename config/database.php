<?php
    return [
        /* 'client' => 'predis', */
        'default' => 'mysql',
         'connections' => [
             /*
            'sqlite' => [
                'driver' => 'sqlite',
                'database' => env('DB_DATABASE', database_path('database.sqlite')),
                'prefix' => '',
            ], */
    
            'mysql' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', 'localhost'),
                'port' => env('DB_PORT', '3306'),
                'database' => env('DB_DATABASE', 'lumenapi'),
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'strict' => false,
                'engine' => null,
            ],
    
            /* 'pgsql' => [
                'driver' => 'pgsql',
                'host' => env('DB_HOST', 'localhost'),
                'port' => env('DB_PORT', '5432'),
                'database' => env('DB_DATABASE', 'lumenapi'),
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8',
                'prefix' => '',
                'schema' => 'public',
            ], */
            /* 'mongodb' => [ 
                'driver' => 'mongodb',
                'host' => env('DB_HOST', 'localhost'),
                'port' => env('DB_PORT', 27017),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'options' => [
                    'database' => 'admin' // sets the authentication database required by mongo 3
                ]
            ], */
        ],
        'migrations' => 'migrations',
        /* REDIS DATABASE */
        'redis' => [
            'cluster' => false,
            'default' => [
                'host' => '127.0.0.1',
                'port' => '6379',
                'database' => 0,
            ]
        ],
    ];