<?php

return [
    'leads' => [
        'name' => 'Leads',
        'repository' => 'Webkul\Lead\Repositories\LeadRepository',
        'label_column' => 'title',
        'userCheck' => true,
    ],

    'lead_sources' => [
        'name' => 'Lead Sources',
        'repository' => 'Webkul\Lead\Repositories\SourceRepository',
        'userCheck' => false,
    ],

    'lead_types' => [
        'name' => 'Lead Types',
        'repository' => 'Webkul\Lead\Repositories\TypeRepository',
        'userCheck' => false,
    ],

    'lead_pipelines' => [
        'name' => 'Lead Pipelines',
        'repository' => 'Webkul\Lead\Repositories\PipelineRepository',
        'userCheck' => false,
    ],

    'lead_pipeline_stages' => [
        'name' => 'Lead Pipeline Stages',
        'repository' => 'Webkul\Lead\Repositories\StageRepository',
        'userCheck' => false,
    ],

    'users' => [
        'name' => 'Sales Owners',
        'repository' => 'Webkul\User\Repositories\UserRepository',
        'userCheck' => false,
    ],

    'organizations' => [
        'name' => 'Organizations',
        'repository' => 'Webkul\Contact\Repositories\OrganizationRepository',
        'userCheck' => true,
    ],

    'persons' => [
        'name' => 'Persons',
        'repository' => 'Webkul\Contact\Repositories\PersonRepository',
        'userCheck' => true,
    ],

    'warehouses' => [
        'name' => 'Warehouses',
        'repository' => 'Webkul\Warehouse\Repositories\WarehouseRepository',
        'userCheck' => false,
    ],

    'locations' => [
        'name' => 'Locations',
        'repository' => 'Webkul\Warehouse\Repositories\LocationRepository',
        'userCheck' => false,
    ],

    'spaces' => [
        'name' => 'Spaces',
        'label_column' => 'title',
        'repository' => 'Webkul\Warehouse\Repositories\SpaceRepository',
        'userCheck' => false,
    ],

    'projects' => [
        'name' => 'Projects',
        'label_column' => 'name',
        'repository' => 'Webkul\Project\Repositories\ProjectRepository',
        'userCheck' => true,
    ],

    'project-categories' => [
        'name' => 'Project Categories',
        'label_column' => 'name',
        'repository' => 'Webkul\Project\Repositories\ProjectCategoryRepository',
        'userCheck' => true,
    ],

];
