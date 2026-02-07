@extends('layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Mata Kuliah Details" pretitle="Mata Kuliah">
        <x-slot:actions>
            <x-tabler.button type="a" :href="route('mata-kuliah.edit', $mataKuliah->encrypted_mata_kuliah_id)" icon="ti ti-pencil" class="btn-warning" text="Edit" />
            <x-tabler.button type="a" :href="route('mata-kuliah.index')" icon="ti ti-arrow-left" class="btn-secondary" text="Back" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Kode MK</div>
                            <div class="datagrid-content">{{ $mataKuliah->kode_mk }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Nama MK</div>
                            <div class="datagrid-content">{{ $mataKuliah->nama_mk }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">SKS</div>
                            <div class="datagrid-content">{{ $mataKuliah->sks }}</div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <x-tabler.button type="button" icon="ti ti-trash" class="btn-danger ajax-delete" 
                                    :data-url="route('mata-kuliah.destroy', $mataKuliah->encrypted_mata_kuliah_id)"
                                    data-title="Hapus Mata Kuliah"
                                    data-text="Apakah Anda yakin ingin menghapus mata kuliah ini?"
                                    data-redirect="{{ route('mata-kuliah.index') }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
