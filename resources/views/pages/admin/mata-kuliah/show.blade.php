@extends('layouts.admin.app')

@section('header')
    <x-sys.page-header title="Mata Kuliah Details" pretitle="Mata Kuliah">
        <x-slot:actions>
            <x-sys.button type="edit" :href="route('mata-kuliah.edit', $mataKuliah->encrypted_mata_kuliah_id)" />
            <x-sys.button type="back" :href="route('mata-kuliah.index')" />
        </x-slot:actions>
    </x-sys.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-admin.flash-message />

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
                        <x-sys.button type="delete" 
                                    class="ajax-delete"
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
