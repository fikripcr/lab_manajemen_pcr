@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ajukan Perubahan Jabatan Struktural</h3>
            </div>
            
            <form action="{{ route('hr.pegawai.jabatan-struktural.store', $pegawai->pegawai_id) }}" method="POST" class="ajax-form">
                @csrf
                <div class="card-body">
                    <div class="alert alert-info">
                        Jabatan Struktural saat ini: <strong>{{ $pegawai->latestJabatanStruktural->orgUnit->name ?? 'Belum ada' }}</strong><br>
                        Perubahan yang Anda ajukan akan menunggu persetujuan admin sebelum efektif.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Jabatan Struktural Baru</label>
                            <select class="form-select select2-offline @error('org_unit_id') is-invalid @enderror" name="org_unit_id" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach($jabatan as $item)
                                    <option value="{{ $item->org_unit_id }}" {{ old('org_unit_id') == $item->org_unit_id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('org_unit_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tanggal Mulai (Tgl Awal)</label>
                            <input type="date" class="form-control @error('tgl_awal') is-invalid @enderror" name="tgl_awal" value="{{ old('tgl_awal') }}" required>
                            @error('tgl_awal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai (Opsional)</label>
                            <input type="date" class="form-control @error('tgl_akhir') is-invalid @enderror" name="tgl_akhir" value="{{ old('tgl_akhir') }}">
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
