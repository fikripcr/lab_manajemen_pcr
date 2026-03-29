<?php

// echo phpinfo();
// die;
use App\Http\Controllers\Lab\GlobalSearchController;
use Illuminate\Support\Facades\Route;

// 🔹 Route untuk Public (tanpa login)
require __DIR__.'/public.php';

require __DIR__.'/lab.php';
require __DIR__.'/pemutu.php';
require __DIR__.'/eoffice.php';
require __DIR__.'/event.php';

// 🔹 Theme API (public, no auth required)
Route::post('/theme/save', [App\Http\Controllers\Sys\ThemeTablerController::class, 'save'])->name('theme.save');

// 🔹 CMS & Akademik Routes (Authenticated)
require __DIR__.'/cms.php';
require __DIR__.'/akademik.php';

require __DIR__.'/sys.php';

// 🔹 Route Auth bawaan Laravel Breeze / Jetstream
require __DIR__.'/auth.php';

require __DIR__.'/hr.php';
require __DIR__.'/pmb.php';
require __DIR__.'/cbt.php';
require __DIR__.'/survei.php';
require __DIR__.'/project.php';

// Global Search
Route::get('/global-search', [GlobalSearchController::class, 'search'])->name('global-search');

// Laravel Impersonate Routes (needs to be outside auth group to allow switching back)
Route::impersonate();
