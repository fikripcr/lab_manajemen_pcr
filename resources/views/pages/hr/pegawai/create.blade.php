@extends('layouts.admin.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <h2 class="page-title">
            Tambah Pegawai
        </h2>
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
                {{-- Other tabs can be added here as needed, but for initial creation usually basic info is first --}}
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
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Unit / Departemen</label>
                            <select class="form-select" name="departemen_id">
                                <option value="">Pilih Departemen</option>
                                @foreach($departemen as $d)
                                    <option value="{{ $d->departemen_id }}">{{ $d->departemen }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Program Studi (Jika Ada)</label>
                            <select class="form-select" name="prodi_id">
                                <option value="">Pilih Prodi</option>
                                @foreach($prodi as $p)
                                    <option value="{{ $p->prodi_id }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Posisi</label>
                            <select class="form-select" name="posisi_id">
                                <option value="">Pilih Posisi</option>
                                @foreach($posisi as $p)
                                    <option value="{{ $p->posisi_id }}">{{ $p->posisi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('hr.pegawai.index') }}" class="btn btn-link">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Data Pegawai</button>
        </div>
    </div>
</form>
@endsection
