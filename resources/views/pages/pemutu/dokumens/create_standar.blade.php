<div class="modal-header">
    <h5 class="modal-title">{{ $pageTitle }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemutu.dokumens.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label for="jenis" class="form-label required">Jenis Dokumen</label>
            <x-tabler.form-select id="jenis" name="jenis" label="Jenis Dokumen" required="true" class="select2-offline" data-dropdown-parent="#modalAction">
                <option value="">Pilih Jenis...</option>
                <option value="standar">Standar</option>
                <option value="formulir">Formulir</option>
                <option value="sop">SOP</option>
                <option value="manual_prosedur">Manual Prosedur</option>
            </x-tabler.form-select>
        </div>
        <div class="mb-3">
            <x-tabler.form-input name="judul" label="Judul Dokumen" id="judul" required="true" placeholder="Contoh: Standar Operasional Prosedur Pelayanan..." />
        </div>
        <div class="mb-3">
            <x-tabler.form-input name="kode" label="Kode Dokumen" id="kode" placeholder="Contoh: SOP-HUMAS-01" />
        </div>
        <x-tabler.form-textarea name="isi" id="isi" label="Isi / Keterangan" rows="4" placeholder="Keterangan singkat dokumen..." />
        
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
