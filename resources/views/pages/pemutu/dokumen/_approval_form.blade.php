<x-tabler.form-modal
    id_form="approval-form"
    title="Persetujuan Dokumen"
    route="{{ route('pemutu.dokumen.approve', $dokumen) }}"
    method="POST"
    submitText="Simpan Perubahan Approver"
>
    <div id="approver-container">
        @php 
            $existingApprovals = $dokumen->riwayatApprovals()->where('status', 'Pending')->get();
        @endphp

        @forelse($existingApprovals as $index => $approval)
            <div class="approver-row mb-3 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="text-muted">Approver #<span class="row-number">{{ $index + 1 }}</span></strong>
                </div>
                <div class="d-flex align-items-start gap-3 w-100">
                    <div class="flex-grow-1" style="flex: 1;">
                        <label class="form-label required mb-1">Approver (Pegawai)</label>
                        <select name="approvers[{{ $index }}][pegawai_id]" class="form-select select2-approval" required data-dropdown-parent="#modalAction">
                            <option value="">Pilih Pegawai...</option>
                            @foreach($pegawais as $p)
                                <option value="{{ $p->encrypted_pegawai_id }}" {{ $p->pegawai_id == $approval->pegawai_id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-grow-1" style="flex: 1;">
                        <label class="form-label required mb-1">Posisi / Jabatan</label>
                        <input type="text" name="approvers[{{ $index }}][jabatan]" class="form-control" placeholder="Contoh: Ketua Penjaminan Mutu" required value="{{ $approval->jabatan }}">
                    </div>
                    <div class="pt-4 mt-1">
                        <button type="button" class="btn btn-outline-danger btn-icon btn-remove-approver" title="Hapus" aria-label="Hapus">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <!-- Template Row if Empty -->
            <div class="approver-row mb-3 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="text-muted">Approver #<span class="row-number">1</span></strong>
                </div>
                <div class="d-flex align-items-start gap-3 w-100">
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
                const select = newRow.querySelector('select');
                const input = newRow.querySelector('input[type="text"]');
                const rowNo = newRow.querySelector('.row-number');
                const btnRemove = newRow.querySelector('.btn-remove-approver');

                // Destroy old Select2 if exists (important!)
                const $select = $(select);
                if ($select.hasClass('select2-hidden-accessible') && typeof $.fn.select2 !== 'undefined') {
                    $select.select2('destroy');
                }

                // Clean Select2 attributes
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
                
                // Re-init Select2 for new row (will be caught by global init or manual)
                // But we do it immediately for better UX
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
