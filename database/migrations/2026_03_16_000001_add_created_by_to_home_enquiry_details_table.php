<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_enquiry_details', function (Blueprint $table) {
            $table->string('created_by')->nullable()->after('comments');
            $table->string('program_title')->nullable()->after('created_by');
        });
    }

    public function down(): void
    {
        Schema::table('home_enquiry_details', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'program_title']);
        });
    }
};
