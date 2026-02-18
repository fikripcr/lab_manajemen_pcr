<x-tabler.form-modal
    title="Tambah Jenis Shift"
    route="{{ route('hr.jenis-shift.store') }}"
    method="POST"
    submitText="Simpan"
>
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="jenis_shift" label="Nama Shift" required="true" />
        </div>
        
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_masuk_awal" label="Batas Awal Masuk" placeholder="HH:MM" required="true" />
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_masuk" label="Waktu Masuk" placeholder="HH:MM" required="true" />
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_masuk_akhir" label="Batas Akhir Masuk" placeholder="HH:MM" required="true" />
        </div>

        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_pulang_awal" label="Batas Awal Pulang" placeholder="HH:MM" required="true" />
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_pulang" label="Waktu Pulang" placeholder="HH:MM" required="true" />
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_pulang_akhir" label="Batas Akhir Pulang" placeholder="HH:MM" required="true" />
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
