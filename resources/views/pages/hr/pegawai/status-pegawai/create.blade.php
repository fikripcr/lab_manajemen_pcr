@extends('layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ajukan Perubahan Status Pegawai</h3>
            </div>
            
            <form action="{{ route('hr.pegawai.status-pegawai.store', $pegawai->pegawai_id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="alert alert-info">
                        Status Pegawai saat ini: <strong>{{ $pegawai->latestStatusPegawai->statusPegawai->nama_status ?? 'Belum ada' }}</strong><br>
                        Perubahan yang Anda ajukan akan menunggu persetujuan admin sebelum efektif.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Status Baru</label>
                            <select class="form-select @error('statuspegawai_id') is-invalid @enderror" name="statuspegawai_id" required>
                                <option value="">Pilih Status</option>
                                @foreach($statusPegawai as $status)
                                    <option value="{{ $status->statuspegawai_id }}" {{ old('statuspegawai_id') == $status->statuspegawai_id ? 'selected' : '' }}>
                                        {{ $status->nama_status }} ({{ $status->kode_status }})
                                    </option>
                                @endforeach
                            </select>
                            @error('statuspegawai_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
