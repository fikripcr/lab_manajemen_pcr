@extends('layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ajukan Penambahan Riwayat Pengembangan Diri</h3>
            </div>
            
            <form action="{{ route('hr.pegawai.pengembangan.store', $pegawai->pegawai_id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="alert alert-info">
                        Data yang Anda tambahkan akan disimpan sebagai <strong>Draft / Menunggu Persetujuan</strong>.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Jenis Kegiatan</label>
                            <select class="form-select @error('jenis_kegiatan') is-invalid @enderror" name="jenis_kegiatan" required>
                                <option value="">Pilih Jenis Kegiatan</option>
                                <option value="Pelatihan">Pelatihan</option>
                                <option value="Seminar">Seminar</option>
                                <option value="Workshop">Workshop</option>
                                <option value="Sertifikasi">Sertifikasi</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            @error('jenis_kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Nama Kegiatan</label>
                            <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required>
                            @error('nama_kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penyelenggara</label>
                            <input type="text" class="form-control" name="nama_penyelenggara" value="{{ old('nama_penyelenggara') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Peran</label>
                            <input type="text" class="form-control" name="peran" value="{{ old('peran') }}" placeholder="Contoh: Peserta, Narasumber">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tanggal Mulai</label>
                            <input type="date" class="form-control @error('tgl_mulai') is-invalid @enderror" name="tgl_mulai" value="{{ old('tgl_mulai') }}" required>
                            @error('tgl_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control @error('tgl_selesai') is-invalid @enderror" name="tgl_selesai" value="{{ old('tgl_selesai') }}">
                            @error('tgl_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
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
