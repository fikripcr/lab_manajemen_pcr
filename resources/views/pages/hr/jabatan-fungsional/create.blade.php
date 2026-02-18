<x-tabler.form-modal
    title="Tambah Jabatan Fungsional"
    route="{{ route('hr.jabatan-fungsional.store') }}"
    method="POST"
>
    <div class="row">
        <div class="col-md-4 mb-3">
            <x-tabler.form-input name="kode_jabatan" label="Kode Jabatan" placeholder="Contoh: AA" required="true" />
        </div>
        <div class="col-md-8 mb-3">
            <x-tabler.form-input name="jabfungsional" label="Jabatan Fungsional" placeholder="Contoh: Asisten Ahli" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-input type="number" name="tunjangan" label="Tunjangan" placeholder="0" prefix="Rp" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                label="Aktif" 
                value="1" 
                checked 
                switch 
            />
        </div>
    </div>
</x-tabler.form-modal>
