<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Webkul\Attribute\Models\Attribute;

return new class extends Migration {

    public $attributes = [
        [
            'entity_type' => 'projects-categories',
            'code' => 'name',
            'name' => 'Name',
            'type' => 'text',
            'lookup_type' => null,
            'sort_order' => 1,
            'is_required' => 1
        ],

        [
            'entity_type' => 'projects',
            'code' => 'project_id',
            'name' => 'Project ID',
            'type' => 'text',
            'lookup_type' => null,
            'sort_order' => 1,
            'is_required' => 1
        ],
        [
            'entity_type' => 'projects',
            'code' => 'name',
            'name' => 'Name',
            'type' => 'text',
            'lookup_type' => null,
            'sort_order' => 2,
            'is_required' => 1
        ],
        [
            'entity_type' => 'projects',
            'code' => 'client_id',
            'name' => 'Client',
            'type' => 'lookup',
            'lookup_type' => 'persons',
            'sort_order' => 3,
            'is_required' => 1
        ],
        [
            'entity_type' => 'projects',
            'code' => 'start_date',
            'name' => 'Start Date',
            'type' => 'date',
            'lookup_type' => null,
            'sort_order' => 4,
            'is_required' => 1
        ],
        [
            'entity_type' => 'projects',
            'code' => 'deadline',
            'name' => 'Deadline',
            'type' => 'date',
            'lookup_type' => null,
            'sort_order' => 5
        ],
        [
            'entity_type' => 'projects',
            'code' => 'category_id',
            'name' => 'Category',
            'type' => 'lookup',
            'lookup_type' => 'project-categories',
            'sort_order' => 6,
            'is_required' => 1
        ],
        [
            'entity_type' => 'projects',
            'code' => 'phases',
            'name' => 'Phases',
            'type' => 'text',
            'lookup_type' => null,
            'sort_order' => 7
        ],
        [
            'entity_type' => 'projects',
            'code' => 'description',
            'name' => 'Description',
            'type' => 'textarea',
            'lookup_type' => null,
            'sort_order' => 8
        ],


        [
            'entity_type' => 'projects-tasks',
            'code' => 'project_id',
            'name' => 'Project',
            'type' => 'lookup',
            'lookup_type' => 'projects',
            'sort_order' => 1,
            'is_required' => 1
        ],
        [
            'entity_type' => 'projects-tasks',
            'code' => 'name',
            'name' => 'Name',
            'type' => 'text',
            'lookup_type' => null,
            'sort_order' => 2,
            'is_required' => 1
        ],
        [
            'entity_type' => 'projects-tasks',
            'code' => 'priority',
            'name' => 'Priority',
            'type' => 'dropdown',
            'field_items' => '[{"value":"low","label":"Low"},{"value":"medium","label":"Medium"},{"value":"high","label":"High"}]',
            'lookup_type' => null,
            'sort_order' => 3,
            'is_required' => 1
        ],
        [
            'entity_type' => 'projects-tasks',
            'code' => 'status',
            'name' => 'Status',
            'type' => 'dropdown',
            'field_items' => '[{"value":"open","label":"Open"},{"value":"in-progress","label":"In Progress"},{"value":"awaiting-approval","label":"Awaiting Approval"},{"value":"closes","label":"Closed"}]',
            'lookup_type' => null,
            'sort_order' => 4,
            'is_required' => 1
        ],
        [
            'entity_type' => 'projects-tasks',
            'code' => 'agent_id',
            'name' => 'Agent',
            'type' => 'lookup',
            'lookup_type' => 'persons',
            'sort_order' => 5,
            'is_required' => 1
        ],
        [
            'entity_type' => 'projects-tasks',
            'code' => 'cc_agents',
            'name' => 'CC Agents',
            'type' => 'lookup',
            'lookup_type' => 'persons',
            'sort_order' => 6
        ],
        [
            'entity_type' => 'projects-tasks',
            'code' => 'start_date',
            'name' => 'Start Date',
            'type' => 'date',
            'lookup_type' => null,
            'sort_order' => 7
        ],
        [
            'entity_type' => 'projects-tasks',
            'code' => 'deadline',
            'name' => 'Deadline',
            'type' => 'date',
            'lookup_type' => null,
            'sort_order' => 8
        ],
        [
            'entity_type' => 'projects-tasks',
            'code' => 'description',
            'name' => 'Description',
            'type' => 'textarea',
            'lookup_type' => null,
            'sort_order' => 9
        ]
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->attributes as $attrItem) {
            $model = new Attribute($attrItem);
            $model->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->attributes as $attrItem) {
            Attribute::where('entity_type', $attrItem['entity_type'])
                ->where('code', $attrItem['code'])
                ->delete();
        }
    }
};
