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
            // Check if column exists and is string type before changing
            // if (Schema::hasColumn('program_events', 'embed_map')) {
                $table->longText('embed_map')->nullable()->change();
            // }
        });
    }

    public function down(): void
    {
        Schema::table('program_events', function (Blueprint $table) {
            $table->string('embed_map')->nullable()->change();
        });
    }
};
