<?php


// default connection
$array = array(
    'default' => env('DB_DEFAULT', 'maria'),
    'partner' => 'partner',
    'migrations' => 'migrations',
    'redis' => [

        'client' => 'predis',

        'cluster' => env('REDIS_CLUSTER', false),

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DB', 0),
        ],

        'cache' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_CACHE_DB', 1),
        ],

        'jobs' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_QUEUE_DB', 2),
        ],

    ],
);

$connections = [];

$prefix = __DIR__ . DIRECTORY_SEPARATOR . 'database/';
$files = scandir( $prefix );
foreach ( $files as $file ) {
    if ( is_file( $prefix . $file ) ) {
        $connections = array_merge( $connections, require $prefix . $file );
    }
}

$array[ 'connections' ] = $connections;
return $array;
