<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            
            // $table->bigInteger('project_id')->unsigned()->nullable();
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->string('name');
            $table->string('priority');
            $table->string('status');

            $table->integer('agent_id')->unsigned()->nullable();
            $table->foreign('agent_id')->references('id')->on('persons')->onDelete('cascade');

            $table->string('cc_agents')->nullable();

            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();

            $table->longText('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_tasks');
    }
};
