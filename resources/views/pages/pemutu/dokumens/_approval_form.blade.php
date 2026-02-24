<x-tabler.form-modal
    title="Persetujuan Dokumen"
    route="{{ route('pemutu.dokumens.approve', $dokumen) }}"
    method="POST"
    submitText="Simpan Approval"
    submitIcon="ti ti-device-floppy"
>
    <div class="mb-3">
        <x-tabler.form-select name="personil_id" label="Approver (Pegawai)" required="true" class="select2" data-dropdown-parent="#modalAction">
            <option value="">Pilih Pegawai...</option>
            @foreach($pegawais as $p)
                <option value="{{ $p->encrypted_personil_id }}">{{ $p->nama }} ({{ $p->posisi ?? '-' }})</option>
            @endforeach
        </x-tabler.form-select>
    </div>
    
    <div class="mb-3">
        <label class="form-label required">Keputusan Tahapan</label>
        <div class="form-selectgroup form-selectgroup-boxes d-flex flex-row gap-2">
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="status" value="terima" class="form-selectgroup-input" checked>
                <div class="form-selectgroup-label d-flex align-items-center justify-content-center p-2 h-100">
                    <div class="text-center">
                        <span class="d-block text-success font-weight-bold mb-1"><i class="ti ti-check me-1"></i> Terima (Approve)</span>
                    </div>
                </div>
            </label>
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="status" value="tolak" class="form-selectgroup-input">
                <div class="form-selectgroup-label d-flex align-items-center justify-content-center p-2 h-100">
                    <div class="text-center">
                        <span class="d-block text-danger font-weight-bold mb-1"><i class="ti ti-x me-1"></i> Tolak (Reject)</span>
                    </div>
                </div>
            </label>
            <label class="form-selectgroup-item flex-fill">
                <input type="radio" name="status" value="tangguhkan" class="form-selectgroup-input">
                <div class="form-selectgroup-label d-flex align-items-center justify-content-center p-2 h-100">
                    <div class="text-center">
                        <span class="d-block text-warning font-weight-bold mb-1"><i class="ti ti-clock me-1"></i> Perbaikan</span>
                    </div>
                </div>
            </label>
        </div>
    </div>
    
    <div class="mb-3">
        <x-tabler.form-textarea name="komentar" label="Catatan Approval" placeholder="Masukkan catatan atau revisi..." rows="3" />
    </div>
</x-tabler.form-modal>
