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
        Schema::create('gtfs_stops', function (Blueprint $table) {
            $table->id('stop_id');
            $table->string('stop_code')->nullable();
            $table->string('stop_name')->nullable();
            $table->point('stop_position');
            $table->unsignedInteger('zone_id')->nullable();
            $table->unsignedTinyInteger('weelchair_boarding')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stops');
    }
};
