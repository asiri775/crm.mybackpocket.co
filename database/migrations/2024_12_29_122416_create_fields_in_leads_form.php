<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Webkul\Attribute\Models\Attribute;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Attribute::where('entity_type', 'leads')
            ->where('code', 'lead_value')
            ->update(['is_required' => 0]);


        $model0 = new Attribute([
            'code' => 'space_id',
            'name' => 'Space',
            'type' => 'lookup',
            'lookup_type' => 'spaces',
            'entity_type' => 'leads',
            'sort_order' => 11,
            'quick_add' => 1
        ]);
        $model0->save();

        $model1 = new Attribute([
            'code' => 'start_date',
            'name' => 'Start Date',
            'type' => 'date',
            'entity_type' => 'leads',
            'sort_order' => 12,
            'quick_add' => 1
        ]);
        $model1->save();

        $model2 = new Attribute([
            'code' => 'start_time',
            'name' => 'Start Time',
            'type' => 'time',
            'entity_type' => 'leads',
            'sort_order' => 13,
            'quick_add' => 1
        ]);
        $model2->save();

        $model3 = new Attribute([
            'code' => 'end_date',
            'name' => 'End Date',
            'type' => 'date',
            'entity_type' => 'leads',
            'sort_order' => 14,
            'quick_add' => 1
        ]);
        $model3->save();

        $model4 = new Attribute([
            'code' => 'end_time',
            'name' => 'End Time',
            'type' => 'time',
            'entity_type' => 'leads',
            'sort_order' => 15,
            'quick_add' => 1
        ]);
        $model4->save();

        $model5 = new Attribute([
            'code' => 'booking_type',
            'name' => 'Booking Type',
            'type' => 'text',
            'entity_type' => 'leads',
            'sort_order' => 16,
            'quick_add' => 1
        ]);
        $model5->save();

        Schema::table('attribute_values', function (Blueprint $table) {
            $table->time('time_value')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Attribute::where('entity_type', 'leads')
            ->where('code', 'lead_value')
            ->update(['is_required' => 1]);

        Attribute::where('entity_type', 'leads')
            ->whereIn('code', [
                'start_date',
                'end_date',
                'start_time',
                'end_time',
                'booking_type',
                'space_id'
            ])
            ->delete();

        Schema::table('attribute_values', function (Blueprint $table) {
            $table->dropColumn('time_value');
        });
    }
};
