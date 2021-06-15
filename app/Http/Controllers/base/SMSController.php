<?php


namespace App\Http\Controllers\base;


use App\Constants\ErrorCodes;
use App\Http\Controllers\Controller;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Jobs\SmsJob;
use App\Services\SMS\SmsGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SMSController extends Controller {

    public function queue( Request $request ) {
        try {
            $smsGateWay = env( "SMS_GATEWAY" );
            /** @var $sender SmsGatewayInterface */
            $sender = new $smsGateWay;

            \Log::info( $sender->getValidationRules(), $request->all() );
            $data = $this->validate( $request, $sender->getValidationRules(), [
                'required' => ErrorCodes::REQUIRED_FIELD,
                'phone'    => ErrorCodes::INCORRECT_TYPE,
                'string'   => ErrorCodes::INCORRECT_TYPE,
                'numeric'  => ErrorCodes::INCORRECT_TYPE,
                'exists'   => ErrorCodes::ITEM_NOT_EXIST,
            ] );
            $data['otp'] = false;

            $this->dispatch( ( new SmsJob( $data ) )->onQueue( 'sms' ) );
            return ( new SuccessResponse() )->response();
        } catch ( \Throwable $throwable ) {
            if ( $throwable instanceof ValidationException ) {
                \Log::info( $throwable->errors() );
            }
            return ErrorResponse::withThrowable( $throwable )->response();
        }
    }

    public function otp(Request  $request){
        try {
            $smsGateWay = env( "SMS_GATEWAY" );
            /** @var $sender SmsGatewayInterface */
            $sender = new $smsGateWay;

            \Log::info( $sender->getValidationRulesOtp(), $request->all() );
            $data = $this->validate( $request, $sender->getValidationRulesOtp(), [
                'required' => ErrorCodes::REQUIRED_FIELD,
                'phone'    => ErrorCodes::INCORRECT_TYPE,
                'string'   => ErrorCodes::INCORRECT_TYPE,
                'numeric'  => ErrorCodes::INCORRECT_TYPE,
                'exists'   => ErrorCodes::ITEM_NOT_EXIST,
            ] );

            $data['otp'] = true;
            $this->dispatch( ( new SmsJob( $data ) )->onQueue( 'sms' ) );
            return ( new SuccessResponse() )->response();
        } catch ( \Throwable $throwable ) {
            if ( $throwable instanceof ValidationException ) {
                \Log::info( $throwable->errors() );
            }
            return ErrorResponse::withThrowable( $throwable )->response();
        }
    }
}
