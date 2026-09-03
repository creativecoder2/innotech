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
        if (!Schema::hasTable('visitor_logs')) {
            Schema::create('visitor_logs', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address', 45)->nullable()->index();
                $table->string('session_id', 100)->nullable()->index();
                $table->date('visit_date')->index(); // for daily unique visitor counting
                $table->string('device_type', 30)->default('Desktop'); // Desktop, Mobile, Tablet
                $table->string('browser', 50)->nullable();
                $table->string('platform', 50)->nullable();
                $table->string('page_url', 255)->index();
                $table->string('page_title', 255)->nullable();
                $table->unsignedInteger('duration_seconds')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('page_analytics')) {
            Schema::create('page_analytics', function (Blueprint $table) {
                $table->id();
                $table->string('page_url', 255)->unique();
                $table->string('page_title', 255)->nullable();
                $table->unsignedBigInteger('total_views')->default(0);
                $table->unsignedBigInteger('unique_visitors')->default(0);
                $table->unsignedBigInteger('total_duration_seconds')->default(0);
                $table->unsignedInteger('avg_duration_seconds')->default(0);
                $table->timestamp('last_visited_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
        Schema::dropIfExists('page_analytics');
    }
};
