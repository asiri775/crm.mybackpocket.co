<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop the existing foreign key constraint if it exists
            $table->dropForeign(['person_id']);

            // Modify the column to be nullable
            $table->unsignedInteger('person_id')->nullable()->change();

            // Re-add the foreign key constraint
            $table->foreign('person_id')->references(columns: 'id')->on('persons')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop the modified foreign key constraint
            $table->dropForeign(['person_id']);

            // Revert the column to not nullable
            $table->unsignedInteger('person_id')->nullable(false)->change();

            // Re-add the original foreign key constraint
            $table->foreign('person_id')->references('id')->on('persons');
        });
    }
};
