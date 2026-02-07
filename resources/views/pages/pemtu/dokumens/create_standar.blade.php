<div class="modal-header">
    <h5 class="modal-title">{{ $pageTitle }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemtu.dokumens.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label for="jenis" class="form-label required">Jenis Dokumen</label>
            <select class="form-select select2-offline" id="jenis" name="jenis" required data-dropdown-parent="#modalAction">
                <option value="">Pilih Jenis...</option>
                <option value="standar">Standar</option>
                <option value="formulir">Formulir</option>
                <option value="sop">SOP</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="judul" class="form-label required">Judul Dokumen</label>
            <input type="text" class="form-control" id="judul" name="judul" required placeholder="Contoh: Standar Operasional Prosedur Pelayanan...">
        </div>
        <div class="mb-3">
            <label for="kode" class="form-label">Kode Dokumen</label>
            <input type="text" class="form-control" id="kode" name="kode" placeholder="Contoh: SOP-HUMAS-01">
        </div>
        <div class="mb-3">
            <label for="isi" class="form-label">Isi / Keterangan</label>
            <textarea class="form-control" id="isi" name="isi" rows="4" placeholder="Keterangan singkat dokumen..."></textarea>
        </div>
        
        {{-- Hidden Default Fields --}}
        <input type="hidden" name="periode" value="{{ date('Y') }}">
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Batal" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Simpan" />
    </div>
</form>

<script>
    setTimeout(() => {
        if(window.initSelect2Offline) {
            window.initSelect2Offline();
        }
    }, 100);
</script>
