<?php


namespace v1;


use App\Models\Messages\SMS;
use App\Services\SMS\DbSMSGateway;
use App\Services\SMS\TextLocalService;

class SMSTest extends \TestCase {

    private $faker;

    protected function setUp(): void {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    public function testSend() {
        $smsGateWay = env( 'SMS_GATEWAY' );
        switch ( $smsGateWay ) {
            case DbSMSGateway::TYPE:
                $sms = SMS::inRandomOrder()->first();
                $this->post( '/v1/sms',
                    [
                        'id_sms' => $sms->id_sms,
                    ] );
                break;
            case TextLocalService::TYPE:
                $this->post( '/v1/sms',
                    [
                        'phone'   => $this->faker->e164PhoneNumber,
                        'message' => $this->faker->text
                    ] );
                break;
        }

        $this->assertResponseOk();
    }

}
