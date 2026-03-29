<x-tabler.form-modal 
    :title="'Persetujuan Dokumen — ' . ($approval->subject->kode ?? '')" 
    :route="$approval->status == 'Pending' ? route('pemutu.approval.process', $approval->encrypted_sys_approval_id) : '#'" 
    :method="$approval->status == 'Pending' ? 'POST' : 'none'" 
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

    @if($approval->status !== 'Pending')
        <div class="alert alert-warning mb-3">
            <i class="ti ti-info-circle me-1"></i> Status saat ini: <strong>{{ $approval->status }}</strong>. Anda tidak dapat mengubah status yang telah dieksekusi.
        </div>
    @endif

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
                <div class="mt-2 text-nowrap">
                    <a href="{{ route('pemutu.dokumen.verify', $approval->subject->encrypted_dok_id) }}" target="_blank" class="btn btn-sm btn-success btn-pill py-1">
                        Verifikasi Publik 
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if(isset($allApprovals) && $allApprovals->where('status', 'Approved')->count() > 0)
        <div class="mb-3">
            <label class="form-label fw-bold small text-uppercase mb-2 text-muted">Stempel Persetujuan Tersimpan (TTD)</label>
            <div class="list-group list-group-flush border rounded border-bottom-0 small">
                @foreach($allApprovals->where('status', 'Approved') as $appr)
                <div class="list-group-item bg-light border-0 border-bottom py-2">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <div class="d-flex align-items-center mb-0">
                            <strong>{{ $appr->pejabat }}</strong>
                            <span class="text-muted ms-2">({{ $appr->jabatan }})</span>
                        </div>
                        <span class="badge bg-green-lt"><i class="ti ti-check me-1"></i>{{ $appr->updated_at->format('d/m/y H:i') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif

    <hr class="my-3">

    <fieldset {{ $approval->status !== 'Pending' ? 'disabled' : '' }}>
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
