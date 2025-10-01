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
        Schema::table('program_events', function (Blueprint $table) {
            $table->dropColumn('events_package_images');
            $table->dropColumn('event_type');
            $table->dropColumn('timezone');
            $table->dropColumn('title');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('location_type');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_events', function (Blueprint $table) {
            $table->longText('events_package_images')->nullable();
            $table->string('event_type')->nullable();
            $table->string('timezone')->nullable();
            $table->string('title')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('location_type')->nullable();
        });
    }
};
