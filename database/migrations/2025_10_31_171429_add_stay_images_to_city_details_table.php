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
        Schema::table('city_details', function (Blueprint $table) {
            $table->string('stay_images')->nullable();
            $table->string('stay_alternate_name')->nullable();
            $table->string('stay_upload_image_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('city_details', function (Blueprint $table) {
            $table->dropColumn([
                'stay_images',
                'stay_alternate_name', 
                'stay_upload_image_name'
            ]);
        });
    }
};
