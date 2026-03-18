<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inclusive_package_details', function (Blueprint $table) {
            if (!Schema::hasColumn('inclusive_package_details', 'program_inclusion')) {
                $table->longText('program_inclusion')->nullable();
            }
            if (!Schema::hasColumn('inclusive_package_details', 'program_exclusion')) {
                $table->longText('program_exclusion')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('inclusive_package_details', function (Blueprint $table) {
            $table->dropColumn(['program_inclusion', 'program_exclusion']);
        });
    }
};
