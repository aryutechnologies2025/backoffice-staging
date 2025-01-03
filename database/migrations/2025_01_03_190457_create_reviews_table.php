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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('package_id')->constrained('inclusive_package_details')->onDelete('cascade');
    $table->text('comment');
    $table->integer('rating');
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->dropColumn('user_id');
        $table->dropColumn('package_id');
        $table->dropColumn('comment');
        $table->dropColumn('rating');
        $table->dropColumn('created_at');
        $table->dropColumn('updated_at');
    }
};
