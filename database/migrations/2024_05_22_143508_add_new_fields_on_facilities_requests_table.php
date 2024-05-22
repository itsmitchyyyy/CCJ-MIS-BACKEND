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
        Schema::table('request_facilities', function(Blueprint $table) {
            $table->integer('quantity')->nullable()->after('equipmentStatus');
            $table->date('borrow_end_date')->nullable()->after('borrowed_date');
            $table->time('reservation_end_time')->nullable()->after('reservation_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_facilities', function(Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('borrow_end_date');
            $table->dropColumn('reservation_end_time');
        });
    }
};
