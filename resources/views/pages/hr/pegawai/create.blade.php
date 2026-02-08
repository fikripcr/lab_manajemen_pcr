@extends('layouts.admin.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <h2 class="page-title">Tambah Pegawai</h2>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('hr.pegawai.store') }}" method="POST" id="form-pegawai" class="ajax-form" enctype="multipart/form-data">
    @csrf
    
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                <li class="nav-item">
                    <a href="#tab-datadiri" class="nav-link active" data-bs-toggle="tab">Data Diri</a>
                </li>
                <li class="nav-item">
                    <a href="#tab-kepegawaian" class="nav-link" data-bs-toggle="tab">Informasi Kepegawaian</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- Tab Data Diri -->
                <div class="tab-pane active show" id="tab-datadiri">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap dengan Gelar" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Inisial</label>
                            <input type="text" class="form-control" name="inisial" placeholder="Ex: ABC">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">NIP / NIK</label>
                            <input type="text" class="form-control" name="nip" placeholder="Nomor Induk Pegawai">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lahir" placeholder="Kota Kelahiran">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tgl_lahir">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="email@pcr.ac.id">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input type="text" class="form-control" name="no_hp" placeholder="0812...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" name="alamat" rows="3"></textarea>
                    </div>
                </div>

                <!-- Tab Kepegawaian -->
                <div class="tab-pane" id="tab-kepegawaian">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Status Pegawai</label>
                            <select class="form-select" name="statpegawai_id" required>
                                <option value="">Pilih Status Pegawai</option>
                                @foreach($statusPegawai as $sp)
                                    <option value="{{ $sp->statpegawai_id }}">{{ $sp->statpegawai }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Status Aktifitas</label>
                            <select class="form-select" name="stataktifitas_id" required>
                                <option value="">Pilih Status Aktifitas</option>
                                @foreach($statusAktifitas as $sa)
                                    <option value="{{ $sa->stataktifitas_id }}">{{ $sa->stataktifitas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        Penugasan ke unit/jabatan dapat dilakukan setelah pegawai tersimpan melalui menu <strong>Riwayat Penugasan</strong>.
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('hr.pegawai.index') }}" class="btn btn-link">Kembali</a>
            <button type="submit" class="btn btn-primary">
                <i class="ti ti-device-floppy me-1"></i> Simpan Pegawai
            </button>
        </div>
    </div>
</form>
@endsection
