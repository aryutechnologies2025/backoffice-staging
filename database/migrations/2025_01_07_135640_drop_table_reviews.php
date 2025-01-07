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
        Schema::dropIfExists('reviews');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            // Recreate the table if needed. Define its columns here.
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('inclusive_package_details')->onDelete('cascade');
            $table->text('comment')->nullable();
            $table->integer('rating')->nullable();
        
            $table->timestamps();
        });
    }
};
