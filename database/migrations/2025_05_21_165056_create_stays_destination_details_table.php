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
        Schema::create('stays_destination_details', function (Blueprint $table) {
            $table->id();
            $table->string('destination', 150);
            $table->string('stay_title', 150);
            $table->longText('stay_description');
            $table->longText('stay_location');
            $table->longText('gallery_image');
            $table->integer('price');
            $table->integer('no_of_days');
            $table->json('amenity_details')->nullable();
            $table->json('food_beverages')->nullable();
            $table->json('activities')->nullable();
            $table->json('safety_features')->nullable();
            $table->enum('status', ['1', '0'])->default('1');
            $table->integer('order')->nullable();
            $table->enum('is_deleted', ['0', '1'])->default('0');
            $table->date('created_date')->nullable();
            $table->string('created_by', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stays_destination_details');
    }
};
