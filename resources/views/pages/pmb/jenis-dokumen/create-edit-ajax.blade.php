<x-tabler.form-modal
    id_form="{{ $jenisDokumen->exists ? 'editJenisDokumenForm' : 'createJenisDokumenForm' }}"
    title="{{ $jenisDokumen->exists ? 'Update Jenis Dokumen' : 'Tambah Jenis Dokumen' }}"
    route="{{ $jenisDokumen->exists ? route('pmb.jenis-dokumen.update', $jenisDokumen->encrypted_jenis_dokumen_id) : route('pmb.jenis-dokumen.store') }}"
    method="{{ $jenisDokumen->exists ? 'PUT' : 'POST' }}"
>
    <div class="mb-3">
        <x-tabler.form-input 
            name="nama_dokumen" 
            label="Nama Dokumen" 
            value="{{ old('nama_dokumen', $jenisDokumen->nama_dokumen) }}"
            placeholder="Contoh: Ijazah SMA, Kartu Keluarga" 
            required 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            name="tipe_file" 
            label="Tipe File (Mime)" 
            value="{{ old('tipe_file', $jenisDokumen->tipe_file) }}"
            placeholder="Contoh: application/pdf, image/jpeg (Pisahkan dengan koma)" 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            type="number" 
            name="max_size_kb" 
            label="Max Size (KB)" 
            value="{{ old('max_size_kb', $jenisDokumen->max_size_kb) }}"
            placeholder="Contoh: 2048" 
            required 
        />
    </div>
</x-tabler.form-modal>
