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
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('dob')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_province_code')->nullable();
            $table->string('country')->nullable();
            $table->string('preferred_lang')->nullable();
            $table->string('newsletter_sub')->nullable();
            $table->string('terms_condition')->nullable();
            $table->string('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'profile_image',
                'dob',
                'street',
                'city',
                'state',
                'zip_province_code',
                'country',
                'preferred_lang',
                'newsletter_sub',
                'terms_condition',
                'created_by'
            ]);
        });
    }
};
