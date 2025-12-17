<?php

namespace Webkul\Admin\DataGrids\Lead;

use App\Helpers\Constants;
use App\Models\Space;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Lead\Repositories\PipelineRepository;
use Webkul\Lead\Repositories\SourceRepository;
use Webkul\Lead\Repositories\StageRepository;
use Webkul\Lead\Repositories\TypeRepository;
use Webkul\Tag\Repositories\TagRepository;
use Webkul\User\Repositories\UserRepository;

class LeadDataGrid extends DataGrid
{

    protected $tableIdentifier = 'lead-data-table';

    /**
     * Pipeline instance.
     *
     * @var \Webkul\Contract\Repositories\Pipeline
     */
    protected $pipeline;

    /**
     * Create data grid instance.
     *
     * @return void
     */
    public function __construct(
        protected PipelineRepository $pipelineRepository,
        protected StageRepository $stageRepository,
        protected SourceRepository $sourceRepository,
        protected TypeRepository $typeRepository,
        protected UserRepository $userRepository,
        protected TagRepository $tagRepository,
    ) {
        if (request('pipeline_id')) {
            $this->pipeline = $this->pipelineRepository->find(request('pipeline_id'));
        } else {
            $this->pipeline = $this->pipelineRepository->getDefaultPipeline();
        }
    }

    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('leads')
            ->addSelect(
                'leads.id',
                'leads.space_id',
                'leads.domain',
                'leads.referer',
                'leads.title',
                'leads.status',
                'leads.lead_value',
                'leads.expected_close_date',
                'lead_sources.name as lead_source_name',
                'lead_types.name as lead_type_name',
                'leads.created_at',
                'lead_pipeline_stages.name as stage',
                'lead_tags.tag_id as tag_id',
                'users.id as user_id',
                'users.name as sales_person',
                'persons.id as person_id',
                'persons.name as person_name',
                'tags.name as tag_name',
                'lead_pipelines.rotten_days as pipeline_rotten_days',
                'lead_pipeline_stages.code as stage_code',
                DB::raw('CASE WHEN DATEDIFF(NOW(),' . DB::getTablePrefix() . 'leads.created_at) >=' . DB::getTablePrefix() . 'lead_pipelines.rotten_days THEN 1 ELSE 0 END as rotten_lead'),
            )
            ->leftJoin('users', 'leads.user_id', '=', 'users.id')
            ->leftJoin('persons', 'leads.person_id', '=', 'persons.id')
            ->leftJoin('lead_types', 'leads.lead_type_id', '=', 'lead_types.id')
            ->leftJoin('lead_pipeline_stages', 'leads.lead_pipeline_stage_id', '=', 'lead_pipeline_stages.id')
            ->leftJoin('lead_sources', 'leads.lead_source_id', '=', 'lead_sources.id')
            ->leftJoin('lead_pipelines', 'leads.lead_pipeline_id', '=', 'lead_pipelines.id')
            ->leftJoin('lead_tags', 'leads.id', '=', 'lead_tags.lead_id')
            ->leftJoin('tags', 'tags.id', '=', 'lead_tags.tag_id')
            ->groupBy('leads.id')
            ->where('leads.lead_pipeline_id', $this->pipeline->id);

        // if ($userIds = bouncer()->getAuthorizedUserIds()) {
        //     $queryBuilder->whereIn('leads.user_id', $userIds);
        // }

        if (!is_null(request()->input('rotten_lead.in'))) {
            $queryBuilder->havingRaw(DB::getTablePrefix() . 'rotten_lead = ' . request()->input('rotten_lead.in'));
        }

        $user = auth()->user();
        if ($user) {
            $queryBuilder->where('leads.user_id', $user->id);
        }

        $this->addFilter('id', 'leads.id');
        $this->addFilter('user', 'leads.user_id');
        $this->addFilter('sales_person', 'users.name');
        $this->addFilter('lead_source_name', 'lead_sources.name');
        $this->addFilter('lead_type_name', 'lead_types.name');
        $this->addFilter('person_name', 'persons.name');
        $this->addFilter('type', 'lead_pipeline_stages.code');
        $this->addFilter('stage', 'lead_pipeline_stages.id');
        $this->addFilter('tag_name', 'tags.name');
        $this->addFilter('expected_close_date', 'leads.expected_close_date');
        $this->addFilter('created_at', 'leads.created_at');
        $this->addFilter('rotten_lead', DB::raw('DATEDIFF(NOW(), ' . DB::getTablePrefix() . 'leads.created_at) >= ' . DB::getTablePrefix() . 'lead_pipelines.rotten_days'));

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index' => 'id',
            'label' => 'Lead ID',
            'type' => 'integer',
            'sortable' => true,
            'filterable' => true,
        ]);

        $user = auth()->user();
        if ($user) {
            if ($user->role_id === Constants::ADMIN_ROLE) {
                $this->addColumn([
                    'index' => 'sales_person',
                    'label' => 'User',
                    'type' => 'string',
                    'searchable' => false,
                    'sortable' => true,
                    'filterable' => false,
                    'closure' => function ($row) {
                        return $row->sales_person;
                    },
                ]);
            }
        }

        $this->addColumn([
            'index' => 'created_at',
            'label' => 'Date',
            'type' => 'date',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'filterable_type' => 'date_range',
            'closure' => function ($row) {
                return date(Constants::DISPLAY_TIME_FORMAT, strtotime($row->created_at));
            },
        ]);

        $this->addColumn([
            'index' => 'person_name',
            'label' => 'Contact',
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
                $route = route('admin.contacts.persons.view', $row->person_id);
                return "<a class=\"text-brandColor transition-all hover:underline\" href='" . $route . "'>" . $row->person_name . '</a>';
            },
        ]);

        $this->addColumn([
            'index' => 'title',
            'label' => 'Subject',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'space_id',
            'label' => 'Space',
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'filterable_type' => 'searchable_dropdown',
            'filterable_options' => [
                'repository' => \Webkul\Contact\Repositories\SpaceRepository::class,
                'column' => [
                    'label' => 'title',
                    'value' => 'id',
                ],
            ],
            'closure' => function ($row) {
                $spaceId = $row->space_id;
                if ($spaceId != null) {
                    $space = Space::where('id', $spaceId)->first();
                    if ($space != null) {
                        return "<a target=\"_blank\" class=\"text-brandColor transition-all hover:underline\" href='" . env('MAIN_APP_URL') . '/space/' . $space->slug . "'>" . $space->title . '</a>';
                    } else {
                        return "-";
                    }
                } else {
                    return "-";
                }
            },
        ]);

        $this->addColumn([
            'index' => 'lead_source_name',
            'label' => trans('admin::app.leads.index.datagrid.source'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => $this->sourceRepository->all(['name as label', 'id as value'])->toArray(),
        ]);

        $this->addColumn([
            'index' => 'referer',
            'label' => 'Referer',
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
            'closure' => function ($row) {
                return '<a target="_blank" href="'.$row->referer.'" title="' . $row->referer . '">' . $row->domain . '</a>';
            },
        ]);

        $this->addColumn([
            'index' => 'stage',
            'label' => 'Status',
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => $this->pipeline->stages->pluck('name', 'id')
                ->map(function ($name, $id) {
                    return ['value' => $id, 'label' => $name];
                })
                ->values()
                ->all(),
        ]);


    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('leads.view')) {
            $this->addAction([
                'icon' => 'icon-eye',
                'title' => trans('admin::app.leads.index.datagrid.view'),
                'method' => 'GET',
                'url' => fn($row) => route('admin.leads.view', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('leads.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => trans('admin::app.leads.index.datagrid.delete'),
                'method' => 'delete',
                'url' => fn($row) => route('admin.leads.delete', $row->id),
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
            'title' => trans('admin::app.leads.index.datagrid.mass-delete'),
            'method' => 'POST',
            'url' => route('admin.leads.mass_delete'),
        ]);

        $this->addMassAction([
            'title' => trans('admin::app.leads.index.datagrid.mass-update'),
            'url' => route('admin.leads.mass_update'),
            'method' => 'POST',
            'options' => $this->pipeline->stages->map(fn($stage) => [
                'label' => $stage->name,
                'value' => $stage->id,
            ])->toArray(),
        ]);
    }
}
