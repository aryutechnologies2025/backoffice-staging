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
        Schema::create('admin', function (Blueprint $table) {
            $table->integer('id', true);// Creates an 'id' column with auto-increment
            $table->string('name', 100); // Creates 'name' column
            $table->string('email', 100)->unique(); // Creates 'email' column with unique constraint
            $table->string('phone', 100); // Creates 'phone' column
            $table->string('password', 255); // Creates 'password' column
            $table->timestamps(); // Creates 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
