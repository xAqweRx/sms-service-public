<?php

namespace App\Jobs;

use App\Models\Messages\Message;
use App\Models\Strategy\Channel;
use App\Models\Strategy\QueueState;
use App\Services\Infobip\InfoBip;

class ZaloJob extends Job {

    public $timeout = 0;
    /**
     * @var InfoBip
     */
    private InfoBip $infoBip;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( InfoBip $infoBip ) {
        $this->infoBip = $infoBip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $zaloChannel = Channel::whereChannel( Channel::ZALO )->with( [ 'statuses' ] )->first();

        foreach ( $zaloChannel->statuses as $status ) {
            switch ( $status->queue_state ) {
                case QueueState::ST_NEW;
                    $statusNew = $status;
                    break;
                case QueueState::ST_DELIVERED;
                    $statusDelivered = $status;
                    break;
                case QueueState::ST_FAILED:
                    $statusFailed = $status;
                    break;
                case QueueState::ST_SENT;
                    $statusSent = $status;
                    break;
            }
        }

        $processed = [];
        $messages = $zaloChannel->messages()->where( [ 'id_state' => $statusNew->id_queue_state ] )->orderBy( 'priority' )->get();
       /* foreach ( $messages as $message ) {
             @var Message $message
            $this->infoBip->sendZalo( $message->id_message, $phone_no, $message->template->foreign_template_code, $message->message_text );
        }*/
    }
}
