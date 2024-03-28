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
        Schema::create('gtfs_services', function (Blueprint $table) {
            $table->id('service_id');
            $table->boolean('monday');
            $table->boolean('tuesday');
            $table->boolean('wednesday');
            $table->boolean('thursday');
            $table->boolean('friday');
            $table->boolean('saturday');
            $table->boolean('sunday');
            $table->date('start_date');
            $table->date('end_date');

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
        Schema::dropIfExists('gtfs_services');
    }
};
