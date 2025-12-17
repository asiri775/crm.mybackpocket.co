<?php

namespace Webkul\Contact\Repositories;

use App\Models\Space;
use Illuminate\Container\Container;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeValueRepository;
use Webkul\Core\Eloquent\Repository;

class SpaceRepository extends Repository
{
    /**
     * Searchable fields
     */
    protected $fieldSearchable = [
        'id',
        'title',
        'create_user'
    ];

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected AttributeValueRepository $attributeValueRepository,
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
        return Space::class;
    }

}
