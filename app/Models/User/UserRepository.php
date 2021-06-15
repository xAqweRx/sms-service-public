<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 10/25/2018
 * Time: 1:46:21 PM
 */

namespace App\Models\User;

use App\Models\ARepository;
use App\Models\Permission\Permission;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class UserRepository
 * @package App\Models\User
 * Main logic of user flow work. Where you can find login process
 * and additional info about work with User(@see User)
 */
class UserRepository extends ARepository {

    /**
     * @var JWTAuth
     */
    private $jwt;

    public function __construct() {
        parent::__construct();
        $this->jwt = app( JWTAuth::class );
        $this->model = User::class;
    }

    function login( string $login, string $password, string $companyHash ) {
        return User::loginUser( $login, $password, $companyHash );
    }

    /**
     * @param array $sort
     * @param array $filter
     * @param array $fields
     * @param bool $withDeleted
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getList( $sort = [], $filter = [], $fields = [], $withDeleted = false ) {
        $this->checkPermission( Permission::CAN_READ_LIST );
        $accessList = $this->getAllowedToReadFieldsInList();

        if ( empty( $fields ) ) {
            $fields = $accessList;
        } else {
            if ( !empty( $accessList ) ) {
                $intersected = array_intersect( $fields, $accessList );
                $fields = empty( $intersected ) ? $accessList : $intersected;
            }
        }

        return User::getList( $withDeleted );
    }

    /**
     * @param $id
     * @param array $fields
     * @param bool $withDeleted
     * @param bool $ignorePermissionFields
     * @return User|null
     */
    public function getOne( $id, $fields = [], $withDeleted = false, $ignorePermissionFields = false ) {
        $this->checkPermission( Permission::CAN_READ_ONE );
        if ( $ignorePermissionFields ) {
            $accessOne = $fields;
        } else {
            $accessOne = $this->getAllowedToReadFieldsOfOne();
        }

        return User::getOne( $id, $withDeleted );
    }

    public function refreshToken( int $id_user ) {
        return LoginState::refreshToken( $id_user );
    }
}
