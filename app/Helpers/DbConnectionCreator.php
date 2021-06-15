<?php
/**
 * Date: 3/26/2019
 * Time: 12:55:00 PM
 */

namespace App\Helpers;


use Illuminate\Database\Connection;

class DbConnectionCreator {

    private static $config_prefix = 'database.connections.';

    /**
     * @param string $name
     * @param string $dbName
     * @param string $dbAddress
     * @param string $dbLogin
     * @param string $dbPass
     * @param string $driver
     * @return Connection
     */
    public static function dbConnection( $name, $dbName, $dbAddress, $dbLogin, $dbPass, $driver = 'mysql' ) {

        $connectionName = self::$config_prefix . $name;
        if ( !\config( $connectionName, false ) ) {
            \config( [ $connectionName =>
                [
                    'driver' => $driver,
                    'host' => $dbAddress,
                    'database' => $dbName,
                    'username' => $dbLogin,
                    'password' => $dbPass,
                    'charset' => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix' => '',
                ]
            ] );

            \DB::purge( $name );
        }
        return \DB::connection( $name );
    }
}
