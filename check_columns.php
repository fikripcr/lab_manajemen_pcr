<?php

require __DIR__ . '/vendor/autoload.php';
$app    = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$tables = ['hr_pegawai', 'pmb_pendaftaran', 'pmb_pilihan_prodi', 'pmb_prodi'];

foreach ($tables as $table) {
    echo "Table: $table\n";
    if (Schema::hasTable($table)) {
        $columns = Schema::getColumnListing($table);
        foreach ($columns as $column) {
            echo " - $column\n";
        }
    } else {
        echo " - TABLE NOT FOUND\n";
    }
    echo "\n";
}
