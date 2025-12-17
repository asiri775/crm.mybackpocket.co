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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_id')->unique();
            $table->string('name');

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('persons')->onDelete('cascade');

            $table->date('start_date');
            $table->date('deadline')->nullable();

            $table->bigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('project_categories')->onDelete('cascade');

            $table->string('phases')->nullable();
            $table->longText('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
