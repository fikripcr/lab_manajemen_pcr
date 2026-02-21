<x-tabler.form-modal
    :title="isset($riwayat) && $riwayat->exists ? 'Edit Jabatan Struktural' : 'Ajukan Perubahan Jabatan Struktural'"
    :route="isset($riwayat) && $riwayat->exists ? route('hr.pegawai.jabatan-struktural.update', ['pegawai' => $pegawai->pegawai_id ?? $pegawai->encrypted_pegawai_id, 'riwayat' => $riwayat->id]) : route('hr.pegawai.jabatan-struktural.store', $pegawai->pegawai_id ?? $pegawai->encrypted_pegawai_id)"
    :method="isset($riwayat) && $riwayat->exists ? 'PUT' : 'POST'"
    :submitText="isset($riwayat) && $riwayat->exists ? 'Simpan Perubahan' : 'Ajukan Perubahan'"
>
    <div class="alert alert-info border-0 shadow-sm mb-4">
        <div class="d-flex">
            <div>
                <i class="ti ti-info-circle fs-2 me-2"></i>
            </div>
            <div>
                <h4 class="alert-title">Informasi Jabatan</h4>
                <div class="text-muted">
                    Jabatan Struktural saat ini: <strong>{{ $pegawai->latestJabatanStruktural->orgUnit->name ?? 'Belum ada' }}</strong><br>
                    Perubahan yang Anda ajukan akan menunggu persetujuan admin sebelum efektif.
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-select class="select2-offline" name="org_unit_id" label="Jabatan Struktural Baru" required="true" data-dropdown-parent="#modalAction">
                <option value="">Pilih Jabatan</option>
                @foreach($jabatan as $item)
                    <option value="{{ $item->org_unit_id }}" {{ (isset($riwayat) && $riwayat->org_unit_id == $item->org_unit_id) || old('org_unit_id') == $item->org_unit_id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_awal" label="Tanggal Mulai (Tgl Awal)" value="{{ isset($riwayat) && $riwayat->tgl_awal ? \Carbon\Carbon::parse($riwayat->tgl_awal)->format('Y-m-d') : old('tgl_awal') }}" required="true" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_akhir" label="Tanggal Selesai (Opsional)" value="{{ isset($riwayat) && $riwayat->tgl_akhir ? \Carbon\Carbon::parse($riwayat->tgl_akhir)->format('Y-m-d') : old('tgl_akhir') }}" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="no_sk" label="Nomor SK" value="{{ $riwayat->no_sk ?? old('no_sk') }}" />
        </div>

        <div class="col-12">
            <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="2" :value="$riwayat->keterangan ?? old('keterangan')" />
        </div>
    </div>
</x-tabler.form-modal>
