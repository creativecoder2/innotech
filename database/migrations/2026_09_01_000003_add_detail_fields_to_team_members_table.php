<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            if (!Schema::hasColumn('team_members', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (!Schema::hasColumn('team_members', 'expertise')) {
                $table->string('expertise')->nullable()->after('designation');
            }
            if (!Schema::hasColumn('team_members', 'experience')) {
                $table->string('experience')->nullable()->after('expertise');
            }
            if (!Schema::hasColumn('team_members', 'email')) {
                $table->string('email')->nullable()->after('experience');
            }
            if (!Schema::hasColumn('team_members', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('team_members', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('facebook_url');
            }
            if (!Schema::hasColumn('team_members', 'pinterest_url')) {
                $table->string('pinterest_url')->nullable()->after('instagram_url');
            }
            if (!Schema::hasColumn('team_members', 'personal_experience')) {
                $table->longText('personal_experience')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('team_members', 'skills')) {
                $table->text('skills')->nullable()->after('personal_experience');
            }
            if (!Schema::hasColumn('team_members', 'education')) {
                $table->text('education')->nullable()->after('skills');
            }
            if (!Schema::hasColumn('team_members', 'awards')) {
                $table->text('awards')->nullable()->after('education');
            }
        });
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'expertise',
                'experience',
                'email',
                'phone',
                'instagram_url',
                'pinterest_url',
                'personal_experience',
                'skills',
                'education',
                'awards'
            ]);
        });
    }
};
