<?php

use App\Http\Controllers\Public\PublicController;
use Illuminate\Support\Facades\Route;

// ==========================
// 🔹 Public Routes
// ==========================
Route::controller(PublicController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/announcements', 'showAllNews')->name('public.announcements.index');
    Route::get('/news/{pengumuman?}', 'showNews')->name('public.news.show');

    // Request Software (Form)
    Route::get('/request-software', 'requestSoftware')->name('public.request-software');
    Route::post('/request-software', 'storeSoftwareRequest')->name('public.store-software-request');

    // Public API
    Route::prefix('api')->group(function () {
        Route::get('/search-mata-kuliah', 'getMataKuliah')->name('public.matakuliah.search');
    });
});
