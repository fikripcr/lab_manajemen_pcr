<x-tabler.form-modal
    title="Tambah Jenis Izin"
    route="{{ route('hr.jenis-izin.store') }}"
    method="POST"
>
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
            <x-tabler.form-input type="number" name="max_hari" label="Max Hari" placeholder="Kosongkan jika tidak ada limit" />
        </div>
    </div>
    <div class="mb-3">
        <x-tabler.form-select name="pemilihan_waktu" label="Pemilihan Waktu">
            <option value="tgl">Tanggal Saja</option>
            <option value="jam">Jam Saja</option>
            <option value="tgl-jam">Tanggal & Jam</option>
        </x-tabler.form-select>
    </div>
</x-tabler.form-modal>
