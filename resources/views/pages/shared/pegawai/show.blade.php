@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Detail Pegawai: {{ $pegawai->nama }}" pretitle="Detail Data">
    <x-slot:actions>
        <x-tabler.button href="{{ route('shared.pegawai.index') }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
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
                        <div class="datagrid-title">NIP</div>
                        <div class="datagrid-content">{{ $pegawai->nip }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Nama</div>
                        <div class="datagrid-content">{{ $pegawai->nama }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Email</div>
                        <div class="datagrid-content">{{ $pegawai->email }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Unit Kerja</div>
                        <div class="datagrid-content">{{ $pegawai->unitKerja->name ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
