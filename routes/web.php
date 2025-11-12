<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Guest\GuestController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\PengumumanController;

Route::get('/', [GuestController::class, 'home'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/password', [ProfileController::class, 'passwordUpdate'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User resource routes
    Route::resource('users', UserController::class);

    // Lab resource routes
    Route::resource('labs', LabController::class);

    // Inventory resource routes
    Route::resource('inventories', InventarisController::class);
    Route::get('api/inventories', [InventarisController::class, 'dataTable'])->name('inventories.dataTable');
    Route::get('api/users', [UserController::class, 'dataTable'])->name('users.dataTable');
    Route::get('api/labs', [LabController::class, 'dataTable'])->name('labs.dataTable');

    // Pengumuman routes
    Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
        Route::get('/', [PengumumanController::class, 'index'])->name('index');
        Route::get('/create', [PengumumanController::class, 'create'])->name('create');
        Route::post('/', [PengumumanController::class, 'store'])->name('store');
        Route::get('/{pengumuman}', [PengumumanController::class, 'show'])->name('show');
        Route::get('/{pengumuman}/edit', [PengumumanController::class, 'edit'])->name('edit');
        Route::put('/{pengumuman}', [PengumumanController::class, 'update'])->name('update');
        Route::delete('/{pengumuman}', [PengumumanController::class, 'destroy'])->name('destroy');
        Route::get('/api/data', [PengumumanController::class, 'dataTablePengumuman'])->name('dataTable');
    });

    // Berita routes
    Route::prefix('berita')->name('berita.')->group(function () {
        Route::get('/', [PengumumanController::class, 'beritaIndex'])->name('index');
        Route::get('/create', [PengumumanController::class, 'create'])->name('create')->defaults('type', 'berita');
        Route::post('/', [PengumumanController::class, 'store'])->name('store');
        Route::get('/{berita}', [PengumumanController::class, 'show'])->name('show');
        Route::get('/{berita}/edit', [PengumumanController::class, 'edit'])->name('edit');
        Route::put('/{berita}', [PengumumanController::class, 'update'])->name('update');
        Route::delete('/{berita}', [PengumumanController::class, 'destroy'])->name('destroy');
        Route::get('/api/data', [PengumumanController::class, 'dataTableBerita'])->name('dataTable');
    });

});
// Guest news routes
Route::get('/news/{pengumuman}', [GuestController::class, 'showNews'])->name('guest.news.show');

require __DIR__ . '/auth.php';
