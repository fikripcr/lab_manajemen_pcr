<?php
// echo phpinfo();
// die;
use App\Http\Controllers\Lab\GlobalSearchController;
use Illuminate\Support\Facades\Route;

// 🔹 Route untuk Guest (tanpa login)
require __DIR__ . '/guest.php';

require __DIR__ . '/lab.php';
require __DIR__ . '/pemutu.php';

// 🔹 Theme API (public, no auth required)
Route::post('/theme/save', [App\Http\Controllers\Sys\ThemeTablerController::class, 'save'])->name('theme.save');

require __DIR__ . '/sys.php';

// 🔹 Route Auth bawaan Laravel Breeze / Jetstream
require __DIR__ . '/auth.php';

// Laravel Impersonate Routes (needs to be outside auth group to allow switching back)
Route::impersonate();

require __DIR__ . '/hr.php';

// Global Search
Route::get('/global-search', [GlobalSearchController::class, 'search'])->name('global-search');
