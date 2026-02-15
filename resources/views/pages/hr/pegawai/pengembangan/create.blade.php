<form action="{{ route('hr.pegawai.pengembangan.store', $pegawai->encrypted_pegawai_id) }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
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
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal" text="Batal" />
        <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy" text="Simpan Pengajuan" />
    </div>
</form>
