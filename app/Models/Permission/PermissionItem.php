<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 10/26/2018
 * Time: 5:38:25 PM
 */

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Permission\PermissionItem
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $rest_model
 * @property string|null $front_model
 * @property string|null $additional
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\PermissionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\PermissionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\PermissionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\PermissionItem whereAdditional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\PermissionItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\PermissionItem whereFrontModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\PermissionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\PermissionItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission\PermissionItem whereRestModel($value)
 * @mixin \Eloquent
 */
class PermissionItem extends Model {
    public $timestamps = false;
    protected $table = "sa_permission_item";
    protected $primaryKey = "id";
    protected $fillable = [
        "name",
        "description",
    ];

    protected $hidden = [ "id" ];


    public static function mergeAdditional( $first, $second ) {
        if ( $first == null || $second == null ) {
            return null;
        }
        if ( !( $first instanceof PermissionItemAdditional ) ) {
            $first = new PermissionItemAdditional($first);
        }

        if ( !(  $second instanceof PermissionItemAdditional ) ) {
             $second = new PermissionItemAdditional( $second);
        }

        $first->merge($second);
        return $first;

    }

    public static function mergePermissions( $first, $second ) {
        $permissionValue = 0;
        foreach ( [
            Permission::CAN_READ_ONE,
            Permission::CAN_READ_LIST,
            Permission::CAN_WRITE,
            Permission::CAN_DELETE,
        ] as $permissionRule ) {
            $permissionValue = $permissionValue | ( ( $second & $permissionRule ) | ( $first & $permissionRule ) );
        }

        return $permissionValue;
    }

    /**
     * @param array $permissionItem Array with structure of PermissionItem
     * @param string $action Action to search
     * @param bool $emptyAsTrue Result to show if some of level in structure is empty
     * @return bool
     */
    public static function hasAction(array $permissionItem, string $action, $emptyAsTrue = true): bool {
        if (
            empty($permissionItem['additional']) ||
            (!$permissionItem['additional'] instanceof PermissionItemAdditional) ||
            empty($permissionItem['additional']->actions)
        ) {
            return $emptyAsTrue;
        }

        return in_array($action, $permissionItem['additional']->actions);
    }
}
