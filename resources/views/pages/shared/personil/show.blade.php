@extends('layouts.admin.app')

@section('header')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Detail Data</div>
                <h2 class="page-title">Detail Personil: {{ $personil->nama }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('shared.personil.index') }}" class="btn btn-secondary">Kembali</a>
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
                        <div class="datagrid-title">Nama</div>
                        <div class="datagrid-content">{{ $personil->nama }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Email</div>
                        <div class="datagrid-content">{{ $personil->email }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Posisi</div>
                        <div class="datagrid-content">{{ $personil->posisi }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Vendor</div>
                        <div class="datagrid-content">{{ $personil->vendor }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Unit Kerja</div>
                        <div class="datagrid-content">{{ $personil->unitKerja->name ?? '-' }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Phone</div>
                        <div class="datagrid-content">{{ $personil->phone ?? '-' }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Status</div>
                        <div class="datagrid-content">
                            @if($personil->status_aktif)
                            <span class="badge bg-success text-white">Aktif</span>
                            @else
                            <span class="badge bg-danger text-white">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
