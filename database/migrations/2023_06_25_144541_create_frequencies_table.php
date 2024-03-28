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
        Schema::create('gtfs_frequencies', function (Blueprint $table) {
            $table->id('trip_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('headway_secs');
            $table->boolean('exact_times')->nullable();

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
        Schema::dropIfExists('gtfs_frequencies');
    }
};
