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
        if (!Schema::hasTable('admin_login_logs')) {
            Schema::create('admin_login_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('session_id')->nullable()->index();
                $table->string('email_or_phone')->nullable();
                $table->string('ip_address', 50)->nullable();
                $table->string('location')->nullable();
                $table->string('device_type', 30)->nullable(); // Desktop, Mobile, Tablet
                $table->string('os', 50)->nullable();          // Windows, macOS, iOS, Android, Linux
                $table->string('browser', 50)->nullable();     // Chrome, Edge, Safari, Firefox
                $table->text('user_agent')->nullable();
                $table->string('login_method', 20)->default('Email'); // Email or Phone
                $table->string('status', 20)->default('success');     // success, failed, logged_out, revoked
                $table->boolean('is_active_session')->default(true);
                $table->timestamp('last_activity_at')->nullable()->index();
                $table->timestamp('logged_out_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_login_logs');
    }
};
