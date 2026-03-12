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
        Schema::create('module_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id')
                ->nullable()
                ->constrained('permissions')
                ->onDelete('set null');
            $table->string('module');
            $table->string('is_create')->default('0');
            $table->string('is_edit')->default('0');
            $table->string('is_delete')->default('0');
            $table->string('is_list')->default('0');
            $table->string('is_view')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_permission');
    }
};
