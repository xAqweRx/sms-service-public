<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group( [ 'prefix' => "v1", 'namespace' => 'v1' ], function () use ( $router ) {

    $router->get( '/', function () use ( $router ) {
        return "SMS Micro service - " . $router->app->version();
    } );

    $router->group( [
        'prefix' => 'sms',
    ], function () use ( $router ) {
        $router->post( 'otp', 'SMSController@otp' );
        $router->post( '', 'SMSController@queue' );
    } );

    $router->group( [
        'prefix' => 'email',
    ], function () use ( $router ) {
        $router->post( '', 'EmailController@queue' );
    } );
} );
