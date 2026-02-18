@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Master Jenis Dokumen" pretitle="PMB">
    <x-slot:actions>
        <x-tabler.button type="button" class="btn-primary d-none d-sm-inline-block ajax-modal-btn" icon="ti ti-plus" text="Tambah Jenis Dokumen" 
            data-modal-target="#modalAction" data-modal-title="Tambah Jenis Dokumen" data-url="{{ route('pmb.jenis-dokumen.create') }}" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-jenis-dokumen" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nama_dokumen', 'name' => 'nama_dokumen', 'title' => 'Nama Dokumen'],
                        ['data' => 'tipe_file', 'name' => 'tipe_file', 'title' => 'Tipe File'],
                        ['data' => 'max_size_kb', 'name' => 'max_size_kb', 'title' => 'Ukuran Maksimal'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('pmb.jenis-dokumen.paginate')"
                />
            </div>
        </div>
    </div>
</div>

@endsection
