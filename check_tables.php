<?php
require 'vendor/autoload.php';
$app    = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = [
    'inventaris',
    'jadwal_kuliah',
    'kegiatans',
    'laporan_kerusakan',
    'log_penggunaan_labs',
    'log_penggunaan_pcs',
    'mata_kuliahs',
    'pc_assignments',
    'request_software',
    'semesters',
    'pengumuman',
    'labs',
];

foreach ($tables as $t) {
    $existsOld = Schema::hasTable($t);
    $existsNew = Schema::hasTable('lab_' . $t);

    $countOld = $existsOld  ?DB::table($t)->count() : 'MISSING';
    $countNew = $existsNew  ?DB::table('lab_' . $t)->count() : 'MISSING';

    echo sprintf("%-20s: %-10s | lab_%-20s: %-10s\n", $t, $countOld, $t, $countNew);
}
