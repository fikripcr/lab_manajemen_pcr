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
                            <x-tabler.form-select class="select2-offline @error('org_unit_id') is-invalid @enderror" name="org_unit_id" label="Jabatan Struktural Baru" required="true">
                                <option value="">Pilih Jabatan</option>
                                @foreach($jabatan as $item)
                                    <option value="{{ $item->org_unit_id }}" {{ old('org_unit_id') == $item->org_unit_id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </x-tabler.form-select>
                            @error('org_unit_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-tabler.form-input type="date" name="tgl_awal" label="Tanggal Mulai (Tgl Awal)" value="{{ old('tgl_awal') }}" required="true" />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-tabler.form-input type="date" name="tgl_akhir" label="Tanggal Selesai (Opsional)" value="{{ old('tgl_akhir') }}" />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-tabler.form-input name="no_sk" label="Nomor SK" value="{{ old('no_sk') }}" />
                        </div>

                        <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="2" />
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
