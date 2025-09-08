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
        Schema::create('program_events', function (Blueprint $table) {
            $table->id();
            $table->longText('events_package_images')->nullable();
            $table->string('cover_img')->nullable();
            $table->string('alternate_name')->nullable();
            $table->string('alternate_image_name')->nullable();
            $table->string('title')->nullable();
            $table->string('event_type')->nullable();
            $table->string('timezone')->nullable();
            $table->string('upload_image_name')->nullable();
            $table->string('start_datetime')->nullable();
            $table->string('end_datetime')->nullable();
            $table->string('location_name')->nullable();
            $table->string('location_address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('location_type')->nullable();
            $table->string('event_description')->nullable();
            $table->enum('status', ['1', '0'])->default('1');
            $table->enum('is_deleted', ['0', '1'])->default('0');
            $table->date('updated_date')->nullable();
            $table->string('status_changed_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_events');
    }
};
