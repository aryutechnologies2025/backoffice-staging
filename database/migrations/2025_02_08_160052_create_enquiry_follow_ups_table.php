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
        Schema::create('enquiry_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enquiry_id')->index();
            $table->foreign('enquiry_id')->references('id')->on('enquiry_details')->onDelete('cascade');            
            $table->string('customer_name')->nullable();
            $table->string('customer_location')->nullable();
            $table->string('event_name')->nullable();
            $table->string('no_of_persons')->nullable();
            $table->string('transportation_mode')->nullable();
            $table->string('travel_date_time')->nullable();
            $table->string('booking_date')->nullable();
            $table->string('travel_start_date')->nullable();
            $table->string('travel_end_date')->nullable();
            $table->string('return_mode')->nullable();
            $table->string('return_travel_date_time')->nullable();
            $table->string('bus_service')->nullable();
            $table->string('bus_status')->nullable();
            $table->string('bus_travel_date_time')->nullable();
            $table->string('cab_pickup')->nullable();
            $table->string('cab_travel_date_time')->nullable();
            $table->string('program_details')->nullable();
            $table->string('special_occasion')->nullable();
            $table->string('stay_list')->nullable();
            $table->string('property_name')->nullable();
            $table->string('cab_service')->nullable();
            $table->string('trip_status')->nullable();
            $table->boolean('is_delected')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiry_follow_ups');
    }
};
