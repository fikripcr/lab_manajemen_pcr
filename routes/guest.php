<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guest\GuestController;

// ==========================
// 🔹 Guest Routes
// ==========================
Route::controller(GuestController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/announcements', 'showAllNews')->name('guest.announcements.index');
    Route::get('/news/{pengumuman}', 'showNews')->name('guest.news.show');

    // Request Software (Form)
    Route::get('/request-software', 'requestSoftware')->name('guest.request-software');
    Route::post('/request-software', 'storeSoftwareRequest')->name('guest.store-software-request');

    // Public API
    Route::prefix('api')->group(function () {
        Route::get('/search-mata-kuliah', 'getMataKuliah')->name('guest.matakuliah.search');
    });
});
