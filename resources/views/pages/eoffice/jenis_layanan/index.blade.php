@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="E-Office Master">
    <x-slot:actions>
        <x-tabler.button type="create" :modal-url="route('eoffice.jenis-layanan.create')" modal-title="Tambah Jenis Layanan Baru" text="Tambah Layanan" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <x-tabler.datatable
            id="table-jenis-layanan"
            route="{{ route('eoffice.jenis-layanan.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%', 'class' => 'text-center'],
                ['data' => 'nama_layanan', 'name' => 'nama_layanan', 'title' => 'Nama Layanan'],
                ['data' => 'kategori', 'name' => 'kategori', 'title' => 'Kategori', 'class' => 'text-center'],
                ['data' => 'batas_pengerjaan', 'name' => 'batas_pengerjaan', 'title' => 'Estimasi (Jam)', 'class' => 'text-center'],
                ['data' => 'status', 'name' => 'is_active', 'title' => 'Status', 'class' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '15%']
            ]"
        />
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('form-success', function(e) {
        $('#table-jenis-layanan').DataTable().ajax.reload();
    });
</script>
@endpush
