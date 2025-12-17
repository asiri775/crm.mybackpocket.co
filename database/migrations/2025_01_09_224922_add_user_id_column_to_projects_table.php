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

        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('project_categories', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('project_tasks', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        

        Attribute::where('entity_type', 'projects')
            ->where('code', 'category_id')->update(['type' => 'select']);

        Attribute::where('entity_type', 'projects-tasks')
            ->where('code', 'project_id')->update(['is_required' => 0]);

        $model = new Attribute([
            'entity_type' => 'projects-tasks',
            'code' => 'category_id',
            'name' => 'Category',
            'type' => 'select',
            'lookup_type' => 'project-categories',
            'sort_order' => 6,
            'is_required' => 0
        ]);

        $model->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('project_categories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('project_tasks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Attribute::where('entity_type', 'projects-tasks')
            ->where('code', 'category_id')->delete();

        Attribute::where('entity_type', 'projects')
            ->where('code', 'category_id')->update(['type' => 'lookup']);
    }
};
