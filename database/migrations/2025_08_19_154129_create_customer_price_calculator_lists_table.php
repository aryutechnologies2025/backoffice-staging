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
        Schema::create('customer_price_calculator_lists', function (Blueprint $table) {
            $table->id();
            $table->string('customer_pricing_id')->nullable()->index();
            $table->string('type_id')->nullable()->index();
            $table->string('type')->index();
            $table->string('title');
            $table->string('price_title');
            $table->string('price');
            $table->enum('is_deleted', ['0', '1'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_price_calculator_lists');
    }
};
