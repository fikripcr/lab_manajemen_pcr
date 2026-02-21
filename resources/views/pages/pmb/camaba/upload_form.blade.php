<x-tabler.form-modal
    title="Unggah Dokumen"
    route="{{ route('pmb.camaba.do-upload', [$pendaftaran_id, $jenis_dokumen_id]) }}"
    method="POST"
    submitText="Unggah Sekarang"
    enctype="multipart/form-data"
    data-redirect="true"
>
    
    <div class="mb-3 text-center">
        <x-tabler.form-input type="file" name="file" label="Pilih Berkas" required help="Format yang diijinkan: {{ $jenis->tipe_file ?? 'Semua' }}. Maks: {{ formatBytes($jenis->max_size_kb * 1024) }}" />
    </div>
</x-tabler.form-modal>
