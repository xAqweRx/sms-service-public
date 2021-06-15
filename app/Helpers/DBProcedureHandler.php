<?php


namespace App\Helpers;

use App\Constants\ErrorCodes;
use App\Constants\HttpCodes;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DBProcedureHandler {

    private static $cnt = 0;

    /**
     * @param string                              $procedure
     * @param array | string                      $data
     * @param ConnectionInterface | string | null $connection
     * @param \Closure $closure
     * @return \stdClass
     * @throws \Exception
     */
    public static function send( string $procedure, $data = null, $connection = null, \Closure $closure = null ) {
        self::$cnt = 0;
        self::logInfo('info', $procedure, 'started');
        $DB = $connection instanceof ConnectionInterface ? $connection : self::getDBConnectionByName($connection);
        try {
            $DB->beginTransaction();

            self::processRequest($data, $procedure, $DB);

            $result = $DB->selectOne(" SELECT @_out_json as out_json;");


            self::logInfo('info', $procedure, $result);

            if ( isset($result->out_json) ) {
                if ( $outData = json_decode($result->out_json) ) {
                    if ( isset($outData->error) ) {
                        throw new \Exception($outData->error->error_message, HttpCodes::UNPROCESSABLE_ENTITY);
                    }
                } else {
                    throw new \Exception(ErrorCodes::PROCEDURE_FAILED, HttpCodes::UNPROCESSABLE_ENTITY);
                }
            } else {
                throw new \Exception(ErrorCodes::PROCEDURE_FAILED, HttpCodes::UNPROCESSABLE_ENTITY);
            }

            if ( $closure ) {
                $result = $closure($result);
            }

            $DB->commit();
            self::logInfo('info', $procedure, $result);
            return $result;

        } catch ( \Exception $exception ) {
            $DB->rollBack();
            throw $exception;

        }
    }

    /**
     * get DB connection
     *
     * @param $name
     * @return ConnectionInterface
     */
    protected static function getDBConnectionByName( $name = null ): ConnectionInterface {
        return $name === ( 'default' || null ) ? DB::connection() : DB::connection($name);
    }

    /**
     * @param string $string
     * @return bool|mixed
     * @throws \Exception
     */
    public static function is_json( string $string ) {
        json_decode($string);
        if ( json_last_error() != JSON_ERROR_NONE ) {
            throw new \Exception(ErrorCodes::INCORRECT_TYPE);
        }
        return true;
    }

    private static function logInfo( $level, $procedure, $result ) {
        /** @var Request $request */
        $request = app('request');
        $message = sprintf(
            " [%s] Request on DB procedure %s.",
            ( new \DateTime() )->format("Y-m-d H:i:s"),
            $procedure
        );

        \Log::$level(
            $message,
            [
                "user" => \Auth::user() ? \Auth::user()->id_user : null,
                "path" => $request->path(),
                'method' => $request->method(),
                'result' => $result,
            ]
        );
    }

    private static function processRequest( $data, $procedure, $DB ) {
        try {

            if ( $data ) {
                if ( gettype($data) === "array" ) {
                    $data = json_encode($data);
                } elseif ( $data instanceof Arrayable || ( gettype($data) === "object" && method_exists(
                            $data,
                            "toArray"
                        ) ) ) {
                    $data = json_encode($data->toArray());
                } elseif ( gettype($data) != "string" ) {
                    throw new \Exception(ErrorCodes::INCORRECT_TYPE);
                }

                self::is_json($data);
                self::logInfo('info', $procedure, $data);
                $DB->statement("CALL ${procedure} (?, @_out_json);", [ $data ]);
            } else {
                self::logInfo('info', $procedure, []);
                $DB->statement("CALL ${procedure} (@_out_json);");
            }
        } catch ( QueryException $ex ) {
            if ( self::$cnt < 3 && preg_match("/\[40001\]/i", $ex->getMessage()) ) {
                self::$cnt++;
                self::processRequest($data, $procedure, $DB);
            } else {
                throw $ex;
            }

        }
    }
}
