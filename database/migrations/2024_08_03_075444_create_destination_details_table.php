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
        Schema::create('destination_details', function (Blueprint $table) {
            $table->integer('id', true);// Creates 'id' column with auto-increment
            $table->string('destination_name', 150); // Creates 'destination_name' column
            $table->string('place', 150); // Creates 'place' column
            $table->string('destination_pic', 255)->nullable(); // Creates 'destination_pic' column, nullable
            $table->enum('status', ['1', '0'])->default('1'); // Creates 'status' column with default value
            $table->date('created_date')->nullable(); // Creates 'created_date' column with default current date
            $table->string('created_by', 100); // Creates 'created_by' column
            $table->enum('is_deleted', ['1', '0'])->default('0'); // Creates 'is_deleted' column with default value
            $table->timestamps(); // Creates 'created_at' and 'updated_at' columns
            $table->string('updated_by', 100)->nullable(); // Creates 'updated_by' column, nullable
            $table->date('updated_date')->nullable(); // Creates 'updated_date' column, nullable
            $table->string('status_changed_by', 100)->nullable(); // Creates 'status_changed_by' column, nullable
            $table->string('deleted_by', 100)->nullable(); // Creates 'deleted_by' column, nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destination_details');
    }
};
