<x-tabler.form-modal
    title="Edit Riwayat Pengembangan"
    route="{{ route('hr.pegawai.pengembangan.update', [$pegawai->encrypted_pegawai_id, $pengembangan->pengembangandiri_id]) }}"
    method="PUT"
    submitText="Simpan Perubahan"
    submitIcon="ti ti-device-floppy"
>
    <div class="alert alert-info">
        <i class="ti ti-info-circle me-2"></i>
        Perubahan data akan berstatus <strong>Menunggu Persetujuan</strong>.
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-select name="jenis_kegiatan" label="Jenis Kegiatan" required="true">
                <option value="">Pilih Jenis Kegiatan</option>
                <option value="Pelatihan" {{ $pengembangan->jenis_kegiatan == 'Pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                <option value="Seminar" {{ $pengembangan->jenis_kegiatan == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                <option value="Workshop" {{ $pengembangan->jenis_kegiatan == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                <option value="Sertifikasi" {{ $pengembangan->jenis_kegiatan == 'Sertifikasi' ? 'selected' : '' }}>Sertifikasi</option>
                <option value="Lainnya" {{ $pengembangan->jenis_kegiatan == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </x-tabler.form-select>
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="nama_kegiatan" label="Nama Kegiatan" value="{{ $pengembangan->nama_kegiatan }}" required="true" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="nama_penyelenggara" label="Penyelenggara" value="{{ $pengembangan->nama_penyelenggara }}" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="peran" label="Peran" value="{{ $pengembangan->peran }}" placeholder="Contoh: Peserta, Narasumber" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="tgl_mulai" label="Tanggal Mulai" type="date" value="{{ $pengembangan->tgl_mulai ? \Carbon\Carbon::parse($pengembangan->tgl_mulai)->format('Y-m-d') : '' }}" required="true" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="tgl_selesai" label="Tanggal Selesai" type="date" value="{{ $pengembangan->tgl_selesai ? \Carbon\Carbon::parse($pengembangan->tgl_selesai)->format('Y-m-d') : '' }}" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="tahun" label="Tahun" type="number" value="{{ $pengembangan->tahun }}" placeholder="YYYY" required="true" />
        </div>

        <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="3" :value="$pengembangan->keterangan" />
    </div>
</x-tabler.form-modal>
