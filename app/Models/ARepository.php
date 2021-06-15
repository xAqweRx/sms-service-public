<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 10/25/2018
 * Time: 1:46:41 PM
 */

namespace App\Models;


use App\Constants\ErrorCodes;
use App\Models\Permission\Permission;
use App\Models\Permission\PermissionItemAdditional;
use Illuminate\Validation\UnauthorizedException;

abstract class ARepository {
    const PAGER_PAGE_NUMBER = "pageNumber";

    const PAGER_PAGE_SIZE = "pageSize";
    const PAGER_DEFAULT_PAGE_SIZE = 20;

    const SORT_FIELD = "sortField";
    const SORT_ORDER = "sortOrder";

    const STATUS_DELETED = 1;
    const STATUS_NOT_DELETED = 0;

    const FILTER_RANGE_FROM_KEY = "from";
    const FILTER_RANGE_TO_KEY = "to";

    const METHODS = [
        Permission::CAN_READ_ONE => 'READ',
        Permission::CAN_READ_LIST => 'READ',
        Permission::CAN_WRITE => 'WRITE',
        Permission::CAN_DELETE => 'DELETE',
    ];

    /** @var $auth \Auth */
    protected $auth;
    protected $model;

    /** @var array */
    protected $permissionItem;

    public function __construct() {
        $this->auth = app("auth");
    }

    /*****************************
     * PERMISSION METHODS
     ****************************/

    /**
     * Check, is user have permission for this action
     *
     * @param      $permissionAction
     * @param null $additional
     * @param bool $throwable
     * @return bool
     */
    protected function checkPermission( $permissionAction, $additional = null, $throwable = true ) {
        if ( !$this->auth->check() || !$this->permissionItem = $this->auth->user()->hasPermission(
                $this->model,
                $permissionAction,
                $additional
            ) ) {
            if ($throwable) {
                $errorName = "NO_".self::METHODS[ $permissionAction ]."_PERMISSION";
                throw new UnauthorizedException(constant(ErrorCodes::class."::".$errorName));
            }

            return false;
        }

        return true;
    }

    /**
     * Check, is user have permission for this action
     *
     * @param      $permissionAction
     * @param      $model
     * @param null $additional
     * @param bool $throwable
     * @return bool
     */
    protected function checkPermissionBehaviour( $permissionAction, $model, $additional = null, $throwable = true ) {
        if ( !$this->auth->check() || !$this->permissionItem = $this->auth->user()->hasPermission(
                $model,
                $permissionAction,
                $additional
            ) ) {
            if ($throwable) {
                $errorName = "NO_".self::METHODS[ $permissionAction ]."_PERMISSION";
                throw new UnauthorizedException(constant(ErrorCodes::class."::".$errorName));
            }

            return false;
        }

        return true;
    }

    /**
     * Check is user have permission for some custom write action (create / update also)
     *
     * @param string $action
     * @param int    $permissionType
     * @param bool   $throwException
     * @return bool
     */
    protected function canDo(string $action, $permissionType = Permission::CAN_WRITE, $throwException = true  ) {
        if ( $throwException ) {
            $this->checkPermission($permissionType, null, $throwException);
        } elseif ( !$this->checkPermission($permissionType, null, $throwException) ) {
            return false;
        }

        $additional = $this->permissionItem['additional'];

        // Check for empty $additional and old versions of additional object
        if (!$additional instanceof PermissionItemAdditional) {
            return true;
        }

        $errorName = "NO_".self::METHODS[ $permissionType ]."_PERMISSION";

        // Check in allowed actions
        if ( !empty($additional->actions) && !in_array($action, $additional->actions) ) {
            if ( $throwException ) {
                throw new UnauthorizedException(constant(ErrorCodes::class."::".$errorName));
            } else {
                return false;
            }
        }

        if ( !empty($additional->forbiddenActions) && in_array($action, $additional->forbiddenActions) ) {
            if ( $throwException ) {
                throw new UnauthorizedException(constant(ErrorCodes::class."::".$errorName));
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Get array of fields allowed to read in list
     *
     * This method fetch allowed fields list for get list operation from user permissions
     *
     * @return array Fields array
     */
    protected function getAllowedToReadFieldsInList() {
        return $this->getAdditionalItems('accessList');
    }

    /**
     * Get array of fields allowed to read of one
     *
     * This method fetch allowed fields list for get one operation from user permissions
     *
     * @return array Fields array
     */
    protected function getAllowedToReadFieldsOfOne() {
        return $this->getAdditionalItems('accessOne');
    }


    /**
     * Get array of fields allowed to write
     *
     * @return array
     */
    protected function getAllowedToWriteFieldsList() {
        return $this->getAdditionalItems('accessWrite');
    }

    /**
     * Get array of restrictions
     *
     * @return array|false
     */
    protected function getRestrictions() {
        return $this->getAdditionalItems('restrictions');
    }

    /**
     * Base method to get additional items
     *
     * @param string $field
     * @return array|false
     */
    private function getAdditionalItems(string $field) {
        $additional = $this->permissionItem['additional'];
        if ($additional instanceof PermissionItemAdditional) {
            return $additional->$field ?? [];
        }

        return [];
    }
}
