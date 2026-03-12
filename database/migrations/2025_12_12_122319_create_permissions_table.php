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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')
                ->nullable()
                ->constrained('roles')   // references id on roles table
                ->onDelete('set null');  // optional
            $table->string('created_by');
            $table->enum('status', ['1', '0'])->default('1');
            $table->enum('is_deleted', ['0', '1'])->default('0');
            $table->string('created_date');
            $table->string('updated_by')->nullable();
            $table->string('status_changed_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
