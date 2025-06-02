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
        Schema::create('stay_enquiry_details', function (Blueprint $table) {
            $table->id();
            $table->string('name',200)->nullable();
            $table->string('email',200)->nullable();
            $table->string('phone',100)->nullable();
            $table->longText('comments')->nullable();
            $table->string('location')->nullable();
            $table->string('stay_title')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('engagement_date')->nullable();
            $table->string('no_of_days')->nullable();
            $table->integer('total_count')->nullable();
            $table->integer('male_count')->nullable();
            $table->integer('female_count')->nullable();
            $table->integer('child_count')->nullable();
            $table->string('checkin_date')->nullable();
            $table->integer('checkout_count')->nullable();
            $table->string('cab')->nullable();
            $table->integer('price')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stay_enquiry_details');
    }
};
