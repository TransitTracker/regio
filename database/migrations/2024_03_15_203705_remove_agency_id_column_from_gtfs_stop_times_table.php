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
        Schema::table('gtfs_stop_times', function (Blueprint $table) {
            $table->dropColumn('agency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gtfs_stop_times', function (Blueprint $table) {
            $table->unsignedInteger('agency_id');
            $table->index('agency_id');
        });
    }
};
