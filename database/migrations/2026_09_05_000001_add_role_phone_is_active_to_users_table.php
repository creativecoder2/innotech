<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'role')) {
                    $table->string('role')->default('Super Admin')->after('name');
                }
                if (!Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable()->unique()->after('email');
                }
                if (!Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('password');
                }
            });

            // Set existing users to active Super Admin
            DB::table('users')->whereNull('role')->orWhere('role', '')->update([
                'role' => 'Super Admin',
                'is_active' => true,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'phone')) {
                    $table->dropColumn('phone');
                }
                if (Schema::hasColumn('users', 'role')) {
                    $table->dropColumn('role');
                }
                if (Schema::hasColumn('users', 'is_active')) {
                    $table->dropColumn('is_active');
                }
            });
        }
    }
};
