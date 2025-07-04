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
             $table->unsignedBigInteger('stay_details_id')->index();
            $table->foreign('stay_details_id')->references('id')->on('stays_destination_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_packages', function (Blueprint $table) {
            $table->dropForeign(['stay_details_id']);
            $table->dropColumn('stay_details_id');
        });
    }
};
