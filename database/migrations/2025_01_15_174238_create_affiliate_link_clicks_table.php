<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateLinkClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_link_clicks', function (Blueprint $table) {
            $table->id(); // Auto-incremented primary key
            $table->unsignedBigInteger('program_id'); // Program ID
            $table->string('reference_id'); // Affiliate reference ID
            $table->unsignedBigInteger('influencer_id'); // Influencer ID linking the click
            $table->timestamp('clicked_at')->nullable(); // Time the link was clicked

            $table->string('ip_address')->nullable(); // User's IP address
            $table->string('user_agent')->nullable(); // Browser/Device details
            $table->timestamps(); // created_at and updated_at columns
            $table->foreign('influencer_id')->references('id')->on('Influencers')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliate_link_clicks');
    }
}

