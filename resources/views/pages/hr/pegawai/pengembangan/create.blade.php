<x-tabler.form-modal
    title="Tambah Riwayat Pengembangan"
    route="{{ route('hr.pegawai.pengembangan.store', $pegawai->encrypted_pegawai_id) }}"
    method="POST"
    submitText="Simpan Pengajuan"
    submitIcon="ti ti-device-floppy"
>
    <div class="alert alert-info">
        <i class="ti ti-info-circle me-2"></i>
        Data yang Anda tambahkan akan disimpan sebagai <strong>Draft / Menunggu Persetujuan</strong>.
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-select name="jenis_kegiatan" label="Jenis Kegiatan" required="true">
                <option value="">Pilih Jenis Kegiatan</option>
                <option value="Pelatihan">Pelatihan</option>
                <option value="Seminar">Seminar</option>
                <option value="Workshop">Workshop</option>
                <option value="Sertifikasi">Sertifikasi</option>
                <option value="Lainnya">Lainnya</option>
            </x-tabler.form-select>
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="nama_kegiatan" label="Nama Kegiatan" required="true" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="nama_penyelenggara" label="Penyelenggara" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="peran" label="Peran" placeholder="Contoh: Peserta, Narasumber" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="tgl_mulai" label="Tanggal Mulai" type="date" required="true" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="tgl_selesai" label="Tanggal Selesai" type="date" />
        </div>

        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="tahun" label="Tahun" type="number" placeholder="YYYY" required="true" />
        </div>

        <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="3" />
    </div>
</x-tabler.form-modal>
