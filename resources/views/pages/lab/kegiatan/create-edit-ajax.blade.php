<x-tabler.form-modal
    id_form="{{ $kegiatan->exists ? 'editKegiatanForm' : 'createKegiatanForm' }}"
    title="{{ $kegiatan->exists ? 'Ubah Kegiatan' : 'Ajukan Peminjaman Lab' }}"
    route="{{ $kegiatan->exists ? route('lab.kegiatan.updateStatus', $kegiatan->encrypted_kegiatan_id) : route('lab.kegiatan.store') }}"
    method="{{ $kegiatan->exists ? 'PUT' : 'POST' }}"
    enctype="multipart/form-data"
    submitText="{{ $kegiatan->exists ? 'Simpan Perubahan' : 'Ajukan Peminjaman' }}"
>
    <x-tabler.form-input 
        name="nama_kegiatan" 
        label="Nama Kegiatan" 
        value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" 
        placeholder="Contoh: Workshop Laravel" 
        required 
    />

    <div class="mb-3">
        <x-tabler.form-select 
            name="lab_id" 
            label="Lab Target" 
            :options="$labs->mapWithKeys(fn($lab) => [encryptId($lab->lab_id) => $lab->name . ' (Kapasitas: ' . $lab->capacity . ')'])->toArray()" 
            selected="{{ old('lab_id', $kegiatan->lab_id ? encryptId($kegiatan->lab_id) : '') }}" 
            placeholder="Pilih Lab" 
            required 
        />
    </div>

    <div class="row">
        <div class="col-md-4">
            <x-tabler.form-input 
                type="date" 
                name="tanggal" 
                label="Tanggal" 
                value="{{ old('tanggal', $kegiatan->tanggal ? $kegiatan->tanggal->format('Y-m-d') : '') }}" 
                required 
            />
        </div>
        <div class="col-md-4">
            <x-tabler.form-input 
                type="time" 
                name="jam_mulai" 
                label="Jam Mulai" 
                value="{{ old('jam_mulai', $kegiatan->jam_mulai ? $kegiatan->jam_mulai->format('H:i') : '') }}" 
                required 
            />
        </div>
        <div class="col-md-4">
            <x-tabler.form-input 
                type="time" 
                name="jam_selesai" 
                label="Jam Selesai" 
                value="{{ old('jam_selesai', $kegiatan->jam_selesai ? $kegiatan->jam_selesai->format('H:i') : '') }}" 
                required 
            />
        </div>
    </div>

    <x-tabler.form-textarea 
        name="deskripsi" 
        label="Deskripsi" 
        rows="4" 
        placeholder="Jelaskan tujuan dan detail kegiatan..." 
        required 
    >{{ old('deskripsi', $kegiatan->deskripsi) }}</x-tabler.form-textarea>

    <x-tabler.form-input 
        type="file" 
        name="dokumentasi_path" 
        label="Dokumen Pendukung (Surat Permohonan)" 
        accept=".pdf,.jpg,.jpeg,.png" 
        help="Max 2MB. Disarankan format PDF." 
    />
</x-tabler.form-modal>
