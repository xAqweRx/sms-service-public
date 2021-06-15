<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 10/26/2018
 * Time: 5:38:45 PM
 */

namespace App\Models\Permission;

use App\Models\CompositeKeyTrait;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Permission\Permission
 *
 * @property int $id_role
 * @property int $id_permission_item
 * @property int|null $permission
 * @property-read \App\Models\Permission\PermissionItem $permissionItem
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Permission whereIdPermissionItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Permission whereIdRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\Permission wherePermission($value)
 * @mixin \Eloquent
 */
class Permission extends Model {

    use CompositeKeyTrait;

    const CAN_READ_ONE = 1;
    const CAN_READ_LIST = 2;
    const CAN_WRITE = 4;
    const CAN_DELETE = 8;

    protected $table = "sa_role_permission";

    public $incrementing = false;
    public $timestamps = false;

    protected $primaryKey = [
        "id_role",
        "id_permission_item"
    ];

    protected $fillable = [
        "permission"
    ];

    protected $hidden = [ "id_role", "id_permission_item", "permission_item" ];


    public function permissionItem() {
        return $this->belongsTo( PermissionItem::class, "id_permission_item", "id" );
    }


}
