<?php


namespace App\Models\User;


use App\Constants\ErrorCodes;
use App\Exceptions\InvalidArgumentException;
use App\Services\RedisService\UserRedisDataService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class LoginState
 *
 * @package App\Models\User
 * @property int $id
 * @property int|null $id_logged_user
 * @property string|null $token
 * @property string|null $ip
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $update_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $expire_at
 * @property string|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\LoginState onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState whereExpireAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState whereIdLoggedUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState whereUpdateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\LoginState whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\LoginState withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\LoginState withoutTrashed()
 * @mixin \Eloquent
 */
class LoginState extends Model {

    use SoftDeletes;

    const CREATED_AT = "created_at";
    const UPDATED_AT = "update_at";

    const TYPE_ACCESS_TOKEN = 'ACCESS';
    const TYPE_SECRET_TOKEN = 'SECRET';

    protected static $allowedTypes = [
        self::TYPE_ACCESS_TOKEN,
        self::TYPE_SECRET_TOKEN,
    ];

    protected static $pKey = "id";
    protected static $tableName = "sa_login_state";

    protected $dates = [ 'deleted_at' ];

    protected $attributes = [
        'type' => self::TYPE_ACCESS_TOKEN
    ];

    public function __construct( array $attributes = [] ) {
        $this->primaryKey = self::$pKey;
        $this->table = self::$tableName;
        parent::__construct( $attributes );
    }

    public static function getTableName() {
        return self::$tableName;
    }

    /**
     * @return array
     */
    public static function getAllowedTypes(): array {
        return self::$allowedTypes;
    }

    protected static function boot() {
        parent::boot();
        self::creating( function ( $model ) {
            if ( app( 'auth' )->user() != null ) {
                $model->created_by = app( 'auth' )->user()->id_user;
            }
        } );
        self::updating( function ( $model ) {
            if ( app( 'auth' )->user() != null ) {
                $model->updated_by = app( 'auth' )->user()->id_user;
            }
        } );
        self::deleting( function ( $model ) {
            if ( app( 'auth' )->user() != null ) {
                $model->updated_by = app( 'auth' )->user()->id_user;
            }
        } );
    }

    public static function revokeToken( $userId, $invalidate = true ) {
        \Log::debug( "Revoke token with ivalidation ", [ \Auth::user() ] );
        $loginData = LoginState::whereIdLoggedUser( $userId )
                               ->whereType( LoginState::TYPE_ACCESS_TOKEN )
                               ->where( "expire_at", ">", time() );
        \Log::debug( "SQL", [ $loginData->toSql() ] );
        $loginData = $loginData->get();

        \Log::debug( "Tokens ", [ \Auth::user(), $loginData ] );
        $result = true;
        if ( count( $loginData ) ) {
            $currentToken = JWTAuth::getToken();
            foreach ( $loginData as $data ) {
                $result = $data->delete();
                if ( $invalidate ) {
                    try {
                        JWTAuth::setToken( $data->token )->invalidate();
                    } catch ( \Exception $exception ) {
                        \Log::debug( "Revoke token failed", [ "exception" => $exception, "user" => $userId ] );
                    }
                }
            }
            if ( $currentToken ) {
                JWTAuth::setToken( $currentToken );
            }
        }
        return $result;
    }

    public static function refreshToken( $userId ) {

        \Log::debug( "Start refresh Token", [ \Auth::user() ] );
        $newToken = JWTAuth::refresh();
        JWTAuth::setToken( $newToken );

        \Log::debug( "Refreshed Token", [ \Auth::user() ] );
        LoginState::revokeToken( $userId, false );

        \Log::debug( "Add token to redis", [ \Auth::user() ] );
        UserRedisDataService::setUserDataField( $userId, "token", $newToken );

        $result = self::saveLoginData(
            $newToken,
            $userId
        );

        \Log::debug( "New token data saved : ", [ \Auth::user(), $result ] );

        return $newToken;
    }

    public static function saveLoginData( string $access_token, int $id_user, $type = null ) {
        $ip = array_reverse( Request::getClientIps() )[ 0 ];

        $loginState = new LoginState();
        if ( !empty( $type ) ) {
            if ( !in_array( $type, LoginState::getAllowedTypes() ) )
                throw new InvalidArgumentException( ErrorCodes::INCORRECT_PARAMETERS );
            else
                $loginState->type = $type;
        }

        $loginState->id_logged_user = $id_user;
        $loginState->ip = $ip;
        $loginState->token = $access_token;

        $oldToken = JWTAuth::getToken();
        $loginState->expire_at = JWTAuth::setToken( $access_token )->getPayload()->get( "exp" );
        if ( $oldToken ) {
            JWTAuth::setToken( $oldToken );
        }

        $loginState->save();
    }

    public static function getActiveTokensForUser( $id_user ) {
        $loggedIn = null;
        $tokens = self::whereIdLoggedUser( $id_user )
                      ->whereType( LoginState::TYPE_ACCESS_TOKEN )
                      ->where( 'expire_at', '>', time() )
                      ->get();
        $oldToken = JWTAuth::getToken();
        $tokens->each( function ( $item, $key ) use ( &$loggedIn ) {
            /**  @var $item LoginState */
            if ( JWTAuth::setToken( $item->token )->check() ) {
                $loggedIn = $item;
            }
        } );
        if ( !empty( $oldToken ) ) {
            JWTAuth::setToken( $oldToken );
        }

        return $loggedIn;
    }

    public static function verifyLoginSecret( $userId, $secret ) {
        return self::whereIdLoggedUser( $userId )
                   ->whereType( self::TYPE_SECRET_TOKEN )
                   ->whereToken( $secret )
                   ->where( 'expire_at', '>', time() )
                   ->exists();
    }

    public static function validateToken(string $token) {
        return JWTAuth::setToken($token)->check();
    }
}
