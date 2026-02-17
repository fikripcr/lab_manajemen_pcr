@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Manajemen FAQ" pretitle="Info Publik">
    <x-slot:actions>
        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-ajax" data-url="{{ route('shared.faq.create') }}">
            <i class="ti ti-plus icon"></i>
            Tambah FAQ
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
                    id="table-faq"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'question', 'name' => 'question', 'title' => 'Pertanyaan'],
                        ['data' => 'category', 'name' => 'category', 'title' => 'Kategori'],
                        ['data' => 'seq', 'name' => 'seq', 'title' => 'Urutan'],
                        ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
                    ]"
                    :route="route('shared.faq.paginate')"
                />
            </div>
        </div>
    </div>
</div>
@endsection
