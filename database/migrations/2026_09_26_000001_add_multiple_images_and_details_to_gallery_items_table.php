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
        Schema::table('gallery_items', function (Blueprint $table) {
            if (!Schema::hasColumn('gallery_items', 'images')) {
                $table->longText('images')->nullable()->after('image');
            }
            if (!Schema::hasColumn('gallery_items', 'description')) {
                $table->text('description')->nullable()->after('category');
            }
            if (!Schema::hasColumn('gallery_items', 'client')) {
                $table->string('client')->nullable()->after('description');
            }
            if (!Schema::hasColumn('gallery_items', 'date')) {
                $table->string('date')->nullable()->after('client');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $cols = ['images', 'description', 'client', 'date'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('gallery_items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
