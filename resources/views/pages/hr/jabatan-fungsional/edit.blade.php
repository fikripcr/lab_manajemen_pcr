<x-tabler.form-modal
    title="Edit Jabatan Fungsional"
    route="{{ route('hr.jabatan-fungsional.update', $jabatanFungsional) }}"
    method="PUT"
    submitText="Simpan Perubahan"
>
    <div class="row">
        <div class="col-md-4 mb-3">
            <x-tabler.form-input name="kode_jabatan" label="Kode Jabatan" value="{{ $jabatanFungsional->kode_jabatan }}" required="true" />
        </div>
        <div class="col-md-8 mb-3">
            <x-tabler.form-input name="jabfungsional" label="Jabatan Fungsional" value="{{ $jabatanFungsional->jabfungsional }}" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-input type="number" name="tunjangan" label="Tunjangan" :value="$jabatanFungsional->tunjangan" prefix="Rp" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                label="Aktif" 
                value="1" 
                :checked="$jabatanFungsional->is_active" 
                switch 
            />
        </div>
    </div>
</x-tabler.form-modal>
