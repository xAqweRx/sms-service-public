<?php


namespace App\Services\SMS;


use App\Helpers\JsonHelper;
use Exception;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class TextLocalService implements SmsGatewayInterface {

    const TYPE = 'TextLocalService';
    const TEXT_LOCAL_URL = 'https://api.textlocal.in/send/?';

    const KEY_PHONE = 'phone';
    const KEY_MESSAGE = 'message';

    /** @var array */
    private $data;

    /**
     * @param array $data
     * @return string
     * @throws Exception
     * @throws GuzzleException
     */
    public static function send( array $data ): string {
        if ( !env( 'SMS_ENABLED', false ) ) {
            return 'SMS Sending disabled';
        }

        $instance = new TextLocalService();
        $instance->prepareData( $data[ self::KEY_PHONE ], $data[ self::KEY_MESSAGE ] );
        return $instance->sendToApi();
    }

    /*****************************************
     *          PRIVATE METHODS
     ****************************************/

    /**
     * @param string $number
     * @param string $message
     * @throws Exception
     */
    private function prepareData( string $number, string $message ) {
        $this->data = [
            'username' => $this->fetchParam( "TEXT_LOCAL_USERNAME" ),
            'hash'     => $this->fetchParam( "TEXT_LOCAL_API_HASH" ),
            'apiKey'   => $this->fetchParam( "TEXT_LOCAL_API_KEY" ),
            'sender'   => $this->fetchParam( "TEXT_LOCAL_SENDER" ),
            'numbers'  => $number,
            'message'  => $message,
            'test'     => env( "TEXT_LOCAL_TEST" )
        ];
    }

    /**
     * @throws Exception|GuzzleException
     */
    private function sendToApi() {
        Log::debug( 'Send request to TEXT LOCAL', [ 'data' => $this->data ] );

        $response = ( new Guzzle() )->post( self::TEXT_LOCAL_URL, [
            'form_params' => $this->data
        ] );
        $responseBody = $response->getBody()->getContents();
        $result = JsonHelper::decode( $responseBody );

        Log::debug( 'Text local response', [ 'response' => $result ] );

        if ( isset( $result[ 'errors' ] ) ) {
            throw new Exception( "Text local return errors: $responseBody" );
        }

        return $responseBody;
    }

    /**
     * @param string $paramName
     * @return mixed
     * @throws Exception
     */
    private function fetchParam( string $paramName ) {
        if ( empty( env( $paramName ) ) ) throw new Exception( "ENV param $paramName not defined" );

        return env( $paramName );
    }

    public function getValidationRules() {
        return [
            self::KEY_PHONE   => [ 'required', 'phone' ],
            self::KEY_MESSAGE => [ 'required', 'string' ]
        ];
    }

    public function getValidationRulesOtp() {
        return [
            self::KEY_PHONE   => [ 'required', 'phone' ],
            self::KEY_MESSAGE => [ 'required', 'string' ]
        ];
    }

    /**
     * @throws GuzzleException
     */
    public static function sendOtp( array $data ): string {
        return self::send( $data );
    }
}
