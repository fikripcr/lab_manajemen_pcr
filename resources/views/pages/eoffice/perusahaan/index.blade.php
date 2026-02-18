@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="E-Office Master">
    <x-slot:actions>
        <x-tabler.button type="create" class="btn-primary ajax-modal-btn" 
            data-url="{{ route('eoffice.perusahaan.create') }}" modal-title="Tambah Perusahaan" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body border-bottom py-3">
                <div class="d-flex">
                    <div class="text-secondary">
                        <div class="ms-2 d-inline-block">
                            <x-tabler.form-select id="filter-kategori" class="form-select-sm" label="Filter Kategori" class="mb-0">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->kategoriperusahaan_id }}">{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </x-tabler.form-select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <x-tabler.datatable
                    id="table-perusahaan"
                    route="{{ route('eoffice.perusahaan.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%', 'class' => 'text-center'],
                        ['data' => 'nama_perusahaan', 'name' => 'nama_perusahaan', 'title' => 'Nama Perusahaan'],
                        ['data' => 'kategori', 'name' => 'kategori', 'title' => 'Kategori', 'class' => 'text-center'],
                        ['data' => 'kota', 'name' => 'kota', 'title' => 'Kota', 'class' => 'text-center'],
                        ['data' => 'telp', 'name' => 'telp', 'title' => 'Telepon', 'class' => 'text-center'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '15%']
                    ]"
                />
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#filter-kategori').on('change', function() {
        const val = $(this).val();
        const table = $('#table-perusahaan').DataTable();
        table.ajax.url("{{ route('eoffice.perusahaan.data') }}?kategori_id=" + val).load();
    });

    document.addEventListener('form-success', function(e) {
        $('#table-perusahaan').DataTable().ajax.reload();
    });
</script>
@endpush
