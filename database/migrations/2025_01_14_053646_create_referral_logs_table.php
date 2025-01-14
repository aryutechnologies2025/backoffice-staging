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
        Schema::create('referral_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('influencer_id');
            $table->string('user_ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->unsignedBigInteger('program_id')->nullable();
            $table->timestamps();
            $table->foreign('influencer_id')->references('id')->on('influencers')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('inclusive_package_details')->onDelete('set null'); // Assuming there's a programs table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_logs');
    }
};
