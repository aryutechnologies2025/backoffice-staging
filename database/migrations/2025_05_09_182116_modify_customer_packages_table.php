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
            // Rename columns
           
       
        $table->renameColumn('notes', 'important_info');
            
        // Add new column
        $table->json('camp_rule')->nullable();
        $table->json('price_title')->nullable();
    }); 
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_packages', function (Blueprint $table) {
            $table->renameColumn('important_info', 'notes');
            $table->dropColumn('camp_rule');
            $table->string('price_title');
        });
    }
};
