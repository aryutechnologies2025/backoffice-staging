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
        Schema::create('home_enquiry_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('comments');
            $table->string('location');
            $table->string('days');
            $table->string('travel_destination');
            $table->string('budget_per_head');
            $table->string('cab_need');
            $table->string('total_count');
            $table->string('male_count');
            $table->string('female_count');
            $table->string('travel_date');
            $table->string('rooms_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_enquiry_details');
    }
};
