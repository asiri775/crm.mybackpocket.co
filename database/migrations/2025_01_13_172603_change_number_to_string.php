<?php

use Illuminate\Database\Migrations\Migration;
use Webkul\Attribute\Models\Attribute;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Attribute::where('entity_type', 'warehouses')
            ->where('code', 'contact_numbers')->update(['validation' => null]);
        Attribute::where('entity_type', 'persons')
            ->where('code', 'contact_numbers')->update(['validation' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Attribute::where('entity_type', 'warehouses')
            ->where('code', 'contact_numbers')->update(['validation' => 'numeric']);
        Attribute::where('entity_type', 'persons')
            ->where('code', 'contact_numbers')->update(['validation' => 'numeric']);
    }
};
