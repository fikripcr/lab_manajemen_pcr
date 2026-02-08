@extends('layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ajukan Perubahan Status Aktifitas</h3>
            </div>
            
            <form action="{{ route('hr.pegawai.status-aktifitas.store', $pegawai->pegawai_id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="alert alert-info">
                        Status Aktifitas saat ini: <strong>{{ $pegawai->latestStatusAktifitas->statusAktifitas->status_aktifitas ?? 'Belum ada' }}</strong><br>
                        Perubahan yang Anda ajukan akan menunggu persetujuan admin sebelum efektif.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Status Aktifitas Baru</label>
                            <select class="form-select @error('statusaktifitas_id') is-invalid @enderror" name="statusaktifitas_id" required>
                                <option value="">Pilih Status</option>
                                @foreach($statusAktifitas as $status)
                                    <option value="{{ $status->statusaktifitas_id }}" {{ old('statusaktifitas_id') == $status->statusaktifitas_id ? 'selected' : '' }}>
                                        {{ $status->status_aktifitas }} ({{ $status->kode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('statusaktifitas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">TMT (Terhitung Mulai Tanggal)</label>
                            <input type="date" class="form-control @error('tmt') is-invalid @enderror" name="tmt" value="{{ old('tmt') }}" required>
                            @error('tmt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No SK</label>
                            <input type="text" class="form-control" name="no_sk" value="{{ old('no_sk') }}">
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
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
