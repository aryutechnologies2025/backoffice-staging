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
        $table->boolean('follow_up')->default(false)->nullable(); // Adjust as needed
    });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enquiry_details', function (Blueprint $table) {
            $table->dropColumn('follow_up');

        });
    }
};
