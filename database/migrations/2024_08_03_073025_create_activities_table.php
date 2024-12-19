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
        Schema::create('activities', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('activities', 100); // Creates 'activities' column
            $table->string('status_changed_by', 100)->nullable(); // Creates 'status_changed_by' column
            $table->string('created_by', 100); // Creates 'created_by' column
            $table->enum('is_deleted', ['1', '0'])->default('0'); // Creates 'is_deleted' column
            $table->string('updated_by', 100)->nullable(); // Creates 'updated_by' column
            $table->date('updated_date')->nullable(); // Creates 'updated_date' column
            $table->enum('status', ['1', '0'])->default('0'); // Creates 'status' column
            $table->date('created_date')->nullable(); // Creates 'created_date' column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
