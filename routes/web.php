<?php
// echo phpinfo();
// die;
use Illuminate\Support\Facades\Route;

// 🔹 Route untuk Guest (tanpa login)
require __DIR__ . '/guest.php';

require __DIR__ . '/admin.php';
require __DIR__ . '/pemtu.php';

// 🔹 Theme API (public, no auth required)
Route::post('/theme/save', [App\Http\Controllers\Sys\ThemeTablerController::class, 'save'])->name('theme.save');

require __DIR__ . '/sys.php';

// 🔹 Route Auth bawaan Laravel Breeze / Jetstream
require __DIR__ . '/auth.php';

// Laravel Impersonate Routes (needs to be outside auth group to allow switching back)
Route::impersonate();

require __DIR__ . '/hr.php';
