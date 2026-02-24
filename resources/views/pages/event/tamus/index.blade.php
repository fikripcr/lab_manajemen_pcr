@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Buku Tamu Kegiatan" pretitle="Kegiatan" />
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'table-tamus'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'table-tamus'" />
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <x-tabler.datatable
            id="table-tamus"
            :columns="[
                ['data' => 'DT_RowIndex',           'name' => 'eventtamu_id',           'title' => 'No',           'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                ['data' => 'foto_preview',           'name' => 'foto_preview',           'title' => 'Foto',         'orderable' => false, 'searchable' => false],
                ['data' => 'nama_tamu',              'name' => 'nama_tamu',              'title' => 'Nama Tamu'],
                ['data' => 'instansi',               'name' => 'instansi',               'title' => 'Instansi'],
                ['data' => 'kontak',                 'name' => 'kontak',                 'title' => 'No. HP',       'searchable' => false],
                ['data' => 'judul_kegiatan',         'name' => 'events.judul_event',     'title' => 'Kegiatan'],
                ['data' => 'waktu_datang',           'name' => 'waktu_datang',           'title' => 'Waktu Datang', 'searchable' => false],
                ['data' => 'action',                 'name' => 'action',                 'title' => 'Aksi',         'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ]"
            :route="route('Kegiatan.tamus.data')"
        />
    </div>
</div>
@endsection
