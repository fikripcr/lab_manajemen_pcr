<x-tabler.form-modal
    title="Unggah File Pegawai"
    route="{{ route('hr.pegawai.files.store', $pegawai->hashid) }}"
    method="POST"
    submitText="Unggah Sekarang"
    submitIcon="ti-upload"
    enctype="multipart/form-data"
>
    <div class="mb-3">
        <x-tabler.form-select name="jenisfile_id" label="Kategori File" required="true">
            <option value="">Pilih Kategori...</option>
            @foreach(\App\Models\Hr\JenisFile::where('is_active', 1)->get() as $jenis)
                <option value="{{ $jenis->jenisfile_id }}">{{ $jenis->jenisfile }}</option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-input type="file" name="file" label="Pilih File" required="true" help="Maksimal 10MB (PDF, JPG, PNG, DOCX, dll)" />
    </div>
    <div class="mb-3">
        <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="3" placeholder="Tambahkan catatan jika perlu..." />
    </div>
</x-tabler.form-modal>
