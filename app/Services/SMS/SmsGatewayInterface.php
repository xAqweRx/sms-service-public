<?php


namespace App\Services\SMS;


interface SmsGatewayInterface {
    public function send( array $data ): string;

    public function sendOtp( array $data ): string;

    public function getValidationRules();

    public function getValidationRulesOtp();
}
