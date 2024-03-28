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
        Schema::table('gtfs_stop_times', function (Blueprint $table) {
            $table->renameColumn('pickup_time', 'pickup_type');
            $table->renameColumn('drop_off_time', 'drop_off_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gtfs_stop_times', function (Blueprint $table) {
            $table->renameColumn('pickup_type', 'pickup_time');
            $table->renameColumn('drop_off_type', 'drop_off_time');
            //
        });
    }
};
