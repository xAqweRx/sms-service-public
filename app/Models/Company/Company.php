<?php

namespace App\Models\Company;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Company\Company
 *
 * @property int $cmp_id
 * @property string|null $cmp_name
 * @property string|null $cmp_hash
 * @property string|null $cmp_description
 * @property int|null $created_id User id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $updated_id User id
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $subscription_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company\Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company\Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company\Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company\Company whereCmpDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company\Company whereCmpHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company\Company whereCmpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company\Company whereCmpName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company\Company whereCreatedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company\Company whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company\Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Company\Company whereUpdatedId($value)
 * @mixin \Eloquent
 */
class Company extends Model {

    protected static $tableName = "sa_company";
    protected static $pKey = "cmp_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cmp_id',
        'cmp_name',
        'cmp_description'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'subscription_id',
        "created_id",
        "created_at",
        "updated_id",
        "updated_at"
    ];

    protected static function boot() {
        parent::boot();

        self::creating( function ( $model ) {
            /** @var $model Company */
            if ( app( 'auth' )->user() != null ) {
                $model->created_id = app( 'auth' )->user()->id_user;
            }
        } );
        self::updating( function ( $model ) {
            /** @var $model Company */
            $model->updated_id = app( 'auth' )->user()->id_user;
        } );
        self::deleting( function ( $model ) {
            /** @var $model Company */
            $model->updated_id = app( 'auth' )->user()->id_user;
        } );
    }

    public function __construct( array $attributes = [] ) {
        $this->table = self::$tableName;
        $this->primaryKey = self::$pKey;
        parent::__construct( $attributes );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users() {
        return $this->hasMany( User::class, 'id_cmp', self::$pKey );
    }

    public static function getTableName() {
        return self::$tableName;
    }

    public static function getPrimaryKey() {
        return self::$pKey;
    }

}
