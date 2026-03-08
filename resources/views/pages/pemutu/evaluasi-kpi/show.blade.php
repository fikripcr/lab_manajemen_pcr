@extends('layouts.tabler.app')
@section('title', 'Isi Evaluasi KPI')

@section('header')
<x-tabler.page-header title="Isi Evaluasi KPI" pretitle="{{ $periode->nama }}">
    <x-slot:actions>
        <x-tabler.button type="back" :href="route('pemutu.evaluasi-kpi.index')" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<x-tabler.card>
    <x-tabler.card-header title="Daftar Indikator Kinerja Saya" class="border-bottom py-3" />
    <x-tabler.card-body class="border-bottom py-3">
        <div class="text-muted">
            Berikut adalah daftar indikator kinerja yang ditargetkan untuk Anda pada periode <strong>{{ $periode->nama }}</strong>. Silakan isi capaian dan analisis untuk setiap indikator.
        </div>
    </x-tabler.card-body>
    <x-tabler.card-body class="p-0 table-responsive">
        <x-tabler.datatable 
            id="table-ekpi"
            route="{{ route('pemutu.evaluasi-kpi.data', $periode->encrypted_periode_kpi_id) }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                ['data' => 'pegawai', 'name' => 'pegawai', 'title' => 'Pegawai', 'width' => '15%'],
                ['data' => 'indikator_full', 'name' => 'indikator', 'title' => 'Indikator / Sasaran Kinerja'],
                ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%'],
                ['data' => 'capaian', 'name' => 'capaian', 'title' => 'Capaian', 'width' => '15%'],
                ['data' => 'analisis', 'name' => 'analisis', 'title' => 'Analisis', 'width' => '25%'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
            ]"
        />
    </x-tabler.card-body>
</x-tabler.card>
@endsection
