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
        Schema::table('customer_packages', function (Blueprint $table) {
            $table->json('location')->nullable();
            $table->json('tour_planning')->nullable();
            $table->json('price_amount')->nullable();
            $table->json('payment_policy')->nullable();
            $table->longText('notes')->nullable();
            $table->json('package_inclusion')->nullable();
            $table->json('package_exclusion')->nullable();
            $table->json('amenities')->nullable();
            $table->json('food_beverages')->nullable();
            $table->json('activities')->nullable();
            $table->json('safety_features')->nullable();
            $table->enum('package_status', ['1', '0'])->default('1')->nullable();
            $table->string('list_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_packages', function (Blueprint $table) {
            $table->dropColumn('location');
            $table->dropColumn('tour_planning');
            $table->dropColumn('price_amount');
            $table->dropColumn('payment_policy');
            $table->dropColumn('notes');
            $table->dropColumn('package_inclusion');
            $table->dropColumn('package_exclusion');
            $table->dropColumn('amenities');
            $table->dropColumn('food_beverages');
            $table->dropColumn('activities');
            $table->dropColumn('safety_features');
            $table->dropColumn('status');
            $table->dropColumn('list_order');
        });
    }
};
