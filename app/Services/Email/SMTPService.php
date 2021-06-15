<?php


namespace App\Services\Email;


use App\Constants\ErrorCodes;
use App\Mail\GenericEmail;
use App\Models\contracts\EmailContract;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class SMTPService implements EmailGatewayService {


    public function getValidationRules() {
        return [
            'queue' => [ 'required', Rule::in( [ 'email_high', 'email_low' ] ) ],
            'to' => [ 'required', 'array' ],
            'to.*' => [ 'email' ],
            'subject' => [ 'required', 'string' ],
            'body' => [ 'required', 'string' ],
            'attachments' => [ 'nullable', 'array' ],
            'attachments.*.name' => [ 'string' ],
            'attachments.*.data' => [ 'string' ]
        ];
    }

    public function getValidationMessages() {
        return [
            'string' => ErrorCodes::INCORRECT_VALUE,
            'required' => ErrorCodes::REQUIRED_FIELD,
            'email' => ErrorCodes::INCORRECT_TYPE,
            'array' => ErrorCodes::INCORRECT_TYPE,
        ];
    }

    public function send( EmailContract $emailContract ) {
        return  Mail::to( $emailContract->getTo() )->send( new GenericEmail( $emailContract ) );
    }
}
