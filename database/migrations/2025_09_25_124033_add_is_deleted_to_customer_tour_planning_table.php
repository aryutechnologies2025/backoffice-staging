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
        Schema::table('customer_tour_planning', function (Blueprint $table) {
            $table->enum('is_deleted', ['1', '0'])->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_tour_planning', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
    }
};
