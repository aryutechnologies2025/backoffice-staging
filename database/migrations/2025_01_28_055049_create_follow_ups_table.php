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
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enquiry_id')->index();
            $table->foreign('enquiry_id')->references('id')->on('enquiry_details')->onDelete('cascade');
            $table->date('follow_up_date');
            $table->string('interest_prospect');
            $table->string('lead_source');
            $table->string('lead_status');
            $table->text('follow_up_notes');
            $table->string('action_required');
            $table->decimal('deal_value', 10, 2);
            $table->string('assigned_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
