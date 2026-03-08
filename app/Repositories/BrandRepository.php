<?php

namespace App\Repositories;

use App\Models\Brand;

class BrandRepository extends BaseRepository
{
    /**
     * BrandRepository constructor.
     *
     * @param Brand $model
     */
    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }
}
