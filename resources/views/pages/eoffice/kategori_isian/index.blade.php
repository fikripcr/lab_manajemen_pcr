@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="E-Office Master">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Isian" class="btn-primary ajax-modal-btn" 
            data-url="{{ route('eoffice.kategori-isian.create') }}" data-modal-title="Tambah Kategori Isian Baru" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <x-tabler.datatable
            id="table-kategori-isian"
            route="{{ route('eoffice.kategori-isian.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%', 'class' => 'text-center'],
                ['data' => 'nama_isian', 'name' => 'nama_isian', 'title' => 'Nama Isian'],
                ['data' => 'type_label', 'name' => 'type', 'title' => 'Tipe', 'class' => 'text-center'],
                ['data' => 'alias_on_document', 'name' => 'alias_on_document', 'title' => 'Alias Dokumen', 'class' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '15%']
            ]"
        />
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('form-success', function(e) {
        $('#table-kategori-isian').DataTable().ajax.reload();
    });
</script>
@endpush
