@extends('layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ajukan Penambahan Anggota Keluarga</h3>
            </div>
            
            <form action="{{ route('hr.pegawai.keluarga.store', $pegawai->pegawai_id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="alert alert-info">
                        Data yang Anda tambahkan akan disimpan sebagai <strong>Draft / Menunggu Persetujuan</strong>. 
                        Data tidak akan muncul di profil publik sampai disetujui oleh admin.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama') }}" required>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Hubungan</label>
                            <select class="form-select @error('hubungan') is-invalid @enderror" name="hubungan" required>
                                <option value="">Pilih Hubungan</option>
                                <option value="Suami">Suami</option>
                                <option value="Istri">Istri</option>
                                <option value="Anak">Anak</option>
                                <option value="Orang Tua">Orang Tua</option>
                            </select>
                            @error('hubungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Jenis Kelamin</label>
                            <div>
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="L" checked>
                                    <span class="form-check-label">Laki-laki</span>
                                </label>
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="P">
                                    <span class="form-check-label">Perempuan</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tgl_lahir" value="{{ old('tgl_lahir') }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="2">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('hr.pegawai.show', $pegawai->pegawai_id) }}" class="btn btn-link link-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
