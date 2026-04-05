<x-tabler.form-modal 
    :title="'Persetujuan Dokumen'" 
    :route="route('pemutu.approval.process', $approval->encrypted_sys_approval_id)" 
    :method="'POST'" 
    data-redirect="true">
    
    <div class="alert alert-info py-2 px-3 mb-3 border-0">
        <div class="d-flex align-items-center">
            <span class="avatar avatar-sm rounded-circle bg-blue-lt me-3 text-blue">
                <i class="ti ti-file-description fs-3"></i>
            </span>
            <div>
                <h4 class="mb-0 text-dark">{{ $approval->subject->judul ?? '-' }}</h4>
                <div class="small mt-1 text-muted">
                    Jenis: {{ $approval->subject->jenis ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    @if(isset($isSah) && $isSah && isset($qrCode))
        <div class="alert alert-success d-flex align-items-center mb-3 border-0 shadow-sm">
            <div class="me-3 bg-white p-1 rounded border d-flex align-items-center justify-content-center">
                {!! $qrCode !!}
            </div>
            <div>
                <h4 class="alert-title mb-1"><i class="ti ti-shield-check me-1"></i>Dokumen Telah Sah</h4>
                <div class="text-muted" style="font-size: 0.75rem;">
                    Seluruh pimpinan terkait telah memufakati dokumen ini.
                </div>
                <div class="mt-2 text-nowrap d-flex gap-2">
                    <a href="{{ route('pemutu.dokumen.verify', $approval->subject->encrypted_dok_id) }}" target="_blank" class="btn btn-sm btn-success btn-pill py-1">
                        Verifikasi Publik
                    </a>
                    @if($approval->subject instanceof \App\Models\Pemutu\Dokumen)
                        <a href="{{ route('pemutu.dokumen.export', ['type' => 'dokumen', 'id' => $approval->subject->encrypted_dok_id]) }}" class="btn btn-sm btn-outline-primary btn-pill py-1">
                            <i class="ti ti-file-export me-1"></i> Export DOCX
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <hr class="my-3">

    <fieldset>
        <div class="mb-3">
            <label class="form-label required small fw-bold text-uppercase">Tindakan Persetujuan</label>
            <div class="form-selectgroup">
                <label class="form-selectgroup-item">
                    <input type="radio" name="status" value="Approved" class="form-selectgroup-input"
                           @checked($approval->status == 'Approved') required>
                    <span class="form-selectgroup-label text-success"><i class="ti ti-check me-1"></i>Setujui</span>
                </label>
                <label class="form-selectgroup-item">
                    <input type="radio" name="status" value="Rejected" class="form-selectgroup-input"
                           @checked($approval->status == 'Rejected') required>
                    <span class="form-selectgroup-label text-danger"><i class="ti ti-x me-1"></i>Tolak</span>
                </label>
            </div>
        </div>

        <div class="mb-0">
            <x-tabler.form-textarea 
                name="catatan" 
                label="Catatan (Opsional)" 
                rows="3" 
                placeholder="Berikan alasan jika ditolak, atau catatan jika perlu..." 
                :value="$approval->catatan ?? ''" 
            />
        </div>
    </fieldset>

</x-tabler.form-modal>
