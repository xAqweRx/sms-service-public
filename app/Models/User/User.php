<?php

namespace App\Models\User;

use App\Models\AModel;
use App\Models\Company\Company;
use App\Models\Permission\Permission;
use App\Models\Permission\PermissionItem;
use App\Models\Permission\PermissionItemAdditional;
use App\Models\Permission\Role;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\User\User
 *
 * @property int $id_user
 * @property int $test_flag
 * @property int $id_team
 * @property string $login
 * @property string $pswd
 * @property int|null $role
 * @property string $full_name
 * @property string $gender
 * @property string|null $birthday
 * @property string|null $identify_number
 * @property string|null $email
 * @property string $is_active
 * @property string $begin_date
 * @property string $end_date
 * @property string $activate_date
 * @property string $deactivate_date
 * @property string|null $vd_group_name
 * @property string|null $skills
 * @property \Illuminate\Support\Carbon $created
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $last_upd
 * @property int $last_upd_by
 * @property string|null $vicidial_login
 * @property string|null $vicidial_pswd
 * @property string|null $phone_login
 * @property string|null $phone_password
 * @property int|null $id_cmp
 * @property bool|null $is_owner
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $status
 * @property-read \App\Models\Company\Company|null $company
 * @property-read \App\Models\User\User $creator
 * @property-read \App\Models\User\LoginState|null $loginState
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereActivateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereBeginDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereDeactivateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereIdCmp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereIdTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereIdentifyNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereIsOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereLastUpd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereLastUpdBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User wherePhoneLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User wherePhonePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User wherePswd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereSkills($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereTestFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereVdGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereVicidialLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereVicidialPswd($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends AModel implements AuthenticatableContract, AuthorizableContract, JWTSubject {

    use Authenticatable, Authorizable, SoftDeletes;

    const TABLE_NAME = 'sa_users';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'last_upd';

    protected static $fields = [
        "id_user",
        "id_team",
        "id_cmp",
        "login",
        "pswd",
        "phone",
        "full_name",
        "gender",
        "birthday",
        "identify_number",
        "email",
        "is_active",
        "begin_date",
        "end_date",
        "activate_date",
        "deactivate_date",
        "vd_group_name",
        "skills",
        "vicidial_login",
        "vicidial_pswd",
        "phone_login",
        "phone_password",
        "is_owner",
        "status",
    ];

    protected static $tableName = self::TABLE_NAME;
    protected static $pKey = "id_user";

    protected $hidden = [
        'pswd',
        'deleted_at',
        "role",
        "pivot",
    ];
    protected $dates = [ 'deleted_at' ];


    private $permissions = [];
    private $front_permissions = [];

    public function __construct( array $attributes = [] ) {
        $this->connection = Config::get( "database.default" );
        $this->fillable = self::$fields;
        $this->table = self::$tableName;
        $this->primaryKey = self::$pKey;

        parent::__construct( $attributes );
    }

    /******************************************/
    /*         RELATION METHODS               */
    /******************************************/

    public function company() {
        return $this->belongsTo( Company::class, "id_cmp", 'cmp_id' );
    }

    public function roles() {
        return $this->belongsToMany(
            Role::class,
            "sa_users2roles",
            "id_user",
            "id_role"
        );//->where( "visible", Role::VISIBLE );
    }

    public function permissions( $frontend = true ) {
        if ( !$frontend ) {
            $key = 'backend';
            if ( empty( $this->permissions[ $key ] ) ) {
                $this->permissions[ $key ] = [];
                /** @var Role[] $roles */
                $roles = $this->roles()->with( 'permissions.permissionItem' )->get();
                foreach ( $roles as $role ) {
                    foreach ( $role->permissions as $permission ) {
                        if ( !empty( $permission->permissionItem->rest_model ) ) {
                            /** @var Permission $permission */
                            if ( empty( $this->permissions[ $key ][ $permission->permissionItem->rest_model ] ) ) {

                                $additional = empty( $permission->permissionItem->additional ) ? null : new PermissionItemAdditional(
                                    json_decode( $permission->permissionItem->additional, true )
                                );
                                $this->permissions[ $key ][ $permission->permissionItem->rest_model ] = [
                                    "name"       => $permission->permissionItem->name,
                                    "permission" => $permission->permission,
                                    "additional" => $additional,
                                    "rest_model" => $permission->permissionItem->rest_model,
                                ];
                            } else {
                                $temp = $this->permissions[ $key ][ $permission->permissionItem->rest_model ];

                                $permissionValue = PermissionItem::mergePermissions(
                                    $temp[ "permission" ],
                                    $permission->permission
                                );

                                $additionalValue = PermissionItem::mergeAdditional(
                                    $temp[ 'additional' ],
                                    json_decode( $permission->permissionItem->additional, true )
                                );


                                $this->permissions[ $key ][ $permission->permissionItem->rest_model ] = [
                                    "name"       => $permission->permissionItem->name,
                                    "permission" => $permissionValue,
                                    "additional" => $additionalValue,
                                    "rest_model" => $permission->permissionItem->rest_model,
                                ];
                            }
                        }
                    }
                }
            }
            return array_values( $this->permissions[ $key ] );
        } else {
            $key = 'frontend';
            if ( empty( $this->permissions[ $key ] ) ) {
                $this->permissions[ $key ] = [];
                /** @var Role[] $roles */
                $roles = $this->roles()->with( 'permissions.permissionItem' )->get();
                foreach ( $roles as $role ) {
                    foreach ( $role->permissions as $permission ) {
                        if ( !empty( $permission->permissionItem->front_model ) ) {
                            /** @var Permission $permission */
                            if ( empty( $this->permissions[ $key ][ $permission->permissionItem->front_model ] ) ) {

                                $additional = empty( $permission->permissionItem->additional ) ? null : new PermissionItemAdditional(
                                    json_decode( $permission->permissionItem->additional, true )
                                );
                                $this->permissions[ $key ][ $permission->permissionItem->front_model ] = [
                                    "name"       => $permission->permissionItem->name,
                                    "permission" => $permission->permission,
                                    "additional" => $additional,
                                    "model"      => $permission->permissionItem->front_model,
                                ];
                            } else {
                                $temp = $this->permissions[ $key ][ $permission->permissionItem->front_model ];

                                $permissionValue = PermissionItem::mergePermissions(
                                    $temp[ "permission" ],
                                    $permission->permission
                                );

                                $additionalValue = PermissionItem::mergeAdditional(
                                    $temp[ 'additional' ],
                                    json_decode( $permission->permissionItem->additional, true )
                                );


                                $this->permissions[ $key ][ $permission->permissionItem->front_model ] = [
                                    "name"       => $permission->permissionItem->name,
                                    "permission" => $permissionValue,
                                    "additional" => $additionalValue,
                                    "model"      => $permission->permissionItem->front_model,
                                ];
                            }
                        }
                    }
                }

                foreach ( $this->permissions[ $key ] as &$permission ) {
                    if ( !empty( $permission[ 'additional' ] ) && $permission[ 'additional' ] instanceof PermissionItemAdditional ) {
                        $additional = $permission[ 'additional' ]->getDataSet();
                        unset( $additional[ PermissionItemAdditional::FIELD_RESTRICTIONS ] );
                        $permission[ 'additional' ] = $additional;
                    }
                }
            }

            return array_values( $this->permissions[ $key ] );

        }
    }

    public function creator() {
        return $this->belongsTo( self::class, "created_by", "id_user" );
    }

    public function loginState() {
        return $this->hasOne(
            LoginState::class,
            'id_logged_user',
            'id_user'
        )->whereType( LoginState::TYPE_ACCESS_TOKEN )->whereRaw( 'expire_at > UNIX_TIMESTAMP()' );
    }

    /******************************************/
    /*         OBJECT METHODS                 */
    /******************************************/
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    /**
     * @param string $permissionItem
     * @param int $permissionValueCheck
     * @param null $type
     * @return false|array
     */
    public function hasPermission( string $permissionItem, int $permissionValueCheck, $type = null ) {
        $itemList = array_filter(
            $this->permissions( false ),
            function ( $item ) use ( $permissionItem, $permissionValueCheck ) {
                return $item[ 'rest_model' ] == $permissionItem && $item[ 'permission' ] & $permissionValueCheck;
            }
        );

        if ( !$itemList ) return false;
        if ( count( $itemList ) > 1 ) {
            \Log::error( "There are more than one model in permissions" );
        }

        /** @var PermissionItemAdditional $permissionAdditional */
        $permission = reset( $itemList );
        if ( empty( $permission[ 'additional' ] ) || empty( $permission[ 'additional' ]->types ) ) return $permission;
        foreach ( $permission[ 'additional' ]->types as $permissionType ) {
            if ( $permissionType == $type ) {
                return $permission;
            }
        }

        return false;
    }

    /**
     * @param string $permissionItem
     * @param int $permissionValueCheck
     * @param string $action
     * @param null $type
     * @param bool $emptyAsTrue
     * @return bool
     */
    public function hasPermissionAction( string $permissionItem, int $permissionValueCheck, string $action, $type = null, $emptyAsTrue = true ): bool {
        $permission = $this->hasPermission( $permissionItem, $permissionValueCheck, $type );
        if ( !$permission ) {
            return false;
        }

        return PermissionItem::hasAction( $permission, $action, $emptyAsTrue );
    }
    /******************************************/
    /*         SCHEMA METHODS                 */
    /******************************************/

    protected static function boot() {
        parent::boot();

        // Creating new user
        self::creating(
            function ( $model ) {
                /** @var $model User */
                if ( app( 'auth' )->user() != null ) {
                    /** @var User $creator */
                    $creator = app( 'auth' )->user();
                    $model->created_by = $creator->id_user;
                    $model->id_cmp = $creator->id_cmp;
                }
            }
        );

        // Updating user
        self::updating(
            function ( $model ) {
                /** @var User $model */
                $changes = array_keys( $model->getDirty() );

                $model->last_upd_by = app( 'auth' )->user()->id_user;
            }
        );

        self::deleting(
            function ( $model ) {
                /** @var $model User */
                $model->last_upd_by = app( 'auth' )->user()->id_user;
            }
        );
    }

}
