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
        Schema::create('gtfs_agencies', function (Blueprint $table) {
            $table->id('agency_id');
            $table->string('agency_name');
            $table->string('agency_url');
            $table->string('agency_timezone');
            $table->string('agency_lang')->nullable();
            $table->string('agency_phone')->nullable();
            $table->string('agency_fare_url')->nullable();
            $table->string('agency_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
