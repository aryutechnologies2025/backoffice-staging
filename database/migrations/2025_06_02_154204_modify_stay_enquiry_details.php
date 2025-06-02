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
         Schema::table('stay_enquiry_details', function (Blueprint $table) {
            $table->renameColumn('checkout_count', 'checkout_date');
            $table->string('checkout_count')->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stay_enquiry_details', function (Blueprint $table) {
           $table->renameColumn('checkout_date', 'checkout_count');
        });
       
    }
};
