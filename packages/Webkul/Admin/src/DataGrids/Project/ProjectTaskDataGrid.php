<?php

namespace Webkul\Admin\DataGrids\Project;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ProjectTaskDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('project_tasks')
            ->select(
                'project_tasks.id',
                'project_tasks.project_id',
                'project_tasks.name as task_name',
                'project_tasks.priority',
                'project_tasks.status',
                'project_tasks.agent_id',
                'project_tasks.cc_agents',
                'project_tasks.start_date',
                'project_tasks.deadline',
                'project_tasks.description',
                'persons.name as agent_name',
                'projects.name as project_name',
            )
            ->leftJoin('persons', 'project_tasks.agent_id', '=', 'persons.id')
            ->leftJoin('projects', 'project_tasks.project_id', '=', 'projects.id');

        $this->addFilter('id', 'projects.id');

        $user = auth()->user();
        if ($user) {
            $queryBuilder->where('project_tasks.user_id', $user->id);
        }


        if (!is_null(request()->input('projectId'))) {
            $queryBuilder->havingRaw(DB::getTablePrefix() . 'project_tasks.project_id = ' . request()->input('projectId'));
        }

        $this->addFilter('task_name', 'project_tasks.name');
        $this->addFilter('agent_name', 'persons.name');
        $this->addFilter('project_name', 'projects.name');

        $this->addFilter('start_date', 'project_tasks.start_date');
        $this->addFilter('deadline', 'project_tasks.deadline');

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {

        $this->addColumn([
            'index' => 'task_name',
            'label' => trans('admin::app.projects.tasks.index.datagrid.task'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        if (is_null(request()->input('projectId'))) {
            $this->addColumn([
                'index' => 'project_name',
                'label' => trans('admin::app.projects.tasks.index.datagrid.project'),
                'type' => 'string',
                'searchable' => false,
                'sortable' => true,
                'filterable' => true,
                'filterable_type' => 'searchable_dropdown',
                'filterable_options' => [
                    'repository' => \Webkul\Project\Repositories\ProjectRepository::class,
                    'column' => [
                        'label' => 'name',
                        'value' => 'name',
                    ],
                ],
                'closure' => function ($row) {
                    if ($row->project_id) {
                        $route = route('admin.projects.edit', $row->project_id);
                        return "<a class=\"text-brandColor transition-all hover:underline\" href='" . $route . "'>" . $row->project_name . '</a>';
                    }
                    return '-';
                },
            ]);
        }

        $this->addColumn([
            'index' => 'agent_name',
            'label' => trans('admin::app.projects.tasks.index.datagrid.agent'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'filterable_type' => 'searchable_dropdown',
            'filterable_options' => [
                'repository' => \Webkul\Contact\Repositories\PersonRepository::class,
                'column' => [
                    'label' => 'name',
                    'value' => 'name',
                ],
            ],
            'closure' => function ($row) {
                if ($row->agent_id) {
                    $route = route('admin.contacts.persons.view', $row->agent_id);
                    return "<a class=\"text-brandColor transition-all hover:underline\" href='" . $route . "'>" . $row->agent_name . '</a>';
                }
                return '-';
            },
        ]);

        $this->addColumn([
            'index' => 'priority',
            'label' => trans('admin::app.projects.tasks.index.datagrid.priority'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.projects.tasks.index.datagrid.status'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'start_date',
            'label' => trans('admin::app.projects.tasks.index.datagrid.startDate'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'deadline',
            'label' => trans('admin::app.projects.tasks.index.datagrid.deadline'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('projects.view')) {
            // $this->addAction([
            //     'index' => 'view',
            //     'icon' => 'icon-eye',
            //     'title' => trans('admin::app.projects.tasks.index.datagrid.view'),
            //     'method' => 'GET',
            //     'url' => fn($row) => route('admin.projects.tasks.view', $row->id),
            // ]);
        }

        if (bouncer()->hasPermission('projects.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.projects.tasks.index.datagrid.edit'),
                'method' => 'GET',
                'url' => fn($row) => route('admin.projects.tasks.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('projects.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.projects.tasks.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn($row) => route('admin.projects.tasks.delete', $row->id),
            ]);
        }
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        $this->addMassAction([
            'icon' => 'icon-delete',
            'title' => trans('admin::app.projects.tasks.index.datagrid.delete'),
            'method' => 'POST',
            'url' => route('admin.projects.tasks.mass_delete'),
        ]);
    }
}
