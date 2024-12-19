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
        Schema::create('amenities', function (Blueprint $table) {
            $table->integer('id', true);// Creates an 'id' column with auto-increment
            $table->string('amenity_name', 150); // Creates 'amenity_name' column
            $table->date('created_date')->nullable();// Creates 'created_date' column with default current date
            $table->string('created_by', 100); // Creates 'created_by' column
            $table->enum('status', ['1', '0'])->default('1');
            $table->enum('is_deleted', ['1', '0'])->default('0'); // Creates 'is_deleted' column with default value
            $table->timestamps(); // Creates 'created_at' and 'updated_at' columns
            $table->string('status_changed_by', 100)->nullable(); // Creates 'status_changed_by' column, nullable
            $table->date('updated_date')->nullable(); // Creates 'updated_date' column, nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
