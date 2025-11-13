<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\SoftwareRequestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Guest\GuestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GuestController::class, 'home'])->name('home');
Route::get('/request-software', [GuestController::class, 'requestSoftware'])->name('guest.request-software');
Route::post('/request-software', [GuestController::class, 'storeSoftwareRequest'])->name('guest.store-software-request');
Route::get('/api/mata-kuliah', [GuestController::class, 'getMataKuliah'])->name('guest.mata-kuliah.search');
Route::get('/announcements', [GuestController::class, 'showAllNews'])->name('guest.announcements.index');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
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
    Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');
    Route::resource('permissions', PermissionController::class);

    // Semester routes
    Route::resource('semesters', SemesterController::class);
    Route::get('api/semesters', [SemesterController::class, 'data'])->name('semesters.data');

    // Jadwal routes
    Route::resource('jadwal', JadwalController::class);
    Route::get('jadwal/import/form', [JadwalController::class, 'showImport'])->name('jadwal.import.form');
    Route::post('jadwal/import', [JadwalController::class, 'import'])->name('jadwal.import');

    // Mata Kuliah routes
    Route::resource('mata-kuliah', MataKuliahController::class);
    Route::get('api/mata-kuliah', [MataKuliahController::class, 'data'])->name('mata-kuliah.data');

    // Software Request routes
    Route::prefix('software-requests')->name('software-requests.')->group(function () {
        Route::get('/', [SoftwareRequestController::class, 'index'])->name('index');
        Route::get('/data', [SoftwareRequestController::class, 'data'])->name('data');
        Route::get('/{id}', [SoftwareRequestController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SoftwareRequestController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SoftwareRequestController::class, 'update'])->name('update');
    });

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
