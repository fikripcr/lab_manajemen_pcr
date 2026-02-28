@extends('layouts.tabler.app')
@section('title', 'Isi Evaluasi KPI')

@section('header')
<x-tabler.page-header title="Isi Evaluasi KPI" pretitle="{{ $periode->nama }}">
    <x-slot:actions>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('pemutu.evaluasi-kpi.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-bottom py-3">
        <h3 class="card-title">Daftar Indikator Kinerja Saya</h3>
    </div>
    <div class="card-body border-bottom py-3">
        <div class="text-muted">
            Berikut adalah daftar indikator kinerja yang ditargetkan untuk Anda pada periode <strong>{{ $periode->nama }}</strong>. Silakan isi capaian dan analisis untuk setiap indikator.
        </div>
    </div>
    <div class="table-responsive">
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
    </div>
</div>
@endsection
