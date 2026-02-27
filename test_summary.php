<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pemutu\IndikatorSummaryStandar;
use App\Models\Pemutu\IndikatorSummaryPerforma;

echo "=== Test Model Query ===" . PHP_EOL;

echo "\n1. Test Standar:" . PHP_EOL;
$standar = IndikatorSummaryStandar::first();
if ($standar) {
    echo "   no_indikator: " . $standar->no_indikator . PHP_EOL;
    echo "   indikator: " . substr($standar->indikator, 0, 50) . "..." . PHP_EOL;
    echo "   ed_filled_units: " . ($standar->ed_filled_units ?? 0) . PHP_EOL;
} else {
    echo "   NULL - No data found!" . PHP_EOL;
}

echo "\n2. Test Performa:" . PHP_EOL;
$performa = IndikatorSummaryPerforma::first();
if ($performa) {
    echo "   no_indikator: " . $performa->no_indikator . PHP_EOL;
    echo "   indikator: " . substr($performa->indikator, 0, 50) . "..." . PHP_EOL;
    echo "   total_pegawai_with_kpi: " . ($performa->total_pegawai_with_kpi ?? 0) . PHP_EOL;
} else {
    echo "   NULL - No data found!" . PHP_EOL;
}

echo "\n3. Count:" . PHP_EOL;
echo "   Standar count: " . IndikatorSummaryStandar::count() . PHP_EOL;
echo "   Performa count: " . IndikatorSummaryPerforma::count() . PHP_EOL;
