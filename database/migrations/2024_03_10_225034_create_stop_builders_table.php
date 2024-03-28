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
        Schema::create('helper_stop_builders', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('stop_id');
            $table->unsignedTinyInteger('stop_type');
            $table->string('stop_city')->nullable();
            $table->unsignedBigInteger('main_street_id')->nullable();
            $table->unsignedBigInteger('cross_street_id')->nullable();
            $table->string('place_name')->nullable();
            $table->string('facing_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('helper_stop_builders');
    }
};
