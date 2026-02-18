<x-tabler.form-modal
    title="Tambah Periode"
    route="{{ route('pmb.periode.store') }}"
    method="POST"
    data-table="#table-periode"
>
    <x-tabler.form-input name="nama_periode" label="Nama Periode" placeholder="Contoh: 2025/2026 Ganjil" required="true" />
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_mulai" label="Tanggal Mulai" required="true" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_selesai" label="Tanggal Selesai" required="true" />
        </div>
    </div>

    <x-tabler.form-checkbox name="is_aktif" label="Set sebagai Periode Aktif" checked="true" />
</x-tabler.form-modal>
