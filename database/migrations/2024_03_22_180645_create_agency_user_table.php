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
        Schema::create('agency_user', function (Blueprint $table) {
            $table->id();
            $table->string('role')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('agency_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_user');
    }
};
