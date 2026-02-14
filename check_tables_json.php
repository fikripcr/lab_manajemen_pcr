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

$results = [];
foreach ($tables as $t) {
    try {
        $existsOld = Schema::hasTable($t);
        $existsNew = Schema::hasTable('lab_' . $t);

        $results[$t] = [
            'exists'      => $existsOld,
            'count'       => $existsOld  ?DB::table($t)->count() : null,
            'lab_version' => [
                'exists' => $existsNew,
                'count'  => $existsNew  ?DB::table('lab_' . $t)->count() : null,
            ],
        ];
    } catch (\Exception $e) {
        $results[$t] = ['error' => $e->getMessage()];
    }
}

file_put_contents('table_diagnostic.json', json_encode($results, JSON_PRETTY_PRINT));
echo "DONE\n";
