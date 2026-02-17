@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Formulir Pendaftaran</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('pmb.camaba.store') }}" method="POST" class="ajax-form" data-redirect="true">
                    @csrf
                    <input type="hidden" name="periode_id" value="{{ $periodeAktif->id }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title">Informasi Pendaftaran</h3>
                            <x-tabler.form-input label="Periode" value="{{ $periodeAktif->nama_periode }}" readonly="true" />
                            
                            <div class="mb-3">
                                <label class="form-label required">Jalur Pendaftaran</label>
                                <select name="jalur_id" class="form-select" required>
                                    <option value="">-- Pilih Jalur --</option>
                                    @foreach($jalur as $j)
                                        <option value="{{ $j->id }}">{{ $j->nama_jalur }} (Rp {{ number_format($j->biaya_pendaftaran, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Pilihan Program Studi (Maks 2)</label>
                                <select name="pilihan_prodi[]" class="form-select" multiple size="5" required>
                                    @foreach($prodi as $p)
                                        <option value="{{ $p->orgunit_id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Tekan Ctrl untuk memilih lebih dari satu (Jika jalur mengijinkan).</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h3 class="card-title">Data Diri Singkat</h3>
                            <x-tabler.form-input name="nik" label="NIK (Nomor Induk Kependudukan)" value="{{ $profil->nik ?? '' }}" required="true" maxlength="16" />
                            <x-tabler.form-input name="no_hp" label="No. WhatsApp/HP" value="{{ $profil->no_hp ?? '' }}" required="true" />
                            
                            <div class="row">
                                <div class="col-6">
                                    <x-tabler.form-input name="tempat_lahir" label="Tempat Lahir" value="{{ $profil->tempat_lahir ?? '' }}" required="true" />
                                </div>
                                <div class="col-6">
                                    <x-tabler.form-input type="date" name="tanggal_lahir" label="Tanggal Lahir" value="{{ $profil->tanggal_lahir ?? '' }}" required="true" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Jenis Kelamin</label>
                                <div class="form-selectgroup">
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="jenis_kelamin" value="L" class="form-selectgroup-input" {{ ($profil && $profil->jenis_kelamin == 'L') ? 'checked' : '' }} required>
                                        <span class="form-selectgroup-label">Laki-laki</span>
                                    </label>
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="jenis_kelamin" value="P" class="form-selectgroup-input" {{ ($profil && $profil->jenis_kelamin == 'P') ? 'checked' : '' }}>
                                        <span class="form-selectgroup-label">Perempuan</span>
                                    </label>
                                </div>
                            </div>

                            <x-tabler.form-textarea name="alamat_lengkap" label="Alamat Lengkap" required="true">{{ $profil->alamat_lengkap ?? '' }}</x-tabler.form-textarea>
                            <x-tabler.form-input name="asal_sekolah" label="Asal Sekolah (SMA/SMK/MA)" value="{{ $profil->asal_sekolah ?? '' }}" required="true" />
                            <x-tabler.form-input name="nama_ibu_kandung" label="Nama Ibu Kandung" value="{{ $profil->nama_ibu_kandung ?? '' }}" required="true" />
                        </div>
                    </div>

                    <div class="card-footer text-end px-0">
                        <button type="submit" class="btn btn-primary btn-lg">Kirim Pendaftaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
