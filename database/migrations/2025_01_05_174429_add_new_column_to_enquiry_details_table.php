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
        Schema::table('enquiry_details', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id')->nullable()->after('id'); // Add package_id column
            $table->foreign('package_id')->references('id')->on('inclusive_package_details')->onDelete('set null'); // Set up the foreign key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enquiry_details', function (Blueprint $table) {
            $table->dropForeign(['package_id']); // Drop foreign key
        $table->dropColumn('package_id'); // Remove the package_id column
        });
    }
};
