<?php

use App\Http\Controllers\Cms\DashboardController;
use App\Http\Controllers\Cms\FAQController;
use App\Http\Controllers\Cms\PengumumanController;
use App\Http\Controllers\Cms\PublicMenuController;
use App\Http\Controllers\Cms\PublicPageController;
use App\Http\Controllers\Cms\SlideshowController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.expired'])->group(function () {

    // CMS Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CMS Data Modules
    Route::prefix('cms')->name('cms.')->group(function () {

        // Pengumuman
        Route::get('/pengumuman/data', [PengumumanController::class, 'data'])->name('pengumuman.data');
        Route::resource('pengumuman', PengumumanController::class);

        // Berita
        Route::prefix('berita')->name('berita.')->controller(PengumumanController::class)->group(function () {
            Route::get('/', 'beritaIndex')->name('index');
            Route::get('/create', 'create')->defaults('type', 'berita')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{pengumuman}', 'show')->name('show');
            Route::get('/{pengumuman}/edit', 'edit')->name('edit');
            Route::put('/{pengumuman}', 'update')->name('update');
            Route::delete('/{pengumuman}', 'destroy')->name('destroy');
            Route::get('/data', 'data')->name('data');
        });

        // Slideshow
        Route::post('/slideshow/reorder', [SlideshowController::class, 'reorder'])->name('slideshow.reorder');
        Route::get('/slideshow/data', [SlideshowController::class, 'data'])->name('slideshow.data');
        Route::resource('slideshow', SlideshowController::class);

        // FAQ
        Route::post('/faq/reorder', [FAQController::class, 'reorder'])->name('faq.reorder');
        Route::get('/faq/data', [FAQController::class, 'data'])->name('faq.data');
        Route::resource('faq', FAQController::class);

        // Info Publik CMS
        // Public Menu
        Route::post('/public-menu/reorder', [PublicMenuController::class, 'reorder'])->name('public-menu.reorder');
        Route::resource('public-menu', PublicMenuController::class);

        // Public Page
        Route::get('/public-page/data', [PublicPageController::class, 'data'])->name('public-page.data');
        Route::resource('public-page', PublicPageController::class);

    });

});
