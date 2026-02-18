@extends('layouts.admin.app')

@section('header')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Detail Data
                </div>
                <h2 class="page-title">
                    Detail Pegawai: {{ $pegawai->nama }}
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <x-tabler.button href="{{ route('shared.pegawai.index') }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
                </div>
            </div>
        </div>
    </div>
</div>
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
