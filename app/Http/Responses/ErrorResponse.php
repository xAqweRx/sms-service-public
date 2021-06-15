<?php
/**
 * Date: 3/7/2019
 * Time: 10:20:46 AM
 */

namespace App\Http\Responses;


use App\Constants\ErrorCodes;
use App\Constants\HttpCodes;
use App\Exceptions\InvalidStateException;
use App\Models\Logging\SystemLog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

/**
 * Class ErrorResponse
 * @package App\Http\Responses
 *
 * Form new Throwable such way
 * message - error code from API
 * code - http status response for correct work of API
 *
 */
class ErrorResponse {


    /**
     * @var \Throwable
     */
    private $throwable;

    private $error;
    private $code;
    private $data;

    private function __construct() {
    }

    public static function withThrowable( \Throwable $e ): ErrorResponse {
        $self = new self();
        $self->throwable = $e;
        return $self;
    }

    public static function withData( $error, int $code, $data = null ): ErrorResponse {
        $self = new self();
        $self->error = $error;
        $self->code = $code;
        $self->data = $data;
        return $self;
    }


    public function response() {
        if ( $this->throwable ) {

            $error = null;
            $code = null;
            $data = [];

            if ( $this->throwable instanceof UnauthorizedException ) {
                $error = [ $this->throwable->getMessage() ];
                $code = HttpCodes::FORBIDDEN;
                $level = 'warning';
            } else if ( $this->throwable instanceof ModelNotFoundException ) {
                $message = ($this->throwable->getMessage() && $this->throwable->getMessage() != '') ? $this->throwable->getMessage() : ErrorCodes::ENTITY_NOT_FOUND;
                $error = [ $message ];
                $code = HttpCodes::NOT_FOUND;
                $level = 'warning';
            } else if ( $this->throwable instanceof InvalidStateException ) {
                $error = [ $this->throwable->getMessage() ];
                $code = $this->throwable->getCode();
                $level = 'warning';
            } else if ( $this->throwable instanceof ValidationException ) {
                $error = $this->throwable->errors();
                $code = HttpCodes::BAD_REQUEST;
                $level = 'warning';

            } else {

                $message = json_decode( $this->throwable->getMessage() );
                $error = $message ?? [$this->throwable->getMessage()];

                if ( env( "APP_DEBUG" ) ) {
                    $data[ "message" ] = $this->throwable->getMessage() . " " . $this->throwable->getFile() . "( " . $this->throwable->getLine() . " ) " . $this->throwable->getTraceAsString();
                }
                $code = HttpCodes::SERVER_ERROR;
                if ( $this->throwable->getCode() && $this->throwable->getCode() < 600 && $this->throwable->getCode() >= 400 ) {
                    $code = $this->throwable->getCode();
                }
                $level = $code >= 500 ? 'error' : 'warning';
            }
            $this->logInfo( $level );
            return response()->json( [ 'success' => false, 'error' => $error, "data" => $data ], $code );
        } else {
            $error = is_string( $this->error ) ? [ $this->error ] : $this->error;
            $this->logInfo( 'warning' );
            return response()->json( [ "success" => false, "error" => $error, "data" => $this->data ], $this->code );
        }
    }

    private function logInfo( $level ) {
        /** @var Request $request */
        $request = app( 'request' );
        $message = sprintf( " [%s] Request on (%s) %s : failed  - code : %s; message - %s; user - %s",
            ( new \DateTime() )->format( "Y-m-d H:i:s" ),
            $request->method(),
            $request->path(),
            ( $this->throwable ? $this->throwable->getCode() : $this->code ),
            ( $this->throwable ? $this->throwable->getMessage() : json_encode( $this->data ) ),
            ( \Auth::user() ? \Auth::user()->id_user : "no User" )
        );

        \Log::$level( $message, [
            "user"            => \Auth::user() ? \Auth::user()->id_user : null,
            "params"          => $request->except( SystemLog::EXCEPTION_FIELDS ),
            'exception'       => $this->throwable,
            "data"            => $this->data,
            "error"           => $this->error,
            "code"            => $this->code,
            "request_headers" => $request->headers
        ] );

    }

}
