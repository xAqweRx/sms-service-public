<?php


namespace App\Traits;

use App\Models\AModel;
use App\Models\Permission\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Trait ReadRepositoryTrait
 * @property Builder | AModel $model
 * @package App\Traits
 */
trait ReadRepositoryTrait {
    /**
     * @param      $id
     * @param bool $withDeleted
     * @return Model|null
     */
    public function getOne( $id, $withDeleted = false ): Model {
        $this->checkPermission( Permission::CAN_READ_ONE );
        return $this->model::getOne($id, $withDeleted);
    }

    /**
     * Get list
     *
     * @return array|BinaryFileResponse
     */
    public function getList() {
        $this->checkPermission( Permission::CAN_READ_LIST );
        if ( !empty( $this->model ) ) {
            return $this->model::getList( $withDeleted );
        }
    }
}
