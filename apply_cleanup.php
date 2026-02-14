<?php
require 'vendor/autoload.php';
$app    = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$toDrop = [
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
    'lab_inventarises',
];

DB::statement('SET FOREIGN_KEY_CHECKS=0;');

foreach ($toDrop as $t) {
    if (Schema::hasTable($t)) {
        if ($t === 'lab_inventarises' && ! Schema::hasTable('lab_inventaris')) {
            Schema::rename($t, 'lab_inventaris');
            echo "Renamed $t to lab_inventaris\n";
        } else if (Schema::hasTable('lab_' . $t) || $t === 'lab_inventarises') {
            Schema::drop($t);
            echo "Dropped $t\n";
        } else {
            Schema::rename($t, 'lab_' . $t);
            echo "Renamed $t to lab_$t\n";
        }
    }
}

// Ensure specific renames
if (Schema::hasTable('surat_bebas_labs') && ! Schema::hasTable('lab_surat_bebas_labs')) {
    Schema::rename('surat_bebas_labs', 'lab_surat_bebas_labs');
    echo "Renamed surat_bebas_labs to lab_surat_bebas_labs\n";
}

if (Schema::hasTable('periode_softrequest') && ! Schema::hasTable('lab_periode_softrequest')) {
    Schema::rename('periode_softrequest', 'lab_periode_softrequest');
    echo "Renamed periode_softrequest to lab_periode_softrequest\n";
}

DB::statement('SET FOREIGN_KEY_CHECKS=1;');

// Mark migration as done if not already
$migrationTable = Schema::hasTable('sys_migrations') ? 'sys_migrations' : 'migrations';
if (! DB::table($migrationTable)->where('migration', '2026_02_15_000000_rename_tables_to_lab_prefix')->exists()) {
    DB::table($migrationTable)->insert([
        'migration' => '2026_02_15_000000_rename_tables_to_lab_prefix',
        'batch'     => (int) DB::table($migrationTable)->max('batch') + 1,
    ]);
    echo "Migration record created in $migrationTable.\n";
}
echo "CLEANUP FINISHED\n";
