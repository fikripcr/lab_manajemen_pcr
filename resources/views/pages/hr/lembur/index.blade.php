@extends('layouts.tabler.app')

@section('title', 'Data Lembur')

@section('content')
<x-tabler.page-header title="Data Lembur" pretitle="HR & Kepegawaian">
    <x-slot name="actions">
        <div class="btn-list">
            <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Lembur" class="ajax-modal-btn" data-url="{{ route('hr.lembur.create') }}" data-modal-title="Form Tambah Lembur" />
        </div>
    </x-slot>
</x-tabler.page-header>

        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-lembur"
                    route="{{ route('hr.lembur.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%'],
                        ['data' => 'judul', 'name' => 'judul', 'title' => 'Judul'],
                        ['data' => 'pengusul_nama', 'name' => 'pengusul.latestDataDiri.nama', 'title' => 'Pengusul'],
                        ['data' => 'tanggal', 'name' => 'tgl_pelaksanaan', 'title' => 'Tanggal'],
                        ['data' => 'waktu', 'name' => 'jam_mulai', 'title' => 'Waktu', 'orderable' => false],
                        ['data' => 'durasi', 'name' => 'durasi_menit', 'title' => 'Durasi'],
                        ['data' => 'jumlah_pegawai', 'name' => 'jumlah_pegawai', 'title' => 'Pegawai', 'orderable' => false, 'searchable' => false],
                        ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'orderable' => false],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'width' => '10%'],
                    ]"
                />
            </div>
        </div>
@endsection
