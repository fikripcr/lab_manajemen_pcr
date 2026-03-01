@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Calon Mahasiswa Baru" pretitle="PMB">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pmb.camaba.create') }}" class="btn-primary" icon="ti ti-plus" text="Tambah Camaba" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <x-tabler.datatable
            id="table-camaba"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                ['data' => 'nik', 'name' => 'nik', 'title' => 'NIK'],
                ['data' => 'nama', 'name' => 'nama', 'title' => 'Nama'],
                ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
                ['data' => 'no_hp', 'name' => 'no_hp', 'title' => 'No HP'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ]"
            :route="route('pmb.camaba.data')"
        />
    </div>
</div>
@endsection
