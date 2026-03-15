<x-tabler.form-modal
    title="Tambah Personil"
    route="{{ route('hr.personil.store') }}"
    method="POST"
>
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input 
                name="nip" 
                label="NIP/NIK" 
                placeholder="Masukkan NIP/NIK" 
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="nama" 
                label="Nama Lengkap" 
                placeholder="Masukkan Nama Lengkap" 
                required="true" 
            />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input 
                name="email" 
                label="Email" 
                type="email"
                placeholder="Email" 
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="posisi" 
                label="Posisi/Jabatan" 
                placeholder="Contoh: Security, Janitor, Teknisi" 
            />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-select 
                name="org_unit_id"
                label="Unit Kerja / Organisasi"
                :options="collect($units)->pluck('name', 'orgunit_id')->toArray()"
                placeholder="-- Pilih Unit Kerja --"
                required="true"
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                name="vendor" 
                label="Vendor / Pembawa" 
                placeholder="Nama Perusahaan Vendor" 
            />
        </div>
    </div>

    <div class="mt-3">
        <label class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="status_aktif" checked>
            <span class="form-check-label">Personil Aktif</span>
        </label>
    </div>
</x-tabler.form-modal>
