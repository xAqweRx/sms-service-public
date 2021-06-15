<?php

namespace App\Mail;

use App\Models\contracts\EmailContract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericEmail extends Mailable {
    use Queueable, SerializesModels;

    /**
     * @var EmailContract
     */
    public $emailContract;

    /**
     * Create a new message instance.
     * @param EmailContract $emailContract
     */
    public function __construct( EmailContract $emailContract ) {
        $this->emailContract = $emailContract;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $build = $this->view( 'emails.generic' )->subject( $this->emailContract->getSubject() );
        if ( !empty( $this->emailContract->getAttachment() ) ) {
            foreach ( $this->emailContract->getAttachment() as $attachment ) {
                if ( filter_var( $attachment->getData(), FILTER_VALIDATE_URL ) ) {
                    $build = $build->attachData( file_get_contents( $attachment->getData() ), $attachment->getName() );
                } else {
                    $build = $build->attachData( $attachment->getData(), $attachment->getName() );
                }
            }
        }
        return $build;
    }
}
