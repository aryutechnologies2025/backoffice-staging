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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('leader_name')->nullable();
            $table->string('leader_contact')->nullable();
            $table->string('gpay_number')->nullable();
            $table->string('ac_name')->nullable();
            $table->string('ac_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('leader_name');
            $table->dropColumn('leader_contact');
            $table->dropColumn('gpay_number');
            $table->dropColumn('ac_name');
            $table->dropColumn('ac_number');
            $table->dropColumn('ifsc_code');
            $table->dropColumn('bank_name');
            $table->dropColumn('branch_name');
        });
    }
};
