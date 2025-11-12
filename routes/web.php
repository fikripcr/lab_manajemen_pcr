<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Guest\GuestController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\PermissionController;

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
    Route::get('api/users', [UserController::class, 'data'])->name('users.data');

    // Lab resource routes
    Route::resource('labs', LabController::class);
    Route::get('api/labs', [LabController::class, 'data'])->name('labs.data');

    // Inventory resource routes
    Route::resource('inventories', InventarisController::class);
    Route::get('api/inventories', [InventarisController::class, 'data'])->name('inventories.data');
    Route::get('inventories/export', [InventarisController::class, 'export'])->name('inventories.export');

    // Roles & Permissions routes
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // Pengumuman routes
    Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
        Route::get('/', [PengumumanController::class, 'index'])->name('index');
        Route::get('/create', [PengumumanController::class, 'create'])->name('create');
        Route::post('/', [PengumumanController::class, 'store'])->name('store');
        Route::get('/{pengumuman}', [PengumumanController::class, 'show'])->name('show');
        Route::get('/{pengumuman}/edit', [PengumumanController::class, 'edit'])->name('edit');
        Route::put('/{pengumuman}', [PengumumanController::class, 'update'])->name('update');
        Route::delete('/{pengumuman}', [PengumumanController::class, 'destroy'])->name('destroy');
        Route::get('/api/data', [PengumumanController::class, 'data'])->name('data');
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
        Route::get('/api/data', [PengumumanController::class, 'data'])->name('data');
    });

});
// Guest news routes
Route::get('/news/{pengumuman}', [GuestController::class, 'showNews'])->name('guest.news.show');

require __DIR__ . '/auth.php';
