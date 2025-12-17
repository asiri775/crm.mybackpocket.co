<?php

namespace Webkul\Project\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Activity\Traits\LogsActivity;
use Webkul\Project\Contracts\ProjectCategory as ProjectCategoryContract;

class ProjectCategory extends Model implements ProjectCategoryContract
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
    ];
}
