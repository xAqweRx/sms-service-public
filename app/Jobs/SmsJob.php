<?php

namespace App\Jobs;

use App\Services\SMS\SmsGatewayInterface;
use Illuminate\Support\Facades\Log;

class SmsJob extends Job {

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
        $smsGateWay = env( "SMS_GATEWAY" );
        /** @var SmsGatewayInterface $sender */
        $sender = new $smsGateWay;

        $result = ( $this->data[ 'otp' ] ?? false ) ? $sender->sendOtp( $this->data ) : $sender->send( $this->data ) ;
        Log::info( "Message send result", [ 'result' => $result ] );
    }

    public function fail( $exception = null ) {
        Log::error( $exception );
    }
}
