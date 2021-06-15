<?php
/**
 * Date: 3/7/2019
 * Time: 10:20:39 AM
 */

namespace App\Http\Responses;

use App\Models\Logging\SystemLog;
use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SuccessResponse {
    private $data;
    private $preparedJson;

    /**
     * SuccessResponse constructor.
     * @param $data
     * @param bool $preparedJson
     */
    public function __construct( $data = [], $preparedJson = false ) {
        $this->data = $data;
        $this->preparedJson = $preparedJson;
    }

    function response() {
        $this->logInfo();
        if ( $this->preparedJson ) {
            return response( '{"success": true, "data": ' . $this->data . '}' )->header( 'Content-Type', 'application/json' );
        }

        if ( $this->data instanceof Collection ) {
            return response()->json( [ "success" => true, "data" => [ "items" => $this->data ] ] );
        } else {
            return response()->json( [ "success" => true, "data" => $this->data ] );
        }
    }

    private function logInfo() {
        /** @var Request $request */
        $request = app( 'request' );
        $message = sprintf( " [%s] Request on (%s) %s : successful ",
            ( new \DateTime() )->format( "Y-m-d H:i:s" ),
            $request->method(),
            $request->path()
        );
        \Log::info( $message, [
            "user" => \Auth::user() ? \Auth::user()->id_user : null,
            "params" => $request->except( SystemLog::EXCEPTION_FIELDS )
        ] );
    }
}
