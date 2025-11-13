<?php

use Illuminate\Support\Facades\Route;

// 🔹 Route untuk Guest (tanpa login)
require __DIR__ . '/guest.php';

// 🔹 Route untuk Admin (login wajib)
require __DIR__ . '/admin.php';

// 🔹 Route Auth bawaan Laravel Breeze / Jetstream
require __DIR__ . '/auth.php';
