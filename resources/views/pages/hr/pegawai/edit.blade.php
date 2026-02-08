@extends('layouts.admin.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <div class="page-pretitle">Edit Data</div>
        <h2 class="page-title">
            Pengajuan Perubahan Data: {{ $pegawai->nama }}
        </h2>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('hr.pegawai.update', $pegawai->pegawai_id) }}" method="POST" id="form-pegawai-edit" class="ajax-form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="card">
        <div class="alert alert-info m-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
            Setiap perubahan yang Anda lakukan akan disimpan sebagai <strong>Draft / Menunggu Persetujuan</strong> dan tidak akan langsung mengubah data aktif sampai disetujui oleh Administrator.
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama" value="{{ old('nama', $pegawai->latestDataDiri->nama ?? $pegawai->nama) }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Inisial</label>
                    <input type="text" class="form-control" name="inisial" value="{{ old('inisial', $pegawai->latestDataDiri->inisial ?? $pegawai->inisial) }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">NIP / NIK</label>
                    <input type="text" class="form-control" name="nip" value="{{ old('nip', $pegawai->latestDataDiri->nip ?? $pegawai->nip) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir', $pegawai->latestDataDiri->tempat_lahir ?? '') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="tgl_lahir" value="{{ old('tgl_lahir', optional($pegawai->latestDataDiri->tgl_lahir)->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email', $pegawai->latestDataDiri->email ?? $pegawai->email) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" name="no_hp" value="{{ old('no_hp', $pegawai->latestDataDiri->no_hp ?? '') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea class="form-control" name="alamat" rows="3">{{ old('alamat', $pegawai->latestDataDiri->alamat ?? '') }}</textarea>
            </div>

            <h3 class="card-title mt-4">Informasi Kepegawaian</h3>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Unit / Departemen</label>
                    <select class="form-select" name="departemen_id">
                        <option value="">Pilih Departemen</option>
                        @foreach($departemen as $d)
                            <option value="{{ $d->departemen_id }}" {{ (old('departemen_id', $pegawai->latestDataDiri->departemen_id ?? '') == $d->departemen_id) ? 'selected' : '' }}>
                                {{ $d->departemen }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Program Studi</label>
                    <select class="form-select" name="prodi_id">
                        <option value="">Pilih Prodi</option>
                        @foreach($prodi as $p)
                            <option value="{{ $p->prodi_id }}" {{ (old('prodi_id', $pegawai->latestDataDiri->prodi_id ?? '') == $p->prodi_id) ? 'selected' : '' }}>
                                {{ $p->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Posisi</label>
                    <select class="form-select" name="posisi_id">
                        <option value="">Pilih Posisi</option>
                        @foreach($posisi as $p)
                            <option value="{{ $p->posisi_id }}" {{ (old('posisi_id', $pegawai->latestDataDiri->posisi_id ?? '') == $p->posisi_id) ? 'selected' : '' }}>
                                {{ $p->posisi }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
        <div class="card-footer text-end">
            <a href="{{ route('hr.pegawai.show', $pegawai->pegawai_id) }}" class="btn btn-link">Batal</a>
            <button type="submit" class="btn btn-primary">Ajukan Perubahan</button>
        </div>
    </div>
</form>
@endsection
