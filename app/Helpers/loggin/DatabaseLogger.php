<?php


namespace App\Helpers\loggin;


use Monolog\Logger;

class DatabaseLogger {
    /**
     * Create a custom Monolog instance.
     *
     * @param array $config
     * @return \Monolog\Logger
     */
    public function __invoke( array $config ): Logger {
        $handler = new MysqlDbHandler( $config, Logger::DEBUG );
        return new Logger( 'database', [ $handler ] );
    }

}
