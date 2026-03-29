<x-tabler.form-modal
    id_form="approval-form"
    title="Persetujuan Dokumen"
    route="{{ route('pemutu.dokumen.approve', $dokumen) }}"
    method="POST"
    submitText="Simpan Perubahan Approver"
>
    <div id="approver-container">
        @if(isset($isSah) && $isSah && isset($qrCode))
        <div class="alert alert-success d-flex align-items-center mb-4 border-0 shadow-sm">
            <div class="me-3 bg-white p-1 rounded border d-flex align-items-center justify-content-center">
                {!! $qrCode !!}
            </div>
            <div>
                <h3 class="alert-title mb-1"><i class="ti ti-shield-check me-1"></i>Dokumen Sah & Tervalidasi</h3>
                <div class="text-muted small">
                    Dokumen ini telah disetujui secara mufakat oleh seluruh pejabat struktural terkait. Kode QR di samping dapat dipindai untuk meluncurkan laman verifikasi orisinalitas publik.
                </div>
                <div class="mt-2 text-nowrap">
                    <a href="{{ route('pemutu.dokumen.verify', $dokumen->encrypted_dok_id) }}" target="_blank" class="btn btn-sm btn-success btn-pill">
                        <i class="ti ti-external-link me-1"></i> Buka Pranala Asli 
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if(isset($approvals) && $approvals->where('status', 'Approved')->count() > 0)
        <div class="mb-4">
            <h4 class="text-success"><i class="ti ti-check me-1"></i> Telah Disetujui Oleh:</h4>
            <div class="list-group list-group-flush border rounded-3 text-start small">
                @foreach($approvals->where('status', 'Approved') as $appr)
                <div class="list-group-item bg-light border-0 py-2">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <div class="d-flex align-items-center mb-0">
                            <strong>{{ $appr->pejabat }}</strong>
                            <span class="text-muted ms-2">({{ $appr->jabatan }})</span>
                        </div>
                        <span class="badge bg-green-lt">Disetujui pada {{ $appr->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <hr class="my-3 border-light">
        <h4 class="mb-3">Antrian Persetujuan</h4>
        <p class="text-muted small mb-3">
            <i class="ti ti-info-circle me-1"></i>
            Ubah approver, jabatan, atau hapus baris yang tidak diperlukan. Tambahkan approver baru jika perlu.
        </p>

        @forelse($existingApprovals as $index => $approval)
            <div class="approver-row mb-3 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="text-muted">Approver #<span class="row-number">{{ $index + 1 }}</span></strong>
                    @if($approval->status === 'Approved')
                        <span class="badge bg-green-lt">Sudah Disetujui</span>
                    @elseif($approval->status === 'Rejected')
                        <span class="badge bg-red-lt">Ditolak</span>
                    @else
                        <span class="badge bg-yellow-lt">Pending</span>
                    @endif
                </div>
                <div class="d-flex align-items-start gap-3 w-100">
                    <input type="hidden" name="approvers[{{ $index }}][id]" value="{{ $approval->riwayatapproval_id }}">
                    
                    <div class="flex-grow-1" style="flex: 1;">
                        <label class="form-label required mb-1">Approver (Pegawai)</label>
                        <select name="approvers[{{ $index }}][pegawai_id]" class="form-select select2-approval" required data-dropdown-parent="#modalAction" {{ $approval->status !== 'Pending' ? 'disabled' : '' }}>
                            <option value="">Pilih Pegawai...</option>
                            @foreach($pegawais as $p)
                                <option value="{{ $p->encrypted_pegawai_id }}" {{ $p->pegawai_id == $approval->pegawai_id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                        @if($approval->status !== 'Pending')
                            <small class="text-muted">Tidak dapat diubah karena sudah diproses</small>
                        @endif
                    </div>
                    <div class="flex-grow-1" style="flex: 1;">
                        <label class="form-label required mb-1">Posisi / Jabatan</label>
                        <input type="text" name="approvers[{{ $index }}][jabatan]" class="form-control" placeholder="Contoh: Ketua Penjaminan Mutu" required value="{{ $approval->jabatan }}" {{ $approval->status !== 'Pending' ? 'disabled' : '' }}>
                    </div>
                    <div class="pt-4 mt-1">
                        @if($approval->status === 'Pending')
                            <button type="button" class="btn btn-outline-danger btn-icon btn-remove-approver" title="Hapus" aria-label="Hapus">
                                <i class="ti ti-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>
                @if($approval->status !== 'Pending' && $approval->catatan)
                    <div class="mt-2 p-2 bg-light rounded border">
                        <small class="text-muted"><strong>Catatan:</strong> {{ $approval->catatan }}</small>
                        <br>
                        <small class="text-muted">Diproses pada {{ $approval->updated_at->format('d/m/Y H:i') }}</small>
                    </div>
                @endif
            </div>
        @empty
            <!-- Template Row if Empty -->
            <div class="approver-row mb-3 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="text-muted">Approver #<span class="row-number">1</span></strong>
                </div>
                <div class="d-flex align-items-start gap-3 w-100">
                    <input type="hidden" name="approvers[0][id]" value="">
                    
                    <div class="flex-grow-1" style="flex: 1;">
                        <label class="form-label required mb-1">Approver (Pegawai)</label>
                        <select name="approvers[0][pegawai_id]" class="form-select select2-approval" required data-dropdown-parent="#modalAction">
                            <option value="">Pilih Pegawai...</option>
                            @foreach($pegawais as $p)
                                <option value="{{ $p->encrypted_pegawai_id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-grow-1" style="flex: 1;">
                        <label class="form-label required mb-1">Posisi / Jabatan</label>
                        <input type="text" name="approvers[0][jabatan]" class="form-control" placeholder="Contoh: Ketua Penjaminan Mutu" required>
                    </div>
                    <div class="pt-4 mt-1">
                        <button type="button" class="btn btn-outline-danger btn-icon btn-remove-approver" style="display: none;" title="Hapus" aria-label="Hapus">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-3 text-center">
        <hr class="my-3">
        <button type="button" class="btn btn-outline-primary" id="btn-add-approver">
            <i class="ti ti-plus"></i> Tambah Approver Baru
        </button>
    </div>

</x-tabler.form-modal>

<script>
    if (typeof initApprovalForm !== 'function') {
        let approvalFormInitialized = false;
        
        function initApprovalForm() {
            // Prevent double initialization
            if (approvalFormInitialized) return;
            approvalFormInitialized = true;
            
            const container = document.getElementById('approver-container');
            const btnAdd = document.getElementById('btn-add-approver');
            let rowIndex = container.querySelectorAll('.approver-row').length;

            // Initialize Select2 for all existing rows FIRST
            container.querySelectorAll('.approver-row').forEach(row => {
                initSelect2InRow(row);
            });

            btnAdd.addEventListener('click', function() {
                const firstRow = container.querySelector('.approver-row');
                const newRow = firstRow.cloneNode(true);

                // Update names and indexing
                const hiddenId = newRow.querySelector('input[type="hidden"]');
                const select = newRow.querySelector('select');
                const input = newRow.querySelector('input[type="text"]');
                const rowNo = newRow.querySelector('.row-number');
                const btnRemove = newRow.querySelector('.btn-remove-approver');

                // Remove garbage Select2 DOM containers that got cloned
                newRow.querySelectorAll('.select2-container').forEach(el => el.remove());

                // Clear hidden ID (new row has no ID)
                if (hiddenId) hiddenId.value = "";

                // Clean Select2 attributes from the select element
                select.classList.remove('select2-hidden-accessible');
                select.removeAttribute('data-select2-id');
                select.removeAttribute('tabindex');
                select.removeAttribute('aria-hidden');
                Array.from(select.options).forEach(opt => {
                    opt.removeAttribute('data-select2-id');
                    opt.removeAttribute('selected');
                });

                select.name = `approvers[${rowIndex}][pegawai_id]`;
                select.value = "";

                input.name = `approvers[${rowIndex}][jabatan]`;
                input.value = "";

                rowNo.textContent = rowIndex + 1;
                btnRemove.style.display = 'inline-flex';

                container.appendChild(newRow);

                // Re-init Select2 for new row
                initSelect2InRow(newRow);

                // Ensure first row is removable if count > 1
                if(container.children.length > 1) {
                    const allRemoveBtns = container.querySelectorAll('.btn-remove-approver');
                    allRemoveBtns.forEach(btn => btn.style.display = 'inline-flex');
                }

                rowIndex++;
            });

            // Handle remove button
            container.addEventListener('click', function(e) {
                if (e.target.closest('.btn-remove-approver')) {
                    const row = e.target.closest('.approver-row');
                    if (container.children.length > 1) {
                        row.remove();
                        reindexRows();
                    }
                }
            });

            function reindexRows() {
                const rows = container.querySelectorAll('.approver-row');
                rows.forEach((row, index) => {
                    row.querySelector('.row-number').textContent = index + 1;
                    
                    const hiddenId = row.querySelector('input[type="hidden"]');
                    if (hiddenId) {
                        // Keep the ID if it exists (for existing approvals)
                        // Don't change it
                    }
                    
                    row.querySelector('select').name = `approvers[${index}][pegawai_id]`;
                    row.querySelector('input[type="text"]').name = `approvers[${index}][jabatan]`;

                    const btnRemove = row.querySelector('.btn-remove-approver');
                    if (rows.length === 1) {
                        btnRemove.style.display = 'none';
                    } else {
                        btnRemove.style.display = 'inline-flex';
                    }
                });
                rowIndex = rows.length;
            }

            function initSelect2InRow(row) {
                const select = row.querySelector('.select2-approval');
                if (!select) return;

                const $select = $(select);

                // Skip if already initialized
                if ($select.hasClass('select2-hidden-accessible')) return;
                
                // Ensure Select2 library is loaded
                if (typeof $.fn.select2 === 'undefined') {
                    console.warn('Select2 not loaded yet, skipping initialization');
                    return;
                }

                // Initialize Select2 with Bootstrap 5 theme
                $select.select2({
                    dropdownParent: $('#modalAction'),
                    placeholder: "Pilih Pegawai...",
                    allowClear: true,
                    width: '100%',
                    theme: 'bootstrap-5'
                });
            }
        }

        // Wait for Select2 to be FULLY loaded before initializing
        const initApprovalWithSelect2 = () => {
            if (typeof $.fn.select2 === 'undefined') {
                if (typeof window.loadSelect2 === 'function') {
                    // Show loading state
                    const btn = document.getElementById('btn-add-approver');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                    }
                    
                    window.loadSelect2().then(() => {
                        // Select2 fully loaded with theme
                        initApprovalForm();
                        
                        // Restore button
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="ti ti-plus me-1"></i> Tambah Approver';
                        }
                    }).catch(err => {
                        console.error('Failed to load Select2:', err);
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="ti ti-plus me-1"></i> Tambah Approver';
                        }
                    });
                }
            } else {
                // Already loaded
                initApprovalForm();
            }
        };

        if ($('#modalAction').hasClass('show')) {
            initApprovalWithSelect2();
        } else {
            $('#modalAction').on('shown.bs.modal', function () {
                initApprovalWithSelect2();
                $('#modalAction').off('shown.bs.modal', arguments.callee);
            });
        }
    }
</script>
