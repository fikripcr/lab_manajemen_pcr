<x-tabler.form-modal
    id_form="{{ $jalur->exists ? 'editJalurForm' : 'createJalurForm' }}"
    title="{{ $jalur->exists ? 'Update Jalur Pendaftaran' : 'Tambah Jalur Pendaftaran' }}"
    route="{{ $jalur->exists ? route('pmb.jalur.update', $jalur->encrypted_jalur_id) : route('pmb.jalur.store') }}"
    method="{{ $jalur->exists ? 'PUT' : 'POST' }}"
>
    <div class="mb-3">
        <x-tabler.form-input 
            name="nama_jalur" 
            label="Nama Jalur" 
            value="{{ old('nama_jalur', $jalur->nama_jalur) }}"
            placeholder="Contoh: Jalur Mandiri, Jalur Prestasi" 
            required 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            type="number" 
            name="biaya_pendaftaran" 
            label="Biaya Pendaftaran" 
            value="{{ old('biaya_pendaftaran', $jalur->biaya_pendaftaran) }}"
            placeholder="Contoh: 300000" 
            required 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-checkbox 
            name="is_aktif" 
            label="Status Aktif" 
            value="1" 
            :checked="$jalur->is_aktif" 
            switch 
        />
    </div>
</x-tabler.form-modal>
