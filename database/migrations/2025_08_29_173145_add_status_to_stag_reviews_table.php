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
        Schema::table('stag_reviews', function (Blueprint $table) {
            $table->enum('status', ['1', '0'])->default('1');
            $table->date('updated_date')->nullable();
            $table->string('status_changed_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stag_reviews', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('updated_date');
            $table->dropColumn('status_changed_by');
        });
    }
};
