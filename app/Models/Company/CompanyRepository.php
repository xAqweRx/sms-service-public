<?php

namespace App\Models\Company;


use App\Models\ARepository;

class CompanyRepository extends ARepository {

    public function __construct() {
        parent::__construct();
        $this->model = Company::class;
    }
}

