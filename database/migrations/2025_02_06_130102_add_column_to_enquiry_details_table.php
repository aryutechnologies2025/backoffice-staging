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
        Schema::table('enquiry_details', function (Blueprint $table) {
            $table->string('engagement_date')->nullable();
            $table->string('birth_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enquiry_details', function (Blueprint $table) {
            $table->dropColumn('engagement_date');
            $table->dropColumn('birth_date');
        });
    }
};
