<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 10/26/2018
 * Time: 4:24:11 PM
 */

namespace App\Http\Middleware;

use App\Constants\HttpCodes;
use App\Http\Responses\ErrorResponse;
use Illuminate\Contracts\Auth\Factory as Auth;

use App\Constants\AppHeader;
use App\Constants\ErrorCodes;
use Closure;

class CompanyHashMiddleware {


    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * CompanyHashMiddleware constructor.
     * @param Auth $auth
     */
    public function __construct( Auth $auth ) {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle( $request, Closure $next, $guard = null ) {
        if ( !$request->hasHeader( AppHeader::COMPANY_HASH ) && !$request->query( strtolower( AppHeader::COMPANY_HASH ) ) ) {
            return ErrorResponse::withData(
                [ ErrorCodes::NO_COMPANY_HASH_PROVIDED ]
                , HttpCodes::BAD_REQUEST )->response();
        } else {
            $cmpHash = $request->header( AppHeader::COMPANY_HASH ) ?
                $request->header( AppHeader::COMPANY_HASH ) :
                $request->query( strtolower( AppHeader::COMPANY_HASH ) );
            if (
                $this->auth->guard( $guard )->guest() ||
                $this->auth->guard( $guard )->user()->company()->first()->cmp_hash != $cmpHash
            ) {

                return ErrorResponse::withData( [ ErrorCodes::BAD_COMPANY_HASH_PROVIDED ], HttpCodes::FORBIDDEN )->response();
            }
        }
        return $next( $request );
    }

}
