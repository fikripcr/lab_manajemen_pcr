@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Detail Mahasiswa
            </h2>
            <div class="text-muted mt-1">Master Data / Mahasiswa / Detail</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <x-tabler.button type="edit" href="{{ route('lab.mahasiswa.edit', encryptId($mahasiswa->mahasiswa_id)) }}" />
                <x-tabler.button type="back" href="{{ route('lab.mahasiswa.index') }}" />
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($mahasiswa->user && $mahasiswa->user->avatar_url)
                        <span class="avatar avatar-xl mb-3" style="background-image: url('{{ $mahasiswa->user->avatar_url }}')"></span>
                    @else
                        <span class="avatar avatar-xl mb-3 bg-primary-lt">{{ substr($mahasiswa->nama, 0, 2) }}</span>
                    @endif
                    <h3 class="m-0">{{ $mahasiswa->nama }}</h3>
                    <div class="text-muted">{{ $mahasiswa->nim }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Mahasiswa</h3>
                </div>
                <div class="card-body">
                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">NIM</div>
                            <div class="datagrid-content">{{ $mahasiswa->nim }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Nama Lengkap</div>
                            <div class="datagrid-content">{{ $mahasiswa->nama }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Email</div>
                            <div class="datagrid-content">
                                @if($mahasiswa->email)
                                    <a href="mailto:{{ $mahasiswa->email }}">{{ $mahasiswa->email }}</a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Program Studi</div>
                            <div class="datagrid-content">{{ $mahasiswa->program_studi ?? '-' }}</div>
                        </div>
                        @if($mahasiswa->user)
                        <div class="datagrid-item">
                            <div class="datagrid-title">User Account</div>
                            <div class="datagrid-content">
                                <span class="badge bg-green">{{ $mahasiswa->user->name }}</span>
                            </div>
                        </div>
                        @endif
                        <div class="datagrid-item">
                            <div class="datagrid-title">Dibuat</div>
                            <div class="datagrid-content">{{ formatTanggalIndo($mahasiswa->created_at) }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Diperbarui</div>
                            <div class="datagrid-content">{{ formatTanggalIndo($mahasiswa->updated_at) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
