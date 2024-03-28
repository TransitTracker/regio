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
        Schema::create('gtfs_stop_attributes', function (Blueprint $table) {
            // Nullable for Filament purpose (https://filamentphp.com/docs/3.x/forms/advanced#saving-data-to-a-belongsto-relationship)
            $table->unsignedInteger('stop_id')->nullable();
            $table->string('stop_city')->nullable();

            // Outside spec
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gtfs_stop_attributes');
    }
};
