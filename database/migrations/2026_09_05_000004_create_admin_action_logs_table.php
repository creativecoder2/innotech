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
        Schema::create('admin_action_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('admin_name')->nullable();
            $table->string('admin_role')->nullable();
            $table->string('module')->nullable(); // e.g. Products, Settings, Users, Blogs, Inquiries, Services, etc.
            $table->string('action')->nullable(); // Human readable summary of the action
            $table->string('method', 10)->default('GET'); // GET, POST, PUT, DELETE, etc.
            $table->text('url')->nullable(); // Full URI or route accessed
            $table->string('ip_address', 50)->nullable();
            $table->string('location')->nullable();
            $table->string('device_type', 50)->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('os', 100)->nullable();
            $table->string('status', 20)->default('success'); // success, failed, error
            $table->integer('status_code')->default(200); // 200, 302, 422, 500, etc.
            $table->text('error_message')->nullable(); // Detailed validation or system error
            $table->longText('request_data')->nullable(); // Sanitized input payload (JSON)
            $table->timestamps();

            // Indexes for fast querying & filtering
            $table->index('user_id');
            $table->index('module');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_action_logs');
    }
};
