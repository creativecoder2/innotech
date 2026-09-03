<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('image');
            $table->string('banner_subtitle')->nullable()->after('banner_image');
            $table->string('image_2')->nullable()->after('banner_subtitle');
            $table->string('process_title')->nullable()->after('image_2');
            $table->longText('features')->nullable()->after('description');
            $table->string('steps_title')->nullable()->after('features');
            $table->text('steps_description')->nullable()->after('steps_title');
            $table->string('step_1_title')->nullable()->after('steps_description');
            $table->text('step_1_points')->nullable()->after('step_1_title');
            $table->string('step_2_title')->nullable()->after('step_1_points');
            $table->text('step_2_points')->nullable()->after('step_2_title');
            $table->string('step_3_title')->nullable()->after('step_2_points');
            $table->text('step_3_points')->nullable()->after('step_3_title');
            $table->string('step_4_title')->nullable()->after('step_3_points');
            $table->text('step_4_points')->nullable()->after('step_4_title');
            $table->string('research_title')->nullable()->after('step_4_points');
            $table->text('research_description')->nullable()->after('research_title');
            $table->string('research_image')->nullable()->after('research_description');
            $table->string('bottom_link_text')->nullable()->after('research_image');
            $table->string('bottom_link_url')->nullable()->after('bottom_link_text');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'banner_image',
                'banner_subtitle',
                'image_2',
                'process_title',
                'features',
                'steps_title',
                'steps_description',
                'step_1_title',
                'step_1_points',
                'step_2_title',
                'step_2_points',
                'step_3_title',
                'step_3_points',
                'step_4_title',
                'step_4_points',
                'research_title',
                'research_description',
                'research_image',
                'bottom_link_text',
                'bottom_link_url'
            ]);
        });
    }
};
