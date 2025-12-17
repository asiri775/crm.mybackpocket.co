<?php

namespace Webkul\Project\Providers;

use Webkul\Core\Providers\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Project\Models\ProjectCategory::class,
        \Webkul\Project\Models\Project::class,
        \Webkul\Project\Models\ProjectTask::class,
    ];
}
