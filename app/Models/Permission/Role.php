<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 10/26/2018
 * Time: 5:43:24 PM
 */

namespace App\Models\Permission;


use App\Models\User\User;
use App\Traits\BaseModelTrait;
use App\Traits\PaginationTrait;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Permission\Role
 *
 * @property int $id_role
 * @property string $role_name
 * @property string $company
 * @property int $sort
 * @property string $visible
 * @property int $id_lov_role
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Role whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Role whereIdLovRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Role whereIdRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Role whereRoleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Role whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Role whereVisible($value)
 * @mixin \Eloquent
 */
class Role extends Model {

    // todo change according needs
    const ROLE_DIALER_AGENT = 2;
    const ROLE_MANAGER = 8;
    const ROLE_OWNER = 17;
    const ROLE_QC_AGENT = 10;

    const VISIBLE = 'Y';
    const NOT_VISIBLE = 'N';

    const TABLE_NAME = 'sa_roles';
    protected static $pKey = 'id_role';
    protected static $fields = [
        "id_role",
        "role_name",
        "company",
        "sort",
        "visible"
    ];

    protected $hidden = [
        'pivot'
    ];

    public $timestamps = false;


    public function permissions() {
        return $this->hasMany( Permission::class, "id_role", "id_role" );
    }

    public function users() {
        return $this->belongsToMany(
            User::class,
            'sa_users2roles',
            'id_role',
            'id_user'
        );
    }

    public function __construct( array $attributes = [] ) {
        parent::__construct( $attributes );
        $this->primaryKey = self::$pKey;
        $this->table = self::TABLE_NAME;
    }

    /******************************************/
    /*         SCHEMA METHODS                 */
    /******************************************/

    public static function getOwnerRole() {
        return self::whereIdRole( self::ROLE_MANAGER )->first();
    }

}
