<?php


namespace App\Services\Infobip;


use GuzzleHttp\Client;

class InfoBip {

    /**
     * @var Client
     */
    private Client $guzzle;
    /**
     * @var mixed
     */
    private $domain;
    /**
     * @var mixed
     */
    private $authToken;
    /**
     * @var mixed
     */
    private $from;

    public function __construct() {
        $this->guzzle = new Client();
        $this->domain = env( 'INFOBIP_DOMAIN' );
        $this->authToken = env( 'INFOBIP_TOKEN' );
        $this->from = env( 'INFOBIP_FROM' );
    }

    public function sendZalo( $idQueueItem, $to, $templateCode, $templateData ) {
        $data = [
            'from' => $this->from,
            'to' => $to,
            "content" => [
                "templateCode" => $templateCode,
                "templateData" => json_decode($templateData),
                "type" => "TEMPLATE"
            ],
            "callbackData" => $idQueueItem
        ];
        $this->guzzle->post( 'ott/zalo/1/message', [
            'json' => $data,
            'headers' => [
                'Authorization' => 'App ' . $this->authToken
            ]
        ] );
    }
}
