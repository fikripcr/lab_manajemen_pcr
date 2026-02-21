@php
    $isEdit = $jenisShift->exists;
    $title  = $isEdit ? 'Edit Jenis Shift' : 'Tambah Jenis Shift';
    $route  = $isEdit 
        ? route('hr.jenis-shift.update', $jenisShift->hashid) 
        : route('hr.jenis-shift.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Simpan Perubahan' : 'Simpan'"
>
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="jenis_shift" label="Nama Shift" :value="$jenisShift->jenis_shift" placeholder="Contoh: Normal, Pagi 1" required="true" />
        </div>
        
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_masuk_awal" label="Batas Awal Masuk" :value="$jenisShift->jam_masuk_awal" placeholder="HH:MM" required="true" />
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_masuk" label="Waktu Masuk" :value="$jenisShift->jam_masuk" placeholder="HH:MM" required="true" />
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_masuk_akhir" label="Batas Akhir Masuk" :value="$jenisShift->jam_masuk_akhir" placeholder="HH:MM" required="true" />
        </div>

        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_pulang_awal" label="Batas Awal Pulang" :value="$jenisShift->jam_pulang_awal" placeholder="HH:MM" required="true" />
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_pulang" label="Waktu Pulang" :value="$jenisShift->jam_pulang" placeholder="HH:MM" required="true" />
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="time" name="jam_pulang_akhir" label="Batas Akhir Pulang" :value="$jenisShift->jam_pulang_akhir" placeholder="HH:MM" required="true" />
        </div>

        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                label="Aktif" 
                value="1" 
                :checked="$jenisShift->is_active ?? true" 
                switch 
            />
        </div>
    </div>
</x-tabler.form-modal>
