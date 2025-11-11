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
        Schema::create('customer_tour_planning', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();
            $table->string('package_id')->nullable();
            $table->string('day_title')->nullable();
            $table->string('day_subtitle')->nullable();
            $table->longText('activity_description')->nullable();
            $table->string('day_order')->nullable();
            $table->timestamps();


            // Add indexes
            $table->index(['customer_id', 'package_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_tour_planning');
    }
};
