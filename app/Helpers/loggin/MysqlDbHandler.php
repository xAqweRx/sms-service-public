<?php


namespace App\Helpers\loggin;


use App\Models\User\User;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class MysqlDbHandler extends AbstractProcessingHandler {

    const LOG_LEVEL_NONE = 'none';
    const LOG_LEVEL_LOG = 'log';
    const LOG_LEVEL_TRACE = 'trace';


    protected $table;
    protected $connection;

    public function __construct( array $config, int $level = Logger::DEBUG, bool $bubble = true ) {
        $this->connection = $config[ 'connection' ];
        parent::__construct($level, $bubble);
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param array $record
     *
     * @return void
     */
    protected function write( array $record ): void {
        if ( $this->shouldBeLogged($record) ) {
            try {
                /** @var User $user */
                $user = app('auth')->user();
                $connection = config('database.connections.'.\DB::getDefaultConnection())[ 'database' ];

                /**
                 * Stored procedure params
                 * 0  => id_user int,
                 * 1  => id_lead int,
                 * 2  => id_deal bigint,
                 * 3  => ip_user varchar(15),
                 * 4  => db_name varchar(20),
                 * 5  => level varchar(50),
                 * 6  => env_name_server varchar,
                 * 7  => log_source varchar(100),
                 * 8  => log_text text,
                 * 9  => log_params longtext,
                 * 10 => log_trace longtext
                 */
                $insertData = [];
                $insertData[] = ( $user != null ? $user->id_user : null );
                $insertData[] = ( !empty($record[ "context" ][ "id_lead" ]) ? $record[ "context" ][ "id_lead" ] : null );
                $insertData[] = ( !empty($record[ "context" ][ "id_deal" ]) ? $record[ "context" ][ "id_deal" ] : null );
                $insertData[] = ( app('request') )->getClientIp();
                $insertData[] = ( !empty($record[ "context" ][ "db" ]) ? $record[ "context" ][ "db" ] : $connection );
                $insertData[] = mb_strtolower($record[ 'level_name' ]);
                $insertData[] = mb_strtolower(env("APP_ENV", "test"));
                $insertData[] = ( !empty($record[ "context" ][ "channel" ]) ? $record[ "context" ][ "channel" ] : $record[ "channel" ] );

                if ( !empty($record[ "context" ][ "exception" ]) && $record[ "context" ][ "exception" ] instanceof \Throwable ) {
                    /** @var \Throwable $exception */
                    $exception = $record[ "context" ][ "exception" ];
                    /** @var \DateTime $dateTime */
                    $dateTime = $record[ "datetime" ];
                    $insertData[] = sprintf(
                        "[%s] Exception ( code: %s ): %s in %s:%s",
                        $dateTime->format("Y-m-d H:i:s"),
                        $exception->getCode(),
                        $exception->getMessage(),
                        $exception->getFile(),
                        $exception->getLine()
                    );
                    $insertData[] = !empty($record[ 'context' ][ 'params' ]) ? json_encode(
                        $record[ 'context' ][ 'params' ]
                    ) : null;
                    $insertData[] = $exception->getTraceAsString();
                } else {
                    $insertData[] = $record[ "message" ];
                    $insertData[] = json_encode($record[ "context" ]);
                    $insertData[] = null;
                }

                if ( \DB::connection($this->connection)->getDatabaseName() ) {
                    \DB::connection($this->connection)->statement(
                        " CALL sp_system_log( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ); ",
                        $insertData
                    );
                }
            } catch ( \Exception $loggerException ) {
                Log::channel('daily')->error("DB logger failed", [ 'caused' => $loggerException ]);
                Log::channel('slack')->error("DB logger failed", [ 'caused' => $loggerException ]);
            }
        }
    }

    private function shouldBeLogged( $record ) {
        $logLevel = env("DB_LOG_LEVEL", self::LOG_LEVEL_NONE);
        return $logLevel != self::LOG_LEVEL_NONE && ( $logLevel == self::LOG_LEVEL_TRACE || $record[ "level" ] > Logger::DEBUG ) && empty($record[ 'context' ][ "ignore" ]);
    }
}
