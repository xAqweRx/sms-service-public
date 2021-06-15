<?php


namespace App\Http\Controllers\base;


use App\Http\Controllers\Controller;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Jobs\EmailJob;
use App\Mail\GenericEmail;
use App\Models\contracts\EmailContract;
use App\Services\Email\EmailGatewayService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmailController extends Controller {

    public function queue( Request $request ) {
        try {
            $emailGateWay = env( "EMAIL_GATEWAY" );
            /** @var $sender EmailGatewayService */
            $sender = new $emailGateWay;

            \Log::info( $sender->getValidationRules(), $request->all() );
            $data = $this->validate( $request, $sender->getValidationRules(), $sender->getValidationMessages() );

            $queued = $this->dispatch( ( new EmailJob( new EmailContract ( $data )  ) )->onQueue( $data['queue'] ) );
            return ( new SuccessResponse( $queued ) )->response();
        } catch ( \Throwable $throwable ) {
            if ( $throwable instanceof ValidationException ) {
                \Log::info( $throwable->errors() );
            }
            return ErrorResponse::withThrowable( $throwable )->response();
        }
    }
}
