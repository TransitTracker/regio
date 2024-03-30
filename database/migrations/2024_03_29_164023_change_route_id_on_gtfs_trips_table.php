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
        Schema::table('gtfs_trips', function (Blueprint $table) {
            $table->unsignedBigInteger('route_id')->change();
            $table->unsignedBigInteger('service_id')->change();
            $table->unsignedBigInteger('shape_id')->change();
            $table->unsignedBigInteger('agency_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gtfs_trips', function (Blueprint $table) {
            $table->unsignedInteger('route_id')->change();
            $table->unsignedInteger('service_id')->change();
            $table->unsignedInteger('shape_id')->change();
            $table->unsignedInteger('agency_id')->change();
        });
    }
};
