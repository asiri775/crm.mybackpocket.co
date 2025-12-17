<?php

namespace Webkul\Admin\DataGrids\Project;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ProjectDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('projects')
            ->select(
                'projects.id',
                'projects.project_id',
                'projects.name',
                'projects.start_date',
                'projects.deadline',
                'projects.category_id',
                'projects.phases',
                'projects.description',
                // 'persons.name as client_name',
                'project_categories.name as category_name',
            )
            ->leftJoin('project_categories', 'projects.category_id', '=', 'project_categories.id');


        $user = auth()->user();
        if ($user) {
            $queryBuilder->where('projects.user_id', $user->id);
        }

        $this->addFilter('id', 'projects.id');

        // $this->addFilter('client_name', 'persons.name');
        $this->addFilter('category_name', 'project_categories.name');
        $this->addFilter('name', 'projects.name');

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index' => 'project_id',
            'label' => trans('admin::app.projects.index.datagrid.projectId'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.projects.index.datagrid.name'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        // $this->addColumn([
        //     'index' => 'client_name',
        //     'label' => trans('admin::app.projects.index.datagrid.client'),
        //     'type' => 'string',
        //     'searchable' => false,
        //     'sortable' => true,
        //     'filterable' => true,
        //     'filterable_type' => 'searchable_dropdown',
        //     'filterable_options' => [
        //         'repository' => \Webkul\Contact\Repositories\PersonRepository::class,
        //         'column' => [
        //             'label' => 'name',
        //             'value' => 'name',
        //         ],
        //     ],
        //     'closure' => function ($row) {
        //         $route = route('admin.contacts.persons.view', $row->client_id);
        //         return "<a class=\"text-brandColor transition-all hover:underline\" href='" . $route . "'>" . $row->client_name . '</a>';
        //     },
        // ]);

        $this->addColumn([
            'index' => 'category_name',
            'label' => trans('admin::app.projects.index.datagrid.category'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'filterable_type' => 'searchable_dropdown',
            'filterable_options' => [
                'repository' => \Webkul\Project\Repositories\ProjectCategoryRepository::class,
                'column' => [
                    'label' => 'name',
                    'value' => 'name',
                ],
            ],
            'closure' => function ($row) {
                return $row->category_name;
            },
        ]);

        $this->addColumn([
            'index' => 'start_date',
            'label' => trans('admin::app.projects.index.datagrid.startDate'),
            'type' => 'date',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'deadline',
            'label' => trans('admin::app.projects.index.datagrid.deadline'),
            'type' => 'date',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'phases',
            'label' => trans('admin::app.projects.index.datagrid.phases'),
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
            $this->addAction([
                'index' => 'view',
                'icon' => 'icon-eye',
                'title' => trans('admin::app.projects.index.datagrid.view'),
                'method' => 'GET',
                'url' => fn($row) => route('admin.projects.view', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('projects.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.projects.index.datagrid.edit'),
                'method' => 'GET',
                'url' => fn($row) => route('admin.projects.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('projects.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.projects.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn($row) => route('admin.projects.delete', $row->id),
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
            'title' => trans('admin::app.projects.index.datagrid.delete'),
            'method' => 'POST',
            'url' => route('admin.projects.mass_delete'),
        ]);
    }
}
