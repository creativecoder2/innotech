<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'tags')) {
                $table->string('tags')->nullable()->after('category');
            }
            if (!Schema::hasColumn('blogs', 'views')) {
                $table->unsignedBigInteger('views')->default(0)->after('status');
            }
            if (!Schema::hasColumn('blogs', 'video_url')) {
                $table->string('video_url')->nullable()->after('image');
            }
            if (!Schema::hasColumn('blogs', 'slider_images')) {
                $table->text('slider_images')->nullable()->after('video_url');
            }
            if (!Schema::hasColumn('blogs', 'quote')) {
                $table->text('quote')->nullable()->after('content');
            }
            if (!Schema::hasColumn('blogs', 'quote_author')) {
                $table->string('quote_author')->nullable()->after('quote');
            }
            if (!Schema::hasColumn('blogs', 'approach_title')) {
                $table->string('approach_title')->nullable()->after('quote_author');
            }
            if (!Schema::hasColumn('blogs', 'approach_text')) {
                $table->text('approach_text')->nullable()->after('approach_title');
            }
            if (!Schema::hasColumn('blogs', 'approach_points')) {
                $table->text('approach_points')->nullable()->after('approach_text');
            }
            if (!Schema::hasColumn('blogs', 'meta_image_1')) {
                $table->string('meta_image_1')->nullable()->after('approach_points');
            }
            if (!Schema::hasColumn('blogs', 'meta_image_2')) {
                $table->string('meta_image_2')->nullable()->after('meta_image_1');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $columns = [
                'tags', 'views', 'video_url', 'slider_images', 'quote',
                'quote_author', 'approach_title', 'approach_text',
                'approach_points', 'meta_image_1', 'meta_image_2'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('blogs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
