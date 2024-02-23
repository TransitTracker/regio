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
        Schema::create('gtfs_routes', function (Blueprint $table) {
            $table->id('route_id');
            $table->unsignedInteger('agency_id');
            $table->string('route_short_name')->nullable();
            $table->string('route_long_name')->nullable();
            $table->unsignedSmallInteger('route_type');
            $table->string('route_url')->nullable();
            $table->string('route_color', 6)->nullable();
            $table->string('route_text_color', 6)->nullable();
            $table->unsignedInteger('route_sort_order');

            // Outside spec
            $table->index('agency_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
