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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('to_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('send_from_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['read', 'unread'])->default('unread');
            $table->enum('type', ['inbox', 'sent'])->default('inbox');
            $table->string('attachment')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->dateTime('sent_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
