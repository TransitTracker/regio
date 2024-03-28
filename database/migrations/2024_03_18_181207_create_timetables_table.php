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
        Schema::create('gtfs_timetables', function (Blueprint $table) {
            $table->id('timetable_id');
            $table->unsignedBigInteger('route_id');
            $table->boolean('direction_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('monday');
            $table->boolean('tuesday');
            $table->boolean('wednesday');
            $table->boolean('thursday');
            $table->boolean('friday');
            $table->boolean('saturday');
            $table->boolean('sunday');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('include_exceptions')->nullable();
            $table->string('timetable_label')->nullable();
            $table->string('service_notes')->nullable();
            $table->unsignedTinyInteger('orientation')->nullable();
            $table->unsignedBigInteger('timetable_page_id')->nullable();
            $table->unsignedBigInteger('timetable_sequence')->nullable();
            $table->string('direction_name')->nullable();
            $table->boolean('show_trip_continuation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
