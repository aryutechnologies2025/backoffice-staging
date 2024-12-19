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
        Schema::create('city_details', function (Blueprint $table) {
            $table->integer('id', true); // Creates 'id' column with auto-increment
            $table->string('city_name', 150); // Creates 'city_name' column
            $table->enum('status', ['1', '0'])->default('1'); // Creates 'status' column with default value
            $table->enum('is_deleted', ['1', '0'])->default('0'); // Creates 'is_deleted' column with default value
            $table->date('created_date')->nullable(); // Creates 'created_date' column with default current date
            $table->string('created_by', 100)->nullable(); // Creates 'created_by' column, nullable
            $table->string('updated_by', 100)->nullable(); // Creates 'updated_by' column, nullable
            $table->string('status_changed_by', 100)->nullable(); // Creates 'status_changed_by' column, nullable
            $table->string('deleted_by', 100)->nullable(); // Creates 'deleted_by' column, nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_details');
    }
};
