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
                    <strong>Approver #<span class="row-number">{{ $index + 1 }}</span></strong>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-approver">
                        <i class="ti ti-trash"></i> Hapus
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label required">Approver (Pegawai)</label>
                        <select name="approvers[{{ $index }}][pegawai_id]" class="form-select select2-approval" required data-dropdown-parent="#modalAction">
                            <option value="">Pilih Pegawai...</option>
                            @foreach($pegawais as $p)
                                <option value="{{ $p->encrypted_pegawai_id }}" {{ $p->pegawai_id == $approval->pegawai_id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <x-tabler.form-input 
                            name="approvers[{{ $index }}][jabatan]" 
                            label="Posisi / Jabatan" 
                            placeholder="Contoh: Ketua Penjaminan Mutu" 
                            required="true" 
                            value="{{ $approval->jabatan }}"
                        />
                    </div>
                </div>
            </div>
        @empty
            <!-- Template Row if Empty -->
            <div class="approver-row mb-3 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Approver #<span class="row-number">1</span></strong>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-approver" style="display: none;">
                        <i class="ti ti-trash"></i> Hapus
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label required">Approver (Pegawai)</label>
                        <select name="approvers[0][pegawai_id]" class="form-select select2-approval" required data-dropdown-parent="#modalAction">
                            <option value="">Pilih Pegawai...</option>
                            @foreach($pegawais as $p)
                                <option value="{{ $p->encrypted_pegawai_id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <x-tabler.form-input name="approvers[0][jabatan]" label="Posisi / Jabatan" placeholder="Contoh: Ketua Penjaminan Mutu" required="true" />
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
        function initApprovalForm() {
            const container = document.getElementById('approver-container');
            const btnAdd = document.getElementById('btn-add-approver');
            let rowIndex = container.querySelectorAll('.approver-row').length;
            
            // Initial Select2 init for all existing rows
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
                
                // Clear old Select2 container if cloned
                const select2Span = newRow.querySelector('.select2-container');
                if (select2Span) { select2Span.remove(); }
                
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
                if (select && typeof $ !== 'undefined' && $.fn.select2) {
                    // Avoid re-initializing if already done
                    if (!$(select).hasClass("select2-hidden-accessible")) {
                        $(select).select2({
                            dropdownParent: $('#modalAction'),
                            placeholder: "Pilih Pegawai...",
                            allowClear: true,
                            width: '100%',
                        });
                    }
                }
            }
        }
        
        if ($('#modalAction').hasClass('show')) {
            initApprovalForm();
        } else {
            $('#modalAction').on('shown.bs.modal', function () {
                initApprovalForm();
                $('#modalAction').off('shown.bs.modal', arguments.callee);
            });
        }
    }
</script>
