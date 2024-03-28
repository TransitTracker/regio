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
        Schema::table('gtfs_stops', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_station')->nullable();
            $table->unsignedTinyInteger('location_type')->nullable();
            $table->string('platform_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gtfs_stops', function (Blueprint $table) {
            $table->dropColumn(['parent_station', 'location_type', 'platform_code']);
        });
    }
};
