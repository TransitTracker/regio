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
            $table->unsignedBigInteger('trip_id')->change();
            $table->unsignedBigInteger('stop_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gtfs_stop_times', function (Blueprint $table) {
            $table->unsignedInteger('trip_id')->change();
            $table->unsignedInteger('stop_id')->change();
        });
    }
};
