<?php


namespace App\Services\SMS;


interface SmsGatewayInterface {
    public static function send( array $data ): string;

    public static function sendOtp( array $data ): string;

    public function getValidationRules();

    public function getValidationRulesOtp();
}
