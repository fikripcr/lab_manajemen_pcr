@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Master Jalur PMB" pretitle="PMB">
    <x-slot:actions>
        <x-tabler.button type="button" class="btn-primary d-none d-sm-inline-block ajax-modal-btn" icon="ti ti-plus" text="Tambah Jalur"
            data-modal-target="#modalAction" data-modal-title="Tambah Jalur" data-url="{{ route('pmb.jalur.create') }}" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

        <div class="card">
            <div class="card-body">
                <x-tabler.datatable
                    id="table-jalur"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                        ['data' => 'nama_jalur', 'name' => 'nama_jalur', 'title' => 'Nama Jalur'],
                        ['data' => 'biaya_pendaftaran', 'name' => 'biaya_pendaftaran', 'title' => 'Biaya Pendaftaran'],
                        ['data' => 'is_aktif', 'name' => 'is_aktif', 'title' => 'Status'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                    ]"
                    :url="route('pmb.jalur.data')"
                />
            </div>
        </div>
@endsection
