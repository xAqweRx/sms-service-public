<?php


namespace App\Services\SMS;

use App\Models\Messages\SMS;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DbSMSGateway implements SmsGatewayInterface {

    const TYPE = 'DbSMSGateway';

    const KEY_ID_SMS = 'id_sms';
    const KEY_PHONE = 'phone';
    const KEY_MESSAGE = 'message';


    /** signature ( id_sms ); */
    private const DB_FUNCTION = 'f_get_sms2';
    private const DB_FUNCTION_OTP = 'f_get_sms2otp';

    /**
     * @param $data
     * @return string
     * @throws GuzzleException
     */
    public static function send( array $data ): string {
        if ( !env( 'SMS_ENABLED', false ) ) {
            return 'SMS Sending disabled';
        }
        Log::debug( 'Initiate sending sms via DbSMSGateway', [ 'data' => $data ] );
        $url = DB::selectOne( " SELECT " . self::DB_FUNCTION . "(?) as url", [ $data[ self::KEY_ID_SMS ] ] );
        Log::debug( 'Send request to DbSMSGateway', [ 'url' => $url->url ] );
        $response = ( new Guzzle() )->get( $url->url );
        $body = $response->getBody()->getContents();
        Log::debug( 'DbSMSGateway response', [ 'body' => $body ] );
        return $body;
    }

    public function getValidationRules() {
        return [
            self::KEY_ID_SMS => [ 'required', 'numeric', Rule::exists( SMS::TABLE_NAME, SMS::PRIMARY_KEY ) ],
        ];

    }

    public function getValidationRulesOtp() {
        return [
            self::KEY_PHONE   => [ 'required', 'phone' ],
            self::KEY_MESSAGE => [ 'required', 'string' ]
        ];
    }

    public static function sendOtp( array $data ): string {
        $number = $data[ self::KEY_PHONE ];
        $message = $data[ self::KEY_MESSAGE ];

        Log::debug('Initiate sending OTP via DbSMSGateway', ['phone' => $number, 'message' => $message]);
        $url = DB::selectOne(" SELECT ".self::DB_FUNCTION_OTP."(?,?) as url", [ $number, $message ]);
        Log::debug( 'Send request to DbSMSGateway', [ 'url' => $url->url ] );
        $response = ( new Guzzle() )->get($url->url);
        $body = $response->getBody()->getContents();
        Log::debug( 'DbSMSGateway response', [ 'body' => $body ] );
        return $body;
    }
}
