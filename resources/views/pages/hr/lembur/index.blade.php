@extends('layouts.tabler.app')

@section('title', 'Data Lembur')

@section('header')
<x-tabler.page-header title="Data Lembur" pretitle="HR & Kepegawaian">
    <x-slot:actions>
        <div class="btn-list">
            <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Lembur" class="ajax-modal-btn" data-url="{{ route('hr.lembur.create') }}" data-modal-title="Form Tambah Lembur" />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

        <div class="card">
            <div class="card-header border-bottom">
                <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
                    <h3 class="card-title mb-0">Data Lembur</h3>
                    <div class="ms-auto d-flex gap-2">
                        <x-tabler.datatable-page-length dataTableId="table-lembur" />
                        <x-tabler.datatable-search dataTableId="table-lembur" />
                        <x-tabler.datatable-filter dataTableId="table-lembur">
                            <div style="min-width: 150px;">
                                <x-tabler.form-select name="status" placeholder="Semua Status" class="mb-0"
                                    :options="['Diajukan' => 'Diajukan', 'Approved' => 'Disetujui', 'Rejected' => 'Ditolak']" />
                            </div>
                        </x-tabler.datatable-filter>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <x-tabler.datatable
                    id="table-lembur"
                    route="{{ route('hr.lembur.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '5%'],
                        ['data' => 'judul', 'name' => 'judul', 'title' => 'Judul'],
                        ['data' => 'pengusul_nama', 'name' => 'pengusul.latestDataDiri.nama', 'title' => 'Pengusul'],
                        ['data' => 'tanggal', 'name' => 'tgl_pelaksanaan', 'title' => 'Tanggal'],
                        ['data' => 'waktu', 'name' => 'jam_mulai', 'title' => 'Waktu', 'orderable' => false],
                        ['data' => 'durasi', 'name' => 'durasi_menit', 'title' => 'Durasi'],
                        ['data' => 'jumlah_pegawai', 'name' => 'jumlah_pegawai', 'title' => 'Pegawai', 'orderable' => false, 'searchable' => false],
                        ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'orderable' => false],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '10%'],
                    ]"
                />
            </div>
        </div>
@endsection
