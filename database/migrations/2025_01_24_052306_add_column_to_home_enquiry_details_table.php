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
        Schema::table('home_enquiry_details', function (Blueprint $table) {
            $table->string('child_count')->nullable();
            $table->string('child_age')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_enquiry_details', function (Blueprint $table) {
            $table->dropColumn('child_count');
            $table->dropColumn('child_age');
        });
    }
};
