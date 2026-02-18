@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="E-Office Master">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Kategori" class="btn-primary ajax-modal-btn" 
            data-url="{{ route('eoffice.kategori-perusahaan.create') }}" data-modal-title="Tambah Kategori Perusahaan" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <x-tabler.datatable
            id="table-kategori-perusahaan"
            route="{{ route('eoffice.kategori-perusahaan.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%', 'class' => 'text-center'],
                ['data' => 'nama_kategori', 'name' => 'nama_kategori', 'title' => 'Nama Kategori'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '15%']
            ]"
        />
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('form-success', function(e) {
        $('#table-kategori-perusahaan').DataTable().ajax.reload();
    });
</script>
@endpush
