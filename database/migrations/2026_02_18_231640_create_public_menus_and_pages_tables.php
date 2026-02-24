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
        Schema::create('public_pages', function (Blueprint $table) {
            $table->id('page_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->text('meta_desc')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        Schema::create('public_menus', function (Blueprint $table) {
            $table->id('menu_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('title');
            $table->string('type')->default('link'); // link, page, route
            $table->string('url')->nullable();
            $table->string('route')->nullable();
            $table->unsignedBigInteger('page_id')->nullable();
            $table->string('position')->default('header'); // header, footer, etc.
            $table->string('target')->default('_self');
            $table->integer('sequence')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('parent_id')->references('menu_id')->on('public_menus')->onDelete('cascade');
            $table->foreign('page_id')->references('page_id')->on('public_pages')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_menus');
        Schema::dropIfExists('public_pages');
    }
};
