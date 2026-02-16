<form action="{{ route('pmb.camaba.do-upload') }}" method="POST" class="ajax-form" enctype="multipart/form-data" data-redirect="true">
    @csrf
    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran_id }}">
    <input type="hidden" name="jenis_dokumen_id" value="{{ $jenis_dokumen_id }}">
    
    <div class="mb-3 text-center">
        <div class="form-label">Pilih Berkas</div>
        <input type="file" name="file" class="form-control" required>
        <div class="form-hint">Format yang diijinkan: {{ $jenis->tipe_file ?? 'Semua' }}. Maks: {{ formatBytes($jenis->max_size_kb * 1024) }}</div>
    </div>

    <div class="modal-footer px-0 pb-0">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">Unggah Sekarang</button>
    </div>
</form>
