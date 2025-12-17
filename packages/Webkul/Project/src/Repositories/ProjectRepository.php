<?php

namespace Webkul\Project\Repositories;

use App\Helpers\Constants;
use Illuminate\Container\Container;
use Webkul\Core\Eloquent\Repository;
use Webkul\Project\Contracts\Project;

class ProjectRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected ProjectCategoryRepository $productCategoryRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return Project::class;
    }

    /**
     * Create.
     *
     * @return \Webkul\Project\Contracts\Project
     */
    public function create(array $data)
    {
        $project = parent::create($data);
        return $project;
    }

    /**
     * Update.
     *
     * @param  int  $id
     * @return \Webkul\Project\Contracts\Project
     */
    public function update(array $data, $id)
    {
        $project = parent::update($data, $id);
        return $project;
    }
}
