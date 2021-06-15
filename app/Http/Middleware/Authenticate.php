<?php

namespace App\Http\Middleware;

use App\Constants\ErrorCodes;
use App\Constants\HttpCodes;
use App\Http\Responses\ErrorResponse;
use App\Models\User\User;
use App\Models\User\UserRepository;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;


class Authenticate {

    const CRITICAL_EXP_TIME = 20;
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;
    /**
     * @var JWTAuth
     */
    private $jwt;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Factory $auth
     * @param JWTAuth $JWTAuth
     * @param UserRepository $userRepository
     */
    public function __construct( Auth $auth, JWTAuth $JWTAuth, UserRepository $userRepository ) {
        $this->auth = $auth;
        $this->jwt = $JWTAuth;
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle( $request, Closure $next, $guard = null ) {
        if ( $this->auth->guard( $guard )->guest() ) {
            return ErrorResponse::withData( ErrorCodes::ACCESS_TOKEN_EXPIRED, HttpCodes::UNAUTHORIZED )->response();
        }

        return $next( $request );
    }
}
