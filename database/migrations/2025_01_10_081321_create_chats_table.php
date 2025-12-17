<?php

use App\Helpers\Constants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();

            $table->integer('is_group')->default(0);
            $table->string('type')->default(Constants::CHAT_TYPE_NORMAL);

            $table->string('last_message')->nullable();
            $table->timestamp('last_message_at')->nullable();

            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('recipient_id')->unsigned()->nullable();
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('lead_id')->unsigned()->nullable();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
