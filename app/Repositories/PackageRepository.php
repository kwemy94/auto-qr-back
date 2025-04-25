<?php

namespace App\Repositories;

use App\Models\Package;

class PackageRepository extends ResourceRepository {

    public function __construct(Package $package)
    {
        $this->model = $package;
    }
    
}
