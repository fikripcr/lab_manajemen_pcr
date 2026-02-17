@extends('layouts.admin.app')

@section('header')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Detail Data</div>
                <h2 class="page-title">Detail Mahasiswa: {{ $mahasiswa->nama }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('shared.mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
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
