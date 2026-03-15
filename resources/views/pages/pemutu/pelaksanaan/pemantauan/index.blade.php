@extends('layouts.tabler.app')
@section('title', 'Pemantauan (Monitoring)')

@section('header')
<x-tabler.page-header title="Pemantauan SPMI {{ $siklus['tahun'] }}" pretitle="Pelaksanaan">
    <x-slot:actions>
        <div class="text-muted small px-3 py-2 bg-light-lt rounded border border-info d-none d-md-block">
            <i class="ti ti-info-circle me-1 text-info"></i> Silahkan jadwalkan di menu <b>Rapat</b> lalu pilih entitas terkait indikator pemantauan.
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<x-tabler.card>
    <x-tabler.card-header title="Daftar Rapat Pemantauan" />
    <x-tabler.card-body class="border-bottom bg-light-lt">
        <p class="text-muted mb-0 small">Berikut adalah daftar rapat atau agenda pemantauan yang telah dijadwalkan. Rapat ini digunakan untuk memantau pelaksanaan perbaikan indikator.</p>
    </x-tabler.card-body>
    <x-tabler.card-body class="p-0 table-responsive">
        <x-tabler.datatable
            id="table-pemantauan"
            route="{{ route('pemutu.pelaksanaan.pemantauan.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                ['data' => 'tgl_info', 'name' => 'tgl_rapat', 'title' => 'Waktu Pelaksanaan', 'width' => '20%'],
                ['data' => 'judul_kegiatan', 'name' => 'judul_kegiatan', 'title' => 'Nama Kegiatan/Rapat'],
                ['data' => 'tempat_rapat', 'name' => 'tempat_rapat', 'title' => 'Tempat'],
                ['data' => 'indikator_count', 'name' => 'indikator_count', 'title' => 'Cakupan', 'width' => '15%', 'class' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '15%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
            ]"
        />
    </x-tabler.card-body>
</x-tabler.card>
@endsection
