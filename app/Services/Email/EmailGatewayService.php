<?php


namespace App\Services\Email;


use App\Models\contracts\EmailContract;

interface EmailGatewayService {
    public function send(EmailContract  $emailContract);

    public function getValidationRules();

    public function getValidationMessages();
}
