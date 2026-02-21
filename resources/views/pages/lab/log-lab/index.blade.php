@extends('layouts.tabler.app')

@section('title', 'Log Penggunaan Lab (Guest)')

@section('content')
    <x-tabler.page-header title="Log Penggunaan Lab" pretitle="Buku Tamu">
        <x-slot:actions>
            <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.log-lab.create')" modal-title="Tambah Log Penggunaan Lab" />
        </x-slot:actions>
    </x-tabler.page-header>

        <div class="card">
            <div class="card-body">
                <x-tabler.datatable
                    id="table-log-lab"
                    route="{{ route('lab.log-lab.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'waktu', 'name' => 'waktu_isi', 'title' => 'Waktu'],
                        ['data' => 'kegiatan', 'name' => 'kegiatan.nama_kegiatan', 'title' => 'Kegiatan / Event'],
                        ['data' => 'lab_nama', 'name' => 'lab.name', 'title' => 'Lab'],
                        ['data' => 'peserta', 'name' => 'nama_peserta', 'title' => 'Nama Peserta'],
                        ['data' => 'kondisi', 'name' => 'kondisi', 'title' => 'Kondisi PC/Alat']
                    ]"
                />
            </div>
        </div>
@endsection
