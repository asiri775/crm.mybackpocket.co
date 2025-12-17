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
        Attribute::where('entity_type', 'projects-tasks')
            ->where('code', 'agent_id')->update(['is_required' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Attribute::where('entity_type', 'projects-tasks')
            ->where('code', 'agent_id')->update(['is_required' => 1]);
    }
};
