@extends('layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ajukan Penambahan Riwayat Pendidikan</h3>
            </div>
            
            <form action="{{ route('hr.pegawai.pendidikan.store', $pegawai->pegawai_id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="alert alert-info">
                        Data yang Anda tambahkan akan disimpan sebagai <strong>Draft / Menunggu Persetujuan</strong>.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Jenjang Pendidikan</label>
                            <select class="form-select @error('jenjang_pendidikan') is-invalid @enderror" name="jenjang_pendidikan" required>
                                <option value="">Pilih Jenjang</option>
                                <option value="D3">D3</option>
                                <option value="D4">D4</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                            @error('jenjang_pendidikan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Nama Perguruan Tinggi</label>
                            <input type="text" class="form-control @error('nama_pt') is-invalid @enderror" name="nama_pt" value="{{ old('nama_pt') }}" required>
                            @error('nama_pt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Bidang Ilmu / Jurusan</label>
                            <input type="text" class="form-control" name="bidang_ilmu" value="{{ old('bidang_ilmu') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tanggal Ijazah (Lulus)</label>
                            <input type="date" class="form-control @error('tgl_ijazah') is-invalid @enderror" name="tgl_ijazah" value="{{ old('tgl_ijazah') }}" required>
                            @error('tgl_ijazah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kota Asal PT</label>
                            <input type="text" class="form-control" name="kotaasal_pt" value="{{ old('kotaasal_pt') }}">
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
