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
        Schema::table('customer_pricing_calculators', function (Blueprint $table) {
            $table->string('selected_value')->nullable();
            $table->string('service_fee')->nullable();
            $table->string('tax_amount')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('grand_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_pricing_calculators', function (Blueprint $table) {
            $table->dropColumn('selected_value');
            $table->dropColumn('service_fee');
            $table->dropColumn('tax_amount');
            $table->dropColumn('total_amount');
            $table->dropColumn('grand_total');
        });
    }
};
