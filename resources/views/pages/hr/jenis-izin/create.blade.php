<div class="modal-header">
    <h5 class="modal-title">Tambah Jenis Izin</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="ajax-form" action="{{ route('hr.jenis-izin.store') }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <x-tabler.form-input name="nama" label="Nama Izin" required="true" placeholder="Contoh: Cuti Tahunan" />
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <x-tabler.form-select name="kategori" label="Kategori">
                    <option value="">Pilih Kategori...</option>
                    <option value="Cuti">Cuti</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Izin">Izin</option>
                </x-tabler.form-select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Max Hari</label>
                <input type="number" class="form-control" name="max_hari" placeholder="Kosongkan jika tidak ada limit">
            </div>
        </div>
        <div class="mb-3">
            <x-tabler.form-select name="pemilihan_waktu" label="Pemilihan Waktu">
                <option value="tgl">Tanggal Saja</option>
                <option value="jam">Jam Saja</option>
                <option value="tgl-jam">Tanggal & Jam</option>
            </x-tabler.form-select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
