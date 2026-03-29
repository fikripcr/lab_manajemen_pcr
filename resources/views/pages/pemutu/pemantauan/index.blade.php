@extends('layouts.tabler.app')
@section('title', 'Pemantauan (Monitoring) - Siklus ' . $siklus['tahun'])

@section('header')
<x-tabler.page-header title="Pemantauan SPMI {{ $siklus['tahun'] }}" pretitle="Pelaksanaan">
    <x-slot:actions>
        <x-tabler.button 
            type="create" 
            :modal-url="route('pemutu.pemantauan.create')" 
            modal-title="Jadwalkan Rapat Pemantauan" 
            data-modal-size="modal-xl"
            text="Jadwalkan Rapat"
            icon="ti ti-calendar-plus"
        />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <x-tabler.card>
            <x-tabler.card-body class="border-bottom py-3">
                <div class="d-flex align-items-center">
                    <div class="text-muted">
                        Menampilkan daftar rapat pemantauan untuk siklus <strong>{{ $siklus['tahun'] }}</strong>.
                    </div>
                    <div class="ms-auto d-flex gap-2">
                        <x-tabler.datatable-page-length dataTableId="table-pemantauan" />
                        <x-tabler.datatable-search dataTableId="table-pemantauan" />
                    </div>
                </div>
            </x-tabler.card-body>
            <div class="table-responsive">
                <x-tabler.datatable
                    id="table-pemantauan"
                    route="{{ route('pemutu.pemantauan.data') }}"
                    :columns="[
                        ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                        ['data' => 'judul_kegiatan', 'name' => 'judul_kegiatan', 'title' => 'Judul Rapat'],
                        ['data' => 'tgl_info', 'name' => 'tgl_rapat', 'title' => 'Waktu Pelaksanaan', 'width' => '20%'],
                        ['data' => 'tempat_rapat', 'name' => 'tempat_rapat', 'title' => 'Tempat', 'width' => '15%'],
                        ['data' => 'indikator_count', 'name' => 'indikator_count', 'title' => 'Cakupan', 'width' => '10%', 'class' => 'text-center', 'orderable' => false],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                    ]"
                />
            </div>
        </x-tabler.card>
    </div>
</div>
@endsection
