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
            $table->string('hosted_by')->nullable();
            $table->string('welcome_msg')->nullable();
            $table->string('embed_map')->nullable();
            $table->string('send_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_events', function (Blueprint $table) {
            $table->dropColumn('hosted_by');
            $table->dropColumn('welcome_msg');
            $table->dropColumn('embed_map');
            $table->dropColumn('send_link');
        });
    }
};
