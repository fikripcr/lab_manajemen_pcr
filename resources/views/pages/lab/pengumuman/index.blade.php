@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header :title="ucfirst($type)" pretitle="Management">
    <x-slot:actions>
        <x-tabler.button :href="route('lab.'.$type . '.create', ['type' => $type])" class="btn-primary" icon="ti ti-plus" :text="'Tambah ' . ucfirst($type)" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="$type . '-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="$type . '-table'" />
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <x-tabler.flash-message />
            @php
                $columns = [
                    [
                        'title' => '#',
                        'data' => 'DT_RowIndex',
                        'name' => 'DT_RowIndex',
                        'orderable' => false,
                        'searchable' => false,
                        'className' => 'text-center'
                    ],
                    [
                        'title' => 'Cover',
                        'data' => 'cover',
                        'name' => 'cover',
                        'orderable' => false,
                        'searchable' => false
                    ],
                    [
                        'title' => 'Title',
                        'data' => 'judul',
                        'name' => 'judul',
                        'orderable' => true,
                        'searchable' => true
                    ],
                    [
                        'title' => 'Status',
                        'data' => 'is_published',
                        'name' => 'is_published',
                        'orderable' => true,
                        'searchable' => false
                    ],
                    [
                        'title' => 'Author',
                        'data' => 'author',
                        'name' => 'penulis.name',
                    ],
                    [
                        'title' => 'Dibuat Pada',
                        'data' => 'created_at',
                        'name' => 'created_at',
                    ],
                    [
                        'title' => 'Aksi',
                        'data' => 'action',
                        'name' => 'action',
                        'orderable' => false,
                        'searchable' => false,
                        'className' => 'text-end'
                    ]
                ];
            @endphp
            <x-tabler.datatable id="pengumuman-table" :route="route('lab.'.$type.'.data')" :columns="$columns" :order="[[4, 'desc']]" />
        </div>
    </div>
@endsection
