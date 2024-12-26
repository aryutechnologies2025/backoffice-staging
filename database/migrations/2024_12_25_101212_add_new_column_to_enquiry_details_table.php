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
            $table->string('location')->nullable(); 
            $table->string('days')->nullable();
            $table->string('travel_destination')->nullable();
            $table->string('budget_per_head')->nullable();
            $table->string('cab_need')->nullable();
            $table->string('total_count')->nullable();
            $table->string('male_female_count')->nullable();
            $table->string('travel_date')->nullable();
            $table->string('rooms_count')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enquiry_details', function (Blueprint $table) {
            $table->dropColumn('location');
            $table->dropColumn('days');
            $table->dropColumn('travel_destination');
            $table->dropColumn('budget_per_head');
            $table->dropColumn('cab_need');
            $table->dropColumn('total_count');
            $table->dropColumn('male_female_count');
            $table->dropColumn('travel_date');
            $table->dropColumn('rooms_count');
        });
    }
};
