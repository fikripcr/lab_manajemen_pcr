<?php

namespace App\Console\Commands;

use App\Http\Controllers\Pemutu\DokumenSpmiController;
use App\Models\Pemutu\Dokumen;
use Illuminate\Console\Command;
use ReflectionMethod;

class TestSummaryData extends Command
{
    protected $signature = 'test:summary';

    protected $description = 'Test DokumenSpmiController summaryData';

    public function handle()
    {
        $visiDoc = Dokumen::with('dokSubs')->where('jenis', 'visi')->where('periode', 2024)->first();
        if (! $visiDoc) {
            $this->error('No Visi 2024 found');

            return;
        }

        $controller = app(DokumenSpmiController::class);
        $kebijakanChain = ['visi', 'misi', 'rjp', 'renstra', 'renop'];
        $startIndex = 0;
        $periode = 2024;

        $reflection = new ReflectionMethod($controller, 'traceChainDown');
        $reflection->setAccessible(true);
        $reflectionCol = new ReflectionMethod($controller, 'collectIndicatorsFromChain');
        $reflectionCol->setAccessible(true);

        $indicators = collect();
        foreach ($visiDoc->dokSubs as $poin) {
            $this->info('Tracing Poin: '.$poin->judul);
            $chain = $reflection->invoke($controller, $poin, $kebijakanChain, $startIndex, $periode);
            $inds = $reflectionCol->invoke($controller, $chain);
            $this->line('  Indicators found: '.$inds->count());
            $indicators = $indicators->merge($inds);
        }

        $this->info('Total merged: '.$indicators->count());
        $unique = $indicators->unique('indikator_id')->values();
        $this->info('Unique indicators: '.$unique->count());
    }
}
