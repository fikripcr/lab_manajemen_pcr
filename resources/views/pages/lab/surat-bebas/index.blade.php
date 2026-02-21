@extends('layouts.tabler.app')

@section('title', 'Surat Bebas Lab')

@section('content')
    <x-tabler.page-header title="Surat Bebas Lab" pretitle="Layanan">
        <x-slot:actions>
            <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.surat-bebas.create')" modal-title="Ajukan Surat Bebas Lab" />
        </x-slot:actions>
    </x-tabler.page-header>

        <div class="card">
            <div class="card-body">
                <x-tabler.datatable
                    id="table-surat-bebas"
                    route="{{ route('lab.surat-bebas.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'mahasiswa', 'name' => 'student.name', 'title' => 'Mahasiswa'],
                        ['data' => 'tanggal', 'name' => 'created_at', 'title' => 'Tanggal Pengajuan'],
                        ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]
                    ]"
                />
            </div>
        </div>
@endsection
