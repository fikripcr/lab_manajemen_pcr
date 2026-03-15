<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for CMS (Content Management System) module.
     */
    public function up(): void
    {
        // =====================================================================
        // 1. Pages
        // =====================================================================
        Schema::create('cms_pages', function (Blueprint $table) {
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

        // =====================================================================
        // 2. Menus
        // =====================================================================
        Schema::create('cms_menus', function (Blueprint $table) {
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

            $table->foreign('parent_id')->references('menu_id')->on('cms_menus')->onDelete('cascade');
            $table->foreign('page_id')->references('page_id')->on('cms_pages')->onDelete('set null');
        });

        // =====================================================================
        // 3. Pengumuman (Moved from Shared)
        // =====================================================================
        Schema::create('cms_pengumuman', function (Blueprint $table) {
            $table->id('pengumuman_id');
            $table->unsignedBigInteger('penulis_id');
            $table->string('judul', 191);
            $table->text('isi');
            $table->string('jenis', 50);
            $table->boolean('is_published')->default(false);
            $table->string('image_url')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('penulis_id')->references('id')->on('users');
            $table->index(['jenis', 'is_published', 'published_at'], 'idx_pengumuman_main');
        });

        // =====================================================================
        // 4. Slideshows (Moved from Shared)
        // =====================================================================
        Schema::create('cms_slideshows', function (Blueprint $table) {
            $table->id();
            $table->string('image_url');
            $table->string('title')->nullable();
            $table->string('caption')->nullable();
            $table->string('link')->nullable();
            $table->integer('seq')->default(0);
            $table->boolean('is_active')->default(true);

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // =====================================================================
        // 5. FAQs (Moved from Shared)
        // =====================================================================
        Schema::create('cms_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->nullable();
            $table->integer('seq')->default(0);
            $table->boolean('is_active')->default(true);

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('cms_faqs');
        Schema::dropIfExists('cms_slideshows');
        Schema::dropIfExists('cms_pengumuman');
        Schema::dropIfExists('cms_menus');
        Schema::dropIfExists('cms_pages');
        Schema::enableForeignKeyConstraints();
    }
};
