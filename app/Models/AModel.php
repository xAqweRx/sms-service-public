<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 10/25/2018
 * Time: 1:46:41 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class AModel
 * @package App\Models
 * @uses \Illuminate\Database\Eloquent\Builder
 */
abstract class AModel extends Model {

    /**
     * @param bool $withDeleted
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getList( $withDeleted = false ) {
        /**
         * @var $model Builder | SoftDeletes
         */
        $model = new static();

        if ( $withDeleted && in_array( SoftDeletes::class, class_uses( static::class ) ) ) {
            $model->withTrashed();
        }
        return $model->get();
    }


    public static function getOne( $id, $withDeleted = false ) {
        /**
         * @var $model Builder | SoftDeletes
         */
        $model = new static();
        if ( $withDeleted && in_array( SoftDeletes::class, class_uses( static::class ) ) ) {
            $model->withTrashed();
        }

        $model->findOrFail( $id );
        return $model;
    }
}
