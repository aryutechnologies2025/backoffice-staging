<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralLogsTable extends Migration
{
    public function up()
    {
        Schema::create('referral_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('influencer_id')->constrained('influencers')->onDelete('cascade');
            $table->foreignId('program_id')->nullable()->constrained('inclusive_package_details')->onDelete('set null');
            $table->string('user_ip');
            $table->string('user_agent');
            $table->string('referral_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('referral_logs');
    }
}
