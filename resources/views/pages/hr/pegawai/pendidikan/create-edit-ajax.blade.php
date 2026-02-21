<x-tabler.form-modal
    :title="isset($pendidikan) && $pendidikan->exists ? 'Edit Riwayat Pendidikan' : 'Tambah Riwayat Pendidikan'"
    :route="isset($pendidikan) && $pendidikan->exists ? route('hr.pegawai.pendidikan.update', [$pegawai->encrypted_pegawai_id, $pendidikan->riwayatpendidikan_id]) : route('hr.pegawai.pendidikan.store', $pegawai->encrypted_pegawai_id)"
    :method="isset($pendidikan) && $pendidikan->exists ? 'PUT' : 'POST'"
    :submitText="isset($pendidikan) && $pendidikan->exists ? 'Simpan Perubahan' : 'Simpan'"
    submitIcon="ti ti-device-floppy"
>
    <div class="alert alert-info">
        <i class="ti ti-info-circle me-2"></i>
        Data pendidikan yang {{ isset($pendidikan) && $pendidikan->exists ? 'diubah' : 'ditambahkan' }} akan berstatus <strong>Menunggu Persetujuan</strong>.
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-select name="jenjang_pendidikan" label="Jenjang Pendidikan" required="true">
                <option value="">Pilih Jenjang</option>
                <option value="D3" {{ (isset($pendidikan) && $pendidikan->jenjang_pendidikan == 'D3') ? 'selected' : '' }}>D3</option>
                <option value="D4" {{ (isset($pendidikan) && $pendidikan->jenjang_pendidikan == 'D4') ? 'selected' : '' }}>D4</option>
                <option value="S1" {{ (isset($pendidikan) && $pendidikan->jenjang_pendidikan == 'S1') ? 'selected' : '' }}>S1</option>
                <option value="S2" {{ (isset($pendidikan) && $pendidikan->jenjang_pendidikan == 'S2') ? 'selected' : '' }}>S2</option>
                <option value="S3" {{ (isset($pendidikan) && $pendidikan->jenjang_pendidikan == 'S3') ? 'selected' : '' }}>S3</option>
            </x-tabler.form-select>
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="nama_pt" label="Nama Perguruan Tinggi" value="{{ $pendidikan->nama_pt ?? '' }}" required="true" />
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label required">Bidang Ilmu / Jurusan</label>
            <x-tabler.form-input name="bidang_ilmu" label="Bidang Ilmu" value="{{ $pendidikan->bidang_ilmu ?? '' }}" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_ijazah" label="Tanggal Ijazah (Lulus)" value="{{ isset($pendidikan) && $pendidikan->tgl_ijazah ? \Carbon\Carbon::parse($pendidikan->tgl_ijazah)->format('Y-m-d') : '' }}" required="true" />
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Kota Asal PT</label>
            <x-tabler.form-input name="kotaasal_pt" label="Kota PT" value="{{ $pendidikan->kotaasal_pt ?? '' }}" />
        </div>
        
        <div class="col-md-6 mb-3">
            <label class="form-label">Negara Asal PT</label>
            <x-tabler.form-input name="kodenegara_pt" label="Negara PT" value="{{ $pendidikan->kodenegara_pt ?? '' }}" placeholder="Indonesia" />
        </div>
    </div>
</x-tabler.form-modal>
