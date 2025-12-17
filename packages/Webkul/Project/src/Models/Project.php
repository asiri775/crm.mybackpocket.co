<?php

namespace Webkul\Project\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Activity\Traits\LogsActivity;
use Webkul\Contact\Models\PersonProxy;
use Webkul\Project\Contracts\Project as ProjectContract;

class Project extends Model implements ProjectContract
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'project_number',
        'project_id',
        'name',
        'client_id',
        'start_date',
        'deadline',
        'category_id',
        'phases',
        'description'
    ];

    public function client()
    {
        return $this->belongsTo(PersonProxy::modelClass());
    }

    public function category()
    {
        return $this->belongsTo(ProjectCategoryProxy::modelClass());
    }

}
