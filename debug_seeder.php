<?php
require 'vendor/autoload.php';
$app    = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    (new Database\Seeders\Hr\HrPegawaiSeeder)->run();
} catch (\Exception $e) {
    echo $e->getMessage() . "\n";
}
