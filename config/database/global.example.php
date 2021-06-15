<?php
return array(
    # Our primary database connection
    'example' => array(
        'driver' => 'mysql',
        'host' => 'host',
        'database' => 'db',
        'username' => 'login',
        'password' => 'password',
        'charset' => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix' => '',
        'options'   => [
            /*\PDO::ATTR_EMULATE_PREPARES => true*/
        ]
    )
);