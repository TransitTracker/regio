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
        Schema::table('gtfs_agencies', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable();
            $table->string('default_color', 6)->nullable();
            $table->string('default_text_color', 6)->nullable();
            $table->string('logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gtfs_agencies', function (Blueprint $table) {
            $table->dropColumn(['slug', 'default_color', 'default_text_color', 'logo']);
        });
    }
};
