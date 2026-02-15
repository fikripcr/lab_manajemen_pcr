@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Manajemen Rapat
            </h2>
            <div class="text-muted mt-1">Pemutu / Meeting</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <x-tabler.button type="create" href="{{ route('pemutu.rapat.create') }}" text="Tambah Rapat" />
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'rapat-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'rapat-table'" />
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <x-tabler.datatable
                id="rapat-table" route="{{ route('pemutu.rapat.data') }}" :columns="[
                [
                    'title' => 'Jenis Rapat',
                    'data' => 'jenis_rapat',
                    'name' => 'jenis_rapat',
                ],
                [
                    'title' => 'Judul Kegiatan',
                    'data' => 'judul_kegiatan',
                    'name' => 'judul_kegiatan',
                ],
                [
                    'title' => 'Tanggal Rapat',
                    'data' => 'tgl_rapat',
                    'name' => 'tgl_rapat',
                ],
                [
                    'title' => 'Waktu',
                    'data' => 'waktu',
                    'name' => 'waktu',
                    'render' => function($row) {
                        return $row->waktu_mulai->format('H:i') . ' - ' . $row->waktu_selesai->format('H:i');
                    }
                ],
                [
                    'title' => 'Tempat',
                    'data' => 'tempat_rapat',
                    'name' => 'tempat_rapat',
                ],
                [
                    'title' => 'Ketua Rapat',
                    'data' => 'ketua_user.name',
                    'name' => 'ketua_user.name',
                ],
                [
                    'title' => 'Actions',
                    'data' => 'action',
                    'name' => 'action',
                    'orderable' => false,
                    'searchable' => false,
                ],
            ]" />
        </div>
    </div>
@endsection
