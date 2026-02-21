<x-tabler.form-modal
    id_form="{{ $mataKuliah->exists ? 'editMataKuliahForm' : 'createMataKuliahForm' }}"
    title="{{ $mataKuliah->exists ? 'Edit Mata Kuliah' : 'Create New Mata Kuliah' }}"
    route="{{ $mataKuliah->exists ? route('lab.mata-kuliah.update', $mataKuliah->encrypted_mata_kuliah_id) : route('lab.mata-kuliah.store') }}"
    method="{{ $mataKuliah->exists ? 'PUT' : 'POST' }}"
    submitText="{{ $mataKuliah->exists ? 'Update' : 'Save' }}"
>
    <x-tabler.form-input 
        name="kode_mk" 
        label="Kode MK" 
        value="{{ old('kode_mk', $mataKuliah->kode_mk) }}" 
        placeholder="e.g. IF101" 
        required 
    />

    <x-tabler.form-input 
        name="nama_mk" 
        label="Nama MK" 
        value="{{ old('nama_mk', $mataKuliah->nama_mk) }}" 
        placeholder="e.g. Pemrograman Web" 
        required 
    />

    <x-tabler.form-input 
        type="number" 
        name="sks" 
        label="SKS" 
        value="{{ old('sks', $mataKuliah->sks ?? 3) }}" 
        min="1" 
        max="6" 
        required 
    />
</x-tabler.form-modal>
