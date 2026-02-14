@extends('layouts.admin.app')

@push('css')
@endpush

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Detail Jadwal Kuliah
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Jadwal</h3>
                            <div class="card-actions">
                                <a href="{{ route('lab.jadwal.edit', encryptId($jadwal->jadwal_kuliah_id)) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-2"></i> Edit
                                </a>
                                <a href="{{ route('lab.jadwal.assignments.index', encryptId($jadwal->jadwal_kuliah_id)) }}" class="btn btn-info ms-2">
                                    <i class="ti ti-desktop me-2"></i> Atur PC
                                </a>
                                <a href="{{ route('lab.jadwal.index') }}" class="btn btn-light ms-2">Kembali</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Tahun Ajaran</div>
                                    <div class="datagrid-content">{{ $jadwal->semester->tahun_ajaran ?? '-' }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Semester</div>
                                    <div class="datagrid-content">{{ $jadwal->semester->semester ?? '-' }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Kode MK</div>
                                    <div class="datagrid-content">{{ $jadwal->mataKuliah->kode_mk ?? '-' }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Mata Kuliah</div>
                                    <div class="datagrid-content">{{ $jadwal->mataKuliah->nama_mk ?? '-' }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Dosen Pengampu</div>
                                    <div class="datagrid-content">
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-xs me-2 rounded" style="background-image: url({{ $jadwal->dosen->avatar_url ?? '' }})"></span>
                                            {{ $jadwal->dosen->name ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Lab / Ruangan</div>
                                    <div class="datagrid-content">{{ $jadwal->lab->name ?? '-' }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Hari</div>
                                    <div class="datagrid-content">{{ $jadwal->hari }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Waktu</div>
                                    <div class="datagrid-content">
                                        {{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
