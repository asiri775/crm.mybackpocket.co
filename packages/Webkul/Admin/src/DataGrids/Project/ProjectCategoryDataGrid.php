<?php

namespace Webkul\Admin\DataGrids\Project;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ProjectCategoryDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('project_categories')
            ->select(
                'project_categories.id',
                'project_categories.name'
            );

        $user = auth()->user();
        if ($user) {
            $queryBuilder->where('project_categories.user_id', $user->id);
        }

        $this->addFilter('id', 'project_categories.id');

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.projects.categories.index.datagrid.name'),
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
        // if (bouncer()->hasPermission('projects.view')) {
        //     $this->addAction([
        //         'index' => 'view',
        //         'icon' => 'icon-eye',
        //         'title' => trans('admin::app.projects.categories.index.datagrid.view'),
        //         'method' => 'GET',
        //         'url' => fn($row) => route('admin.projects.categories.view', $row->id),
        //     ]);
        // }

        if (bouncer()->hasPermission('projects.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.projects.categories.index.datagrid.edit'),
                'method' => 'GET',
                'url' => fn($row) => route('admin.projects.categories.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('projects.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.projects.categories.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn($row) => route('admin.projects.categories.delete', $row->id),
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
            'title' => trans(key: 'admin::app.projects.categories.index.datagrid.delete'),
            'method' => 'POST',
            'url' => route('admin.projects.categories.mass_delete'),
        ]);
    }
}
