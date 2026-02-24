@php
    $isEdit = isset($jabatan) && $jabatan->exists;
    $title = $isEdit ? 'Edit Jabatan Struktural' : 'Tambah Jabatan Struktural';
    $route = $isEdit ? route('hr.jabatan-struktural.update', $jabatan) : route('hr.jabatan-struktural.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    title="{{ $title }}"
    route="{{ $route }}"
    method="{{ $method }}"
>
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="nama" label="Nama Jabatan" :value="$isEdit ? $jabatan->nama : old('nama')" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-select name="parent_id" label="Parent (Atasan)">
                <option value="">- Pilih Parent -</option>
                @foreach($parents as $id => $nama)
                    <option value="{{ $id }}" {{ $isEdit && $jabatan->parent_id == $id ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                label="Aktif" 
                value="1" 
                :checked="$isEdit ? $jabatan->is_active : true" 
                switch 
            />
        </div>
    </div>
</x-tabler.form-modal>
