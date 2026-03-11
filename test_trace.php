<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$visiDoc = \App\Models\Pemutu\Dokumen::with('dokSubs')->where('jenis','visi')->where('periode',2024)->first();
$kebijakanChain = ['visi', 'misi', 'rjp', 'renstra', 'renop'];
$periode = 2024;

function traceChainDownLocal($poin, array $kebijakanChain, int $currentLevel, $periode) {
    $result = [];
    $nextLevel = $currentLevel + 1;
    if ($nextLevel >= count($kebijakanChain)) return $result;
    
    $children = $poin->mappedFrom()
        ->whereHas('dokumen', function ($q) use ($kebijakanChain, $nextLevel, $periode) {
            $q->where('jenis', $kebijakanChain[$nextLevel])
              ->where('periode', $periode);
        })
        ->with(['dokumen', 'indikators.orgUnits', 'indikators.parent.orgUnits'])
        ->get();
        
    foreach ($children as $child) {
        $childData = [
            'poin' => $child,
            'jenis' => $kebijakanChain[$nextLevel],
            'indicators' => collect(),
            'chain' => []
        ];
        if ($kebijakanChain[$nextLevel] === 'renop' && $child->is_hasilkan_indikator) {
            $childData['indicators'] = $child->indikators()->with(['orgUnits', 'parent.orgUnits'])->get();
        }
        $childData['chain'] = traceChainDownLocal($child, $kebijakanChain, $nextLevel, $periode);
        $result[] = $childData;
    }
    return $result;
}

function collectIndicatorsFromChainLocal(array $chain) {
    $indicators = collect();
    foreach ($chain as $node) {
        if (isset($node['indicators']) && $node['indicators'] instanceof \Illuminate\Support\Collection) {
            $indicators = $indicators->merge($node['indicators']);
        }
            $indicators = $indicators->merge(collectIndicatorsFromChainLocal($node['chain']));
        }
    }
    return $indicators;
}

$indicators = collect();
foreach ($visiDoc->dokSubs as $poin) {
    echo 'Tracing Poin: ' . $poin->judul . PHP_EOL;
    $chain = traceChainDownLocal($poin, $kebijakanChain, 0, $periode);
    $inds = collectIndicatorsFromChainLocal($chain);
    $indicators = $indicators->merge($inds);
    echo '  Indicators merged: ' . $inds->count() . PHP_EOL;
}

echo 'Total Indicators: ' . $indicators->count() . PHP_EOL;
