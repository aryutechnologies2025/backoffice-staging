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
        Schema::create('stay_desitinations', function (Blueprint $table) {
            $table->integer('id', true); // Creates 'id' column with auto-increment
            $table->string('city_name', 150);
            $table->string('city_image', 150);
            $table->string('upload_image_name')->nullable();
            $table->string('alternate_name')->nullable();
            $table->enum('status', ['1', '0'])->default('1');
            $table->integer('order')->nullable();
            $table->enum('is_deleted', ['1', '0'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stay_desitinations');
    }
};
