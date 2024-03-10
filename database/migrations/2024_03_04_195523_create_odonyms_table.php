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
        Schema::create('helper_odonyms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topo_id')->unique();
            $table->string('toponym');
            $table->string('type');
            $table->string('municipality');
            $table->string('specific');
            $table->string('generic')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('helper_odonyms');
    }
};
