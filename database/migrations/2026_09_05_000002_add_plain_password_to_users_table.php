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
                if (!Schema::hasColumn('users', 'plain_password')) {
                    $table->string('plain_password')->nullable()->after('password');
                }
            });

            // Set default plain password for existing admin
            DB::table('users')->whereNull('plain_password')->update([
                'plain_password' => 'password123',
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
                if (Schema::hasColumn('users', 'plain_password')) {
                    $table->dropColumn('plain_password');
                }
            });
        }
    }
};
