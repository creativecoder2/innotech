<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nav_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url')->default('#');
            $table->string('page_route')->nullable()->default('custom'); // home, about, contact, service, partners, blog, custom
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('show_on_home')->default(true);
            $table->boolean('show_on_inner')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('target_blank')->default(false);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('nav_menus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_menus');
    }
};
