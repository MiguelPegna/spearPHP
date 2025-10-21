<?php

    return [
        'default' => 'jwt', // o 'session'

        'guards' => [
            'session' => [
                'driver'        => 'session',
                'provider'      => 'users',
                'driver_params' => [
                    'current_user', // $sessionKey
                    'user_id',      // $idKey
                ],
            ],
            'jwt' => [
                'driver'        => 'jwt',
                'provider'      => 'users',
                'driver_params' => [
                    env('JWT_KEY'),
                    env('JWT_TIME'),
                ],
            ],
            'token' => [
                'driver'   => 'token',
                'provider' => 'users',
                'hash' => false, // opcional: si quieres que los tokens estén hasheados en DB
                'driver_params' => [
                    64, // longitud de token
                ],
            ],
            'oauth' => [
                'driver'          => 'oauth',
                'provider'        => 'users',
                'driver_params'   => [
                    'clients', // tabla de apps cliente
                    env('OAUTH_SECRET'),
                ],
            ],
            /*
            'providers' => [
                'users' => [
                    'model' => App\Models\User::class,
                ],
                'clients' => [
                    'model' => App\Models\Client::class, // para OAuth
                ],
            ],
            */
        ]
    ];