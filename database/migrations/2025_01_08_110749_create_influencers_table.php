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
        Schema::create('influencers', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id')->unique();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('gender')->nullable();
            $table->string('age')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            //Instagram Details
            $table->string('instagram_name')->nullable();
            $table->string('instagram_profile_link')->nullable();
            $table->string('instagram_followers_count')->nullable();
            $table->string('instagram_category')->nullable();
            //Linkedin Details
            $table->string('linkedin_name')->nullable();
            $table->string('linkedin_profile_link')->nullable();
            $table->string('linkedin_followers_count')->nullable();
            $table->string('linkedin_category')->nullable();
            //youtube Details
            $table->string('youtube_name')->nullable();
            $table->string('youtube_profile_link')->nullable();
            $table->string('youtube_followers_count')->nullable();
            $table->string('youtube_category')->nullable();
            //Facebook Details
            $table->string('facebook_name')->nullable();
            $table->string('facebook_profile_link')->nullable();
            $table->string('facebook_followers_count')->nullable();
            $table->string('facebook_category')->nullable();
            //Twitter Details
            $table->string('twitter_name')->nullable();
            $table->string('twitter_profile_link')->nullable();
            $table->string('twitter_followers_count')->nullable();
            $table->string('twitter_category')->nullable();
            //details
            $table->string('created_by')->nullable();
            $table->enum('status', ['1', '0'])->default('1'); // Creates 'status' column with default value
            $table->enum('is_deleted', ['1', '0'])->default('0'); // Creates 'is_deleted' column with default value

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('influencers');
    }
};
