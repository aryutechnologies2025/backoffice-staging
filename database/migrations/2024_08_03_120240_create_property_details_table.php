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
        Schema::create('property_details', function (Blueprint $table) {
            $table->integer('id', true);// Auto-incrementing ID
            $table->string('property_title', 150); // Property title
            $table->string('property_type', 150); // Property type
            $table->string('prop_cat', 150); // Property category
            $table->string('type', 150); // Type
            $table->json('city_details')->nullable(); // City details as JSON
            $table->string('state', 100); // State
            $table->string('city', 100); // City
            $table->string('address', 100); // Address
            $table->string('country', 100); // Country
            $table->json('tour_planning')->nullable(); // Tour planning as JSON
            $table->json('property_images')->nullable(); // Property images as JSON
            $table->date('start_date')->nullable(); // Start date
            $table->date('return_date')->nullable(); // Return date
            $table->string('total_days', 50); // Total days
            $table->string('total_room', 50); // Total rooms
            $table->string('member_capacity', 50); // Member capacity
            $table->string('price', 50); // Price
            $table->string('coupon_code', 50); // Coupon code
            $table->json('camp_rule')->nullable(); // Camp rule as JSON
            $table->longText('important_info')->nullable(); // Important info
            $table->json('amenity_details')->nullable();
            $table->json('food_beverages')->nullable();
            $table->json('activities')->nullable();
            $table->json('safety_features')->nullable();
            $table->enum('is_deleted', ['0', '1'])->default('0'); // Is deleted
            $table->date('created_date')->nullable(); // Created date
            $table->string('created_by', 100); // Created by
            $table->date('updated_date')->nullable(); // Updated date
            $table->string('updated_by', 100); // Updated by
            $table->enum('status', ['1', '0'])->default('1'); // Status
            $table->timestamps(); // Created at and Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_details');
    }
};
