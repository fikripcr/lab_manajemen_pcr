@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Manajemen Slideshow" pretitle="Info Publik">
    <x-slot:actions>
        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-ajax" data-url="{{ route('shared.slideshow.create') }}">
            <i class="ti ti-plus icon"></i>
            Tambah Slideshow
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-slideshow"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'image_url', 'name' => 'image_url', 'title' => 'Preview', 'orderable' => false, 'searchable' => false],
                        ['data' => 'title', 'name' => 'title', 'title' => 'Judul'],
                        ['data' => 'seq', 'name' => 'seq', 'title' => 'Urutan'],
                        ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
                    ]"
                    :route="route('shared.slideshow.paginate')"
                />
            </div>
        </div>
    </div>
</div>
@endsection
