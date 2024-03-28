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
        Schema::create('gtfs_trips', function (Blueprint $table) {
            $table->unsignedInteger('route_id');
            $table->unsignedInteger('service_id')->nullable();
            $table->id('trip_id');
            $table->string('trip_headsign')->nullable();
            $table->string('trip_short_name')->nullable();
            $table->boolean('direction_id')->nullable();
            $table->string('block_id')->nullable();
            $table->unsignedInteger('shape_id')->nullable();
            $table->unsignedTinyInteger('wheelchair_accessible')->nullable();
            $table->unsignedTinyInteger('bikes_allowed')->nullable();

            // Outside spec
            $table->unsignedInteger('agency_id');
            $table->index('agency_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gtfs_trips');
    }
};
