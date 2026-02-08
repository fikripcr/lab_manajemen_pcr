<div class="modal-header">
    <h5 class="modal-title">Edit Jenis Izin</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="ajax-form" action="{{ route('hr.jenis-izin.update', $jenis_izin->hashid) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label required">Nama Jenis Izin</label>
            <input type="text" class="form-control" name="nama" value="{{ $jenis_izin->nama }}" required>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <select class="form-select" name="kategori">
                    <option value="">Pilih Kategori...</option>
                    <option value="Cuti" {{ $jenis_izin->kategori == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                    <option value="Sakit" {{ $jenis_izin->kategori == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="Izin" {{ $jenis_izin->kategori == 'Izin' ? 'selected' : '' }}>Izin</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Max Hari</label>
                <input type="number" class="form-control" name="max_hari" value="{{ $jenis_izin->max_hari }}">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Pemilihan Waktu</label>
            <select class="form-select" name="pemilihan_waktu">
                <option value="tgl" {{ $jenis_izin->pemilihan_waktu == 'tgl' ? 'selected' : '' }}>Tanggal Saja</option>
                <option value="jam" {{ $jenis_izin->pemilihan_waktu == 'jam' ? 'selected' : '' }}>Jam Saja</option>
                <option value="tgl-jam" {{ $jenis_izin->pemilihan_waktu == 'tgl-jam' ? 'selected' : '' }}>Tanggal & Jam</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="is_active">
                <option value="1" {{ $jenis_izin->is_active ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ !$jenis_izin->is_active ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
