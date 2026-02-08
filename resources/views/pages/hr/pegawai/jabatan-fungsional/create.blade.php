@extends('layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ajukan Perubahan Jabatan Fungsional</h3>
            </div>
            
            <form action="{{ route('hr.pegawai.jabatan-fungsional.store', $pegawai->pegawai_id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="alert alert-info">
                        Jabatan Fungsional saat ini: <strong>{{ $pegawai->latestJabatanFungsional->jabatanFungsional->jabfungsional ?? 'Belum ada' }}</strong><br>
                        Perubahan yang Anda ajukan akan menunggu persetujuan admin sebelum efektif.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Jabatan Fungsional Baru</label>
                            <select class="form-select @error('jabfungsional_id') is-invalid @enderror" name="jabfungsional_id" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach($jabatan as $item)
                                    <option value="{{ $item->jabfungsional_id }}" {{ old('jabfungsional_id') == $item->jabfungsional_id ? 'selected' : '' }}>
                                        {{ $item->jabfungsional }} ({{ $item->kode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('jabfungsional_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">TMT (Terhitung Mulai Tanggal)</label>
                            <input type="date" class="form-control @error('tmt') is-invalid @enderror" name="tmt" value="{{ old('tmt') }}" required>
                            @error('tmt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No SK (Internal)</label>
                            <input type="text" class="form-control" name="no_sk_internal" value="{{ old('no_sk_internal') }}">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('hr.pegawai.show', $pegawai->pegawai_id) }}" class="btn btn-link link-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Ajukan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
