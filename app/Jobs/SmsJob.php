<?php

namespace App\Jobs;

use App\Services\SMS\SmsGatewayInterface;
use Illuminate\Support\Facades\Log;

class SmsJob extends Job {

    public const SMS_VENDOR = 'vendor';
    public const DB_FUNCTION_NORMALIZE = 'f_convert_num';
    private $data;

    /**
     * Create a new job instance.
     *
     * @param $data
     */
    public function __construct( $data ) {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        if ( !empty( $this->data[ self::SMS_VENDOR ] ) ) {
            $smsGateWay = $this->data[ self::SMS_VENDOR ];
        } else {
            $smsGateWay = env( "SMS_GATEWAY" );
        }
        /** @var SmsGatewayInterface $sender */
        $sender = new $smsGateWay;

        $this->data[$sender::KEY_PHONE] = $this->normalizePhone($this->data[$sender::KEY_PHONE]);
        $result = ( $this->data[ 'otp' ] ?? false ) ? $sender->sendOtp( $this->data ) : $sender->send( $this->data ) ;
        Log::info( "Message send result", [ 'result' => $result ] );
    }

    public function fail( $exception = null ) {
        Log::error( $exception );
    }

    private function normalizePhone($phone){
        if($phone[0] == '0'){
            $phone = substr( $phone, 1, strlen( $phone ) );
        }
        $result = \DB::selectOne( ' SELECT ' . self::DB_FUNCTION_NORMALIZE . " ( ? , ? ) as phone;", [ $phone, 'db' ] );
        return $result->phone;
    }

}
