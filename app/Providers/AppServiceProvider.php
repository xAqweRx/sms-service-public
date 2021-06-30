<?php

namespace App\Providers;

use App\Services\Email\SMTPService;
use App\Services\SMS\DbSMSGateway;
use App\Services\SMS\EightXEightService;
use App\Services\SMS\TextLocalService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

    public function boot() {

        Validator::extend(
            'phone',
            function ( $attribute, $value, $parameters, $validator ) {
                return preg_match( "/^[+]{0,1}[0-9]{7,14}$/", $value );
            },
            'Incorrect phone number'
        );

        if ( !class_exists( 'SMTPService' ) ) {
            class_alias( SMTPService::class, 'SMTPService' );
        }

        if ( !class_exists( 'EightXEightService' ) ) {
            class_alias( EightXEightService::class, 'EightXEightService' );
        }

        if ( !class_exists( 'DbSMSGateway' ) ) {
            class_alias( DbSMSGateway::class, 'DbSMSGateway' );
        }

        if ( !class_exists( 'TextLocalService' ) ) {
            class_alias( TextLocalService::class, 'TextLocalService' );
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }
}
