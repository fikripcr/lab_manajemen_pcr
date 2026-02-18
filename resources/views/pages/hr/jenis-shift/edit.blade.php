<x-tabler.form-modal
    title="Edit Jenis Shift"
    route="{{ route('hr.jenis-shift.update', $jenisShift) }}"
    method="PUT"
    submitText="Simpan Perubahan"
>
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="jenis_shift" label="Nama Shift" value="{{ $jenisShift->jenis_shift }}" required="true" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="time" name="jam_masuk" label="Jam Masuk" value="{{ $jenisShift->jam_masuk }}" required="true" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="time" name="jam_pulang" label="Jam Pulang" value="{{ $jenisShift->jam_pulang }}" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                label="Aktif" 
                value="1" 
                :checked="$jenisShift->is_active" 
                switch 
            />
        </div>
    </div>
</x-tabler.form-modal>
