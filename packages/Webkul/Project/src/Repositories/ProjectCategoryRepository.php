<?php

namespace Webkul\Project\Repositories;

use Webkul\Core\Eloquent\Repository;

class ProjectCategoryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Webkul\Project\Contracts\ProjectCategory';
    }
}
