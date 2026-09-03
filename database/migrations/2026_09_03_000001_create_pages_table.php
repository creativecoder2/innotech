<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->string('template_type')->default('custom'); // terms, privacy, refund, shipping, custom
            $table->longText('content')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('show_in_footer')->default(true);
            $table->string('footer_placement')->default('bottom_bar'); // bottom_bar, useful_links, both
            $table->integer('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
