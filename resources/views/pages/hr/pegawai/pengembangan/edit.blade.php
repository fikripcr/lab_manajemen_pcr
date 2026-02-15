<form action="{{ route('hr.pegawai.pengembangan.update', [$pegawai->encrypted_pegawai_id, $pengembangan->pengembangandiri_id]) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Perubahan data akan berstatus <strong>Menunggu Persetujuan</strong>.
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Jenis Kegiatan</label>
                <select class="form-select" name="jenis_kegiatan" required>
                    <option value="">Pilih Jenis Kegiatan</option>
                    <option value="Pelatihan" {{ $pengembangan->jenis_kegiatan == 'Pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                    <option value="Seminar" {{ $pengembangan->jenis_kegiatan == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                    <option value="Workshop" {{ $pengembangan->jenis_kegiatan == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                    <option value="Sertifikasi" {{ $pengembangan->jenis_kegiatan == 'Sertifikasi' ? 'selected' : '' }}>Sertifikasi</option>
                    <option value="Lainnya" {{ $pengembangan->jenis_kegiatan == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Nama Kegiatan</label>
                <input type="text" class="form-control" name="nama_kegiatan" value="{{ $pengembangan->nama_kegiatan }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Penyelenggara</label>
                <input type="text" class="form-control" name="nama_penyelenggara" value="{{ $pengembangan->nama_penyelenggara }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Peran</label>
                <input type="text" class="form-control" name="peran" value="{{ $pengembangan->peran }}" placeholder="Contoh: Peserta, Narasumber">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Tanggal Mulai</label>
                <input type="date" class="form-control" name="tgl_mulai" value="{{ $pengembangan->tgl_mulai ? \Carbon\Carbon::parse($pengembangan->tgl_mulai)->format('Y-m-d') : '' }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" name="tgl_selesai" value="{{ $pengembangan->tgl_selesai ? \Carbon\Carbon::parse($pengembangan->tgl_selesai)->format('Y-m-d') : '' }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Tahun</label>
                <input type="number" class="form-control" name="tahun" value="{{ $pengembangan->tahun }}" placeholder="YYYY" required>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Keterangan</label>
                <textarea class="form-control" name="keterangan" rows="3">{{ $pengembangan->keterangan }}</textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" class="btn-link link-secondary" data-bs-dismiss="modal">Batal</x-tabler.button>
        <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy">Simpan Perubahan</x-tabler.button>
    </div>
</form>
