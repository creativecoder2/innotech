<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('services') && !Schema::hasColumn('services', 'is_active')) {
            Schema::table('services', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('is_featured');
            });
        }

        if (Schema::hasTable('testimonials') && !Schema::hasColumn('testimonials', 'is_active')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('order');
            });
        }

        if (Schema::hasTable('partners') && !Schema::hasColumn('partners', 'is_active')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('order');
            });
        }

        if (Schema::hasTable('blogs') && !Schema::hasColumn('blogs', 'is_published')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->boolean('is_published')->default(true)->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('services') && Schema::hasColumn('services', 'is_active')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        if (Schema::hasTable('testimonials') && Schema::hasColumn('testimonials', 'is_active')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        if (Schema::hasTable('partners') && Schema::hasColumn('partners', 'is_active')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        if (Schema::hasTable('blogs') && Schema::hasColumn('blogs', 'is_published')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropColumn('is_published');
            });
        }
    }
};
