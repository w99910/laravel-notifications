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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['info', 'warning', 'error', 'success'])->default('info');
            $table->integer('priority')->default(5);  // lower number means higher priority
            $table->string('category')->default('inbox');
            $table->string('avatar')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->json('actions')->nullable();
            $table->integer('progress')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index(['user_id', 'read_at']);
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'priority']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
