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
        Schema::table('subject_students', function (Blueprint $table) {
            $table->string('grade')->nullable()->default(json_encode(['prelim' => 'NG', 'midterm' => 'NG', 'semifinal' => 'NG', 'final' => 'NG']))->after('subject_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_students', function (Blueprint $table) {
            $table->dropColumn('grade');
        });
    }
};
