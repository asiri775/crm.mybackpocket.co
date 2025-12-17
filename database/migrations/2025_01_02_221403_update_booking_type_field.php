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
        Schema::table('attributes', function (Blueprint $table) {
            $table->longText('field_items')->nullable();
        });

        Attribute::where('entity_type', 'leads')
            ->where('code', 'booking_type')
            ->update([
                'type' => 'dropdown',
                'field_items' => '[{"value":"hourly","label":"Hourly"},{"value":"daily","label":"Daily"},{"value":"weekly","label":"Weekly"},{"value":"monthly","label":"Monthly"}]'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->integer('field_items')->nullable();
        });
        Attribute::where('entity_type', 'leads')
            ->where('code', 'booking_type')
            ->update([
                'type' => 'text'
            ]);
    }
};
