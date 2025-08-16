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
        Schema::create('price_calculator_lists', function (Blueprint $table) {
            $table->id();
            $table->string('pricing_calculator_id')->nullable();
            $table->string('type_id')->nullable();
            $table->string('type');
            $table->string('title');
            $table->string('price_title');
            $table->string('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_calculator_lists');
    }
};
