<form action="{{ route('hr.pegawai.pengembangan.store', $pegawai->encrypted_pegawai_id) }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Data yang Anda tambahkan akan disimpan sebagai <strong>Draft / Menunggu Persetujuan</strong>.
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Jenis Kegiatan</label>
                <select class="form-select" name="jenis_kegiatan" required>
                    <option value="">Pilih Jenis Kegiatan</option>
                    <option value="Pelatihan">Pelatihan</option>
                    <option value="Seminar">Seminar</option>
                    <option value="Workshop">Workshop</option>
                    <option value="Sertifikasi">Sertifikasi</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Nama Kegiatan</label>
                <input type="text" class="form-control" name="nama_kegiatan" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Penyelenggara</label>
                <input type="text" class="form-control" name="nama_penyelenggara">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Peran</label>
                <input type="text" class="form-control" name="peran" placeholder="Contoh: Peserta, Narasumber">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Tanggal Mulai</label>
                <input type="date" class="form-control" name="tgl_mulai" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" name="tgl_selesai">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Tahun</label>
                <input type="number" class="form-control" name="tahun" placeholder="YYYY" required>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Keterangan</label>
                <textarea class="form-control" name="keterangan" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal">Batal</x-tabler.button>
        <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy">Simpan Pengajuan</x-tabler.button>
    </div>
</form>
