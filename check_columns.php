<?php
require __DIR__ . '/vendor/autoload.php';
$app    = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = Illuminate\Support\Facades\DB::select('DESCRIBE pemutu_indikator_orgunit');
foreach ($columns as $column) {
    echo $column->Field . PHP_EOL;
}
