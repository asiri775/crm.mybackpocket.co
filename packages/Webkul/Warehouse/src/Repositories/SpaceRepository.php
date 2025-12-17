<?php

namespace Webkul\Warehouse\Repositories;

use Webkul\Core\Eloquent\Repository;

class SpaceRepository extends Repository
{
    /**
     * Searchable fields
     */
    protected $fieldSearchable = [
        'id',
        'title'
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'App\Models\Space';
    }
}
