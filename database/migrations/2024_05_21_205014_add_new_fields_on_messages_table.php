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
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('message_thread_id')->after('id')->constrained()->cascadeOnDelete();
            $table->dropColumn('subject');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['message_thread_id']);
            $table->dropColumn('message_thread_id');
            $table->string('subject')->after('send_from_id');
        });
    }
};
