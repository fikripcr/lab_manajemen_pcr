@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Detail Mahasiswa: {{ $mahasiswa->nama }}" pretitle="Detail Data">
    <x-slot:actions>
        <x-tabler.button href="{{ route('shared.mahasiswa.index') }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <div class="datagrid">
                    <div class="datagrid-item">
                        <div class="datagrid-title">NIM</div>
                        <div class="datagrid-content">{{ $mahasiswa->nim }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Nama</div>
                        <div class="datagrid-content">{{ $mahasiswa->nama }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Email</div>
                        <div class="datagrid-content">{{ $mahasiswa->email }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Program Studi</div>
                        <div class="datagrid-content">{{ $mahasiswa->prodi->nama_prodi ?? '-' }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Angkatan</div>
                        <div class="datagrid-content">{{ $mahasiswa->angkatan ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
