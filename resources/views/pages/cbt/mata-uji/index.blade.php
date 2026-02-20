@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Management Mata Uji (CBT)" pretitle="CBT">
    <x-slot:actions>
        <x-tabler.button type="button" class="btn-primary ajax-modal-btn" data-modal-title="Tambah Mata Uji" data-url="{{ route('cbt.mata-uji.create') }}" icon="ti ti-plus" text="Tambah Mata Uji" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-mata-uji" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nama_mata_uji', 'name' => 'nama_mata_uji', 'title' => 'Nama Mata Uji'],
                        ['data' => 'tipe', 'name' => 'tipe', 'title' => 'Tipe'],
                        ['data' => 'durasi_menit', 'name' => 'durasi_menit', 'title' => 'Durasi (Menit)'],
                        ['data' => 'deskripsi', 'name' => 'deskripsi', 'title' => 'Deskripsi'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('cbt.mata-uji.paginate')"
                />
            </div>
        </div>
    </div>
</div>

@endsection
