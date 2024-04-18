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
        Schema::table('request_facilities', function (Blueprint $table) {
            $table->date('borrowed_date')->nullable()->after('reservation_date');
            $table->date('returned_date')->nullable()->after('borrowed_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_facilities', function (Blueprint $table) {
            $table->dropColumn('borrowed_date');
            $table->dropColumn('returned_date');
        });
    }
};
