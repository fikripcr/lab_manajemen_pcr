@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Manajemen Halaman Publik" pretitle="CMS">
    <x-slot:actions>
        <a href="{{ route('shared.public-page.create') }}" class="btn btn-primary d-none d-sm-inline-block">
            <i class="ti ti-plus"></i> Tambah Halaman
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Halaman</h3>
                    </div>
                    <div class="card-body border-bottom py-3">
                         <div class="d-flex">
                            <div class="text-muted">
                                Show
                                <div class="mx-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" value="10" size="3" aria-label="Invoices count">
                                </div>
                                entries
                            </div>
                            <div class="ms-auto text-muted">
                                Search:
                                <div class="ms-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <x-tabler.datatable
                            id="table-public-pages"
                            route="{{ route('shared.public-page.index') }}"
                            :columns="[
                                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                                ['data' => 'title', 'name' => 'title', 'title' => 'Judul'],
                                ['data' => 'slug', 'name' => 'slug', 'title' => 'Slug'],
                                ['data' => 'is_published', 'name' => 'is_published', 'title' => 'Status'],
                                ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Terakhir Update'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                            ]"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Simple script if we want to enhance the table later (e.g. client side search)
    // For now, it's a basic table as per "except pages" instruction implying valid list view.
    // If user meant DataTables, I can switch back, but this is a standard list view.
    // Given the Controller returns `get()`, this fits.
</script>
@endpush
