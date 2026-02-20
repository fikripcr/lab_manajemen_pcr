<x-tabler.form-modal
    title="Persetujuan Dokumen"
    route="{{ route('pemutu.dokumens.approve', $dokumen) }}"
    method="POST"
    submitText="Simpan Approval"
    submitIcon="ti ti-device-floppy"
>
    <div class="mb-3">
        <x-tabler.form-select name="approver_id" label="Approver (Pegawai)" required="true" class="select2" data-dropdown-parent="#modalAction">
            <option value="">Pilih Pegawai...</option>
            @foreach($pegawais as $p)
                <option value="{{ $p->encrypted_pegawai_id }}">{{ $p->nama }} ({{ $p->jenis ?? '-' }})</option>
            @endforeach
        </x-tabler.form-select>
    </div>
    
    <div class="mb-3">
        <label class="form-label required">Status Approval</label>
        <div class="form-selectgroup form-selectgroup-boxes d-flex flex-row gap-2">
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="status" value="terima" class="form-selectgroup-input" checked>
                <div class="form-selectgroup-label d-flex align-items-center justify-content-center p-2 h-100">
                    <div class="text-center">
                        <span class="d-block text-success font-weight-bold mb-1"><i class="ti ti-check me-1"></i> Terima</span>
                        <small class="d-block text-muted lh-1" style="font-size: 0.65rem;">Disetujui / Dilegalkan</small>
                    </div>
                </div>
            </label>
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="status" value="tolak" class="form-selectgroup-input">
                <div class="form-selectgroup-label d-flex align-items-center justify-content-center p-2 h-100">
                    <div class="text-center">
                        <span class="d-block text-danger font-weight-bold mb-1"><i class="ti ti-x me-1"></i> Tolak</span>
                        <small class="d-block text-muted lh-1" style="font-size: 0.65rem;">Ditolak dengan alasan</small>
                    </div>
                </div>
            </label>
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="status" value="tangguhkan" class="form-selectgroup-input">
                <div class="form-selectgroup-label d-flex align-items-center justify-content-center p-2 h-100">
                    <div class="text-center">
                        <span class="d-block text-warning font-weight-bold mb-1"><i class="ti ti-clock me-1"></i> Tangguh</span>
                        <small class="d-block text-muted lh-1" style="font-size: 0.65rem;">Butuh perbaikan</small>
                    </div>
                </div>
            </label>
        </div>
    </div>
    
    <div class="mb-3">
        <x-tabler.form-textarea name="komentar" label="Komentar / Catatan" placeholder="Masukkan komentar jika ada..." rows="3" />
    </div>
</x-tabler.form-modal>
