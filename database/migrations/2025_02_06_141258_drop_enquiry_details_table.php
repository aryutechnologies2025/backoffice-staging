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
        Schema::dropIfExists('enquiry_details');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('enquiry_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->longText('comments')->nullable();
            $table->string('location')->nullable();
            $table->string('days')->nullable();
            $table->string('travel_destination')->nullable();
            $table->string('budget_per_head')->nullable();
            $table->string('cab_need')->default(false);
            $table->string('total_count')->nullable();
            $table->string('male_count')->nullable();
            $table->string('female_count')->nullable();
            $table->string('travel_date')->nullable();
            $table->string('rooms_count')->nullable();
            $table->string('child_count')->nullable();
            $table->string('child_age')->nullable();
            $table->boolean('followup')->nullable()->default(false);
        });
    }
};
