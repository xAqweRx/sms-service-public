<?php

namespace App\Jobs;

use App\Models\contracts\EmailContract;
use App\Services\Email\EmailGatewayService;
use App\Services\Email\SMTPService;
use App\Services\SMS\SmsGatewayInterface;
use Illuminate\Support\Facades\Log;

class EmailJob extends Job {

    private $emailContract;

    public $queue = 'email';

    /**
     * Create a new job instance.
     *
     * @param EmailContract $emailContract
     */
    public function __construct( EmailContract $emailContract ) {
        $this->emailContract = $emailContract;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $smsGateWay = env( "EMAIL_GATEWAY" );
        /** @var EmailGatewayService $sender */
        $sender = new $smsGateWay;
        $result = $sender->send( $this->emailContract );
        Log::info( "Message send result", [ 'result' => $result ] );
    }

    public function fail( $exception = null ) {
        Log::error( $exception );
    }
}
