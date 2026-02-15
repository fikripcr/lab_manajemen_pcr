@extends('layouts.admin.app')

@section('title', 'Surat Bebas Lab')

@section('content')
<div class="container-xl">
    <x-tabler.page-header title="Surat Bebas Lab" pretitle="Layanan">
        <x-slot:actions>
            <x-tabler.button type="create" href="{{ route('lab.surat-bebas.create') }}" text="Ajukan Surat" icon="bx bx-plus" />
        </x-slot:actions>
    </x-tabler.page-header>

    <div class="page-body">
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
    </div>
</div>
@endsection
