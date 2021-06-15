<?php

namespace App\Models\Permission;

use App\Models\ARepository;
use App\Traits\FullRepositoryTrait;
use App\Traits\ReadRepositoryTrait;

class RoleRepository extends ARepository {
    use ReadRepositoryTrait;

    function __construct()
    {
        parent::__construct();
        $this->model = Role::class;
    }
}
