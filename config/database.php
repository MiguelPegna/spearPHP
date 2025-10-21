<?php
    return [
        'default' => env('DB_DEFAULT', 'local'),
        'connections' => [
            'local' => [
                'driver'  => env('DB_DRIVER'),
                'host'    => env('DB_HOST'),
                'port'    => env('DB_PORT'),
                'database'=> env('DB_NAME'),
                'user'    => env('DB_USER'),
                'password'=> env('DB_PSW'),
                'charset' => env('DB_CHARSET'),
            ],
            'sql_server'  => [
                'driver'  => env('DB_DRIVER_2'),
                'host'    => env('DB_HOST_2'),
                'database'=> env('DB_NAME_2'),
                'user'    => env('DB_USER_2'),
                'password'=> env('DB_PSW_2'),
                'charset' => env('DB_CHARSET_2')
            ],
            'sqlite' => [
                'driver' => env('DB_SQLITE'),
                'name'   => env('DB_SQLITE_FILE', __DIR__ . '/../../database/database.sqlite')
            ]
        ]
    ];