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
        Schema::create('pricing_calculators', function (Blueprint $table) {
            $table->id();
            $table->string('destination_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('stay_price')->nullable();
            $table->string('cab_price')->nullable();
            $table->string('activity_price')->nullable();
            $table->string('total_pricing')->nullable();
            $table->enum('status', ['1', '0'])->default('1');
            $table->enum('is_deleted', ['0', '1'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_calculators');
    }
};
