<x-tabler.form-modal
    :title="$jenisIndisipliner->exists ? 'Edit Jenis Indisipliner' : 'Tambah Jenis Indisipliner'"
    :route="$jenisIndisipliner->exists ? route('hr.jenis-indisipliner.update', $jenisIndisipliner->encrypted_jenisindisipliner_id) : route('hr.jenis-indisipliner.store')"
    :method="$jenisIndisipliner->exists ? 'PUT' : 'POST'"
    :submitText="$jenisIndisipliner->exists ? 'Simpan Perubahan' : 'Bagikan'"
>
    <div class="mb-3">
        <x-tabler.form-input 
            name="jenis_indisipliner" 
            label="Jenis Indisipliner" 
            value="{{ $jenisIndisipliner->jenis_indisipliner }}" 
            required="true" 
            placeholder="Contoh: Teguran Lisan, Surat Peringatan, dll" 
        />
    </div>
</x-tabler.form-modal>
