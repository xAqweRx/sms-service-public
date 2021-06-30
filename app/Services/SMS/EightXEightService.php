<?php


namespace App\Services\SMS;


use App\Exceptions\InvalidArgumentException;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class EightXEightService implements SmsGatewayInterface {

    private const SMS_SUBACCOUNT_KEY = '{subAccountId}';
    private const SMS_URL = 'https://sms.8x8.com/api/v1/subaccounts/' . self::SMS_SUBACCOUNT_KEY . '/messages';

    private static $SUB_ACCOUNT_OTP;
    private static $SUB_ACCOUNT_NOTIFY;

    const KEY_PHONE = 'destination';
    const KEY_MESSAGE = 'text';
    const KEY_SENDER = 'source';

    /**
     * @var Client
     */
    private $httpClient;
    /**
     * @var mixed
     */
    private $name;
    /**
     * @var mixed
     */
    private $key;
    /**
     * @var mixed
     */
    private $sender;

    public static function init() {
        self::$SUB_ACCOUNT_OTP = env( '8x8_SUB_ACCOUNT_OTP', 'flow_otp' );
        self::$SUB_ACCOUNT_NOTIFY = env( '8x8_SUB_ACCOUNT_NOTIFY','flow_notif' );
    }
    public function __construct() {
        $this->name = $this->fetchParam( "8x8_NAME" );
        $this->key = $this->fetchParam( "8x8_KEY" );
        $this->sender = $this->fetchParam( "8x8_SENDER" );

        $this->httpClient = new Client();
    }

    /**
     *
     * curl -X POST https://sms.8x8.com/api/v1/subaccounts/{$subAccount}/messages
     *
     * -H "Authorization: Bearer 7ZzvxiuT77YIwlBgTSw0C1nIqG7f9q6yFx1MLqklfY"
     * -H "Content-Type: application/json"
     *
     * -d $'{ "source": "abcde", "destination": "+6512345678", "text": "Hello World!", "encoding": "AUTO" }
     *
     *
     * @param $subAccount
     * @param array $data
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendSms( $subAccount, array $data ) {

        if ( !env( 'SMS_ENABLED', false ) ) {
            return 'SMS Sending disabled';
        }

        $sendData = [
            self::KEY_PHONE => $data[ self::KEY_PHONE ],
            self::KEY_MESSAGE => $data[ self::KEY_MESSAGE ],
            self::KEY_SENDER => $this->sender,
        ];
        $this->logger()->info( "8x8 Request data", [
            'acc' => $subAccount,
            'data' => $data,
            'send' => $sendData
        ] );
        try {
            $response = $this->httpClient->post( $this->getUrl( $subAccount ), [
                'json' => $sendData,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->key,
                ]
            ] );
            $this->logger()->info( "8x8 response", [ 'data' => $response ] );
        } catch ( \Exception $exception ) {
            $this->logger()->info( "8x8 response", [ 'data' => $exception ] );
            throw $exception;
        }
        return $response->getBody()->getContents();;
    }

    private function getUrl( $subAccount ) {
        return str_replace(
            self::SMS_SUBACCOUNT_KEY,
            $subAccount,
            self::SMS_URL
        );
    }

    private function logger(): LoggerInterface {
        return \Log::channel( '8x8' );
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send( array $data ): string {
        return $this->sendSms( self::$SUB_ACCOUNT_NOTIFY, $data );
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendOtp( array $data ): string {
        return $this->sendSms( self::$SUB_ACCOUNT_OTP, $data );
    }

    public function getValidationRules() {
        return [
            self::KEY_PHONE => [ 'required', 'phone' ],
            self::KEY_MESSAGE => [ 'required', 'string' ]
        ];
    }

    public function getValidationRulesOtp() {
        return [
            self::KEY_PHONE => [ 'required', 'phone' ],
            self::KEY_MESSAGE => [ 'required', 'string' ]
        ];
    }

    /**
     * @param string $paramName
     * @return mixed
     * @throws InvalidArgumentException
     */
    private function fetchParam( string $paramName ) {
        if ( empty( env( $paramName ) ) ) throw new InvalidArgumentException( "ENV param $paramName not defined" );

        return env( $paramName );
    }
}

EightXEightService::init();
