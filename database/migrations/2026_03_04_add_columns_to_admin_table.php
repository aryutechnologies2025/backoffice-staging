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
        Schema::table('admin', function (Blueprint $table) {
            if (!Schema::hasColumn('admin', 'first_name')) {
                $table->string('first_name', 100)->nullable()->after('name');
            }
            if (!Schema::hasColumn('admin', 'last_name')) {
                $table->string('last_name', 100)->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('admin', 'profile_pic')) {
                $table->string('profile_pic')->nullable()->after('password');
            }
            if (!Schema::hasColumn('admin', 'status')) {
                $table->string('status', 1)->default('1')->after('profile_pic');
            }
            if (!Schema::hasColumn('admin', 'created_by')) {
                $table->string('created_by')->nullable()->after('status');
            }
            if (!Schema::hasColumn('admin', 'is_deleted')) {
                $table->string('is_deleted', 1)->default('0')->after('created_by');
            }
            if (!Schema::hasColumn('admin', 'role_id')) {
                $table->integer('role_id')->nullable()->after('is_deleted');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'profile_pic', 'status', 'created_by', 'is_deleted', 'role_id']);
        });
    }
};
