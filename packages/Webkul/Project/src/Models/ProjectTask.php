<?php

namespace Webkul\Project\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Activity\Traits\LogsActivity;
use Webkul\Contact\Models\PersonProxy;
use Webkul\Project\Contracts\ProjectTask as ProjectTaskContract;

class ProjectTask extends Model implements ProjectTaskContract
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'project_id',
        'name',
        'priority',
        'status',
        'agent_id',
        'cc_agents',
        'start_date',
        'deadline',
        'description'
    ];

    public function project()
    {
        return $this->belongsTo(ProjectProxy::modelClass());
    }

    public function agent()
    {
        return $this->belongsTo(PersonProxy::modelClass());
    }

}
