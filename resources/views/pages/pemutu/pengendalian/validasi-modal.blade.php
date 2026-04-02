@php
    $hasilAmi = $indOrg->ami_hasil_akhir !== null ? ($hasilMap[$indOrg->ami_hasil_akhir] ?? null) : null;
    $sudahDiisi = !empty($indOrg->pengend_status);
@endphp

@if(!$sudahDiisi)
    {{-- Belum diisi oleh auditee --}}
    <div class="modal-body">
        <div class="empty py-5">
            <div class="empty-icon">
                <span class="avatar avatar-xl rounded bg-warning-lt">
                    <i class="ti ti-alert-triangle fs-1 text-warning"></i>
                </span>
            </div>
            <h3 class="empty-title mt-3">Pengendalian Belum Dilakukan</h3>
            <p class="empty-subtitle text-muted">
                Auditee/unit belum mengisi data pengendalian untuk indikator
                <strong>{{ $indOrg->indikator->no_indikator }}</strong>.
                <br>Validasi hanya dapat dilakukan setelah unit mengisi pengendalian terlebih dahulu.
            </p>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
@else
@php $isReadonly = request('readonly') == 1; @endphp
    <x-tabler.form-modal 
        :title="($isReadonly ? 'Detail Validasi Pengendalian' : 'Validasi Pengendalian Indikator')" 
        :route="route('pemutu.pengendalian.validasi', $indOrg->encrypted_indorgunit_id)" 
        :method="$isReadonly ? 'none' : 'POST'" 
        data-redirect="false">
        <fieldset {{ $isReadonly ? 'disabled' : '' }}>

        {{-- Info: Data yang diisi Auditee --}}
        @php $s = $statusMap[$indOrg->pengend_status] ?? null; @endphp
        <div class="alert alert-secondary mb-3">
            <div class="d-flex align-items-center mb-2">
                <i class="ti ti-user-check me-2 text-blue"></i>
                <strong>Auditee mengisi sebagai berikut:</strong>
            </div>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="text-muted small">Status:</span>
                @if($s)
                    <span class="badge bg-{{ $s['color'] }}-lt text-{{ $s['color'] }}">{{ $s['label'] }}</span>
                @endif

                @if($indOrg->pengend_important_matrix === 'important')
                    <span class="badge bg-red-lt text-red">Important</span>
                @else
                    <span class="badge bg-secondary-lt">Not Important</span>
                @endif

                @if($indOrg->pengend_urgent_matrix === 'urgent')
                    <span class="badge bg-orange-lt text-orange">Urgent</span>
                @else
                    <span class="badge bg-secondary-lt">Not Urgent</span>
                @endif
            </div>
            @if($indOrg->pengend_analisis)
                <div class="mt-2 small text-muted" style="max-height: 80px; overflow-y: auto;">
                    <em><b>Analisis:<b> {{ $indOrg->pengend_analisis }}</em>
                </div>
            @endif
        </div>

        {{-- Approval Toggle Section --}}
        <div class="card mb-3 border-dashed bg-light-lt">
            <div class="card-body p-3">
                <label class="form-label fw-bold mb-2">Apakah Anda menyetujui pengendalian yang diisi oleh auditee tersebut?</label>
                <div class="form-selectgroup form-selectgroup-pills">
                    <label class="form-selectgroup-item">
                        <input type="radio" name="is_approved_toggle" value="ya" class="form-selectgroup-input" checked>
                        <span class="form-selectgroup-label px-3 h-auto py-2 border-2">Ya, Setujui</span>
                    </label>
                    <label class="form-selectgroup-item">
                        <input type="radio" name="is_approved_toggle" value="tidak" class="form-selectgroup-input">
                        <span class="form-selectgroup-label px-3 h-auto py-2 border-2">Tidak, Perlu Penyesuaian</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Section: Approval Confirmation (Shown if "Ya") --}}
        <div id="approval-confirmation-info" class="alert alert-success border-2 mb-3">
            <div class="d-flex align-items-center mb-1">
                <i class="ti ti-circle-check-filled me-2 fs-2"></i>
                <h4 class="alert-title mb-0">Konfirmasi Persetujuan</h4>
            </div>
            <div class="small">Ya, saya sepakat dan menyetujui usulan pengendalian dari auditee tersebut sesuai data isian di atas.</div>
        </div>

        {{-- Section: Manual Form (Shown if "Tidak") --}}
        <div id="manual-validation-container" class="d-none">
            <div class="hr-text hr-text-left text-danger fw-bold mt-4 mb-3">Silahkan isi status pengendalian yang baru</div>
            
            <div class="mb-3">
                <label class="form-label required small fw-bold text-uppercase">Status Indikator Validasi</label>
                <div class="form-selectgroup">
                    <label class="form-selectgroup-item">
                        <input type="radio" name="pengend_status_atsn" value="tetap" class="form-selectgroup-input"
                               @checked(old('pengend_status_atsn', $indOrg->pengend_status_atsn ?? $indOrg->pengend_status) === 'tetap') required>
                        <span class="form-selectgroup-label">Dipertahankan</span>
                    </label>
                    <label class="form-selectgroup-item">
                        <input type="radio" name="pengend_status_atsn" value="penyesuaian" class="form-selectgroup-input"
                               @checked(old('pengend_status_atsn', $indOrg->pengend_status_atsn ?? $indOrg->pengend_status) === 'penyesuaian') required>
                        <span class="form-selectgroup-label">Disesuaikan</span>
                    </label>
                    <label class="form-selectgroup-item">
                        <input type="radio" name="pengend_status_atsn" value="ditingkatkan" class="form-selectgroup-input"
                               @checked(old('pengend_status_atsn', $indOrg->pengend_status_atsn ?? $indOrg->pengend_status) === 'ditingkatkan') required>
                        <span class="form-selectgroup-label">Ditingkatkan</span>
                    </label>
                    <label class="form-selectgroup-item">
                        <input type="radio" name="pengend_status_atsn" value="nonaktif" class="form-selectgroup-input"
                               @checked(old('pengend_status_atsn', $indOrg->pengend_status_atsn ?? $indOrg->pengend_status) === 'nonaktif') required>
                        <span class="form-selectgroup-label">Nonaktif</span>
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <x-tabler.form-textarea
                    name="pengend_analisis_atsn"
                    label="Catatan / Analisis Validasi"
                    rows="3"
                    placeholder="Berikan catatan tambahan mengapa Anda menyesuaikan isian ini..."
                    :value="$indOrg->pengend_analisis_atsn ?? $indOrg->pengend_analisis ?? ''"
                />
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="small text-muted mb-1 d-block fw-bold text-uppercase">Matrix Important</label>
                    <div class="form-selectgroup w-100">
                        <label class="form-selectgroup-item flex-fill">
                            <input type="radio" name="pengend_important_matrix_atsn" value="important" class="form-selectgroup-input"
                                   @checked(($indOrg->pengend_important_matrix_atsn ?? $indOrg->pengend_important_matrix) === 'important') required>
                            <span class="form-selectgroup-label">Important</span>
                        </label>
                        <label class="form-selectgroup-item flex-fill">
                            <input type="radio" name="pengend_important_matrix_atsn" value="not_important" class="form-selectgroup-input"
                                   @checked(($indOrg->pengend_important_matrix_atsn ?? $indOrg->pengend_important_matrix) === 'not_important') required>
                            <span class="form-selectgroup-label">Not Important</span>
                        </label>
                    </div>
                </div>
                <div class="col-6">
                    <label class="small text-muted mb-1 d-block fw-bold text-uppercase">Matrix Urgent</label>
                    <div class="form-selectgroup w-100">
                        <label class="form-selectgroup-item flex-fill">
                            <input type="radio" name="pengend_urgent_matrix_atsn" value="urgent" class="form-selectgroup-input"
                                   @checked(($indOrg->pengend_urgent_matrix_atsn ?? $indOrg->pengend_urgent_matrix) === 'urgent') required>
                            <span class="form-selectgroup-label">Urgent</span>
                        </label>
                        <label class="form-selectgroup-item flex-fill">
                            <input type="radio" name="pengend_urgent_matrix_atsn" value="not_urgent" class="form-selectgroup-input"
                                   @checked(($indOrg->pengend_urgent_matrix_atsn ?? $indOrg->pengend_urgent_matrix) === 'not_urgent') required>
                            <span class="form-selectgroup-label">Not Urgent</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hidden copy-fields used to sync values if manual form is hidden --}}
        <div id="hidden-sync-fields" class="d-none">
            {{-- Auditor original values purely for JavaScript reference --}}
            <div id="auditee-val-status">{{ $indOrg->pengend_status }}</div>
            <div id="auditee-val-analisis">{{ $indOrg->pengend_analisis }}</div>
            <div id="auditee-val-important">{{ $indOrg->pengend_important_matrix }}</div>
            <div id="auditee-val-urgent">{{ $indOrg->pengend_urgent_matrix }}</div>
        </div>

        <script>
            setTimeout(() => {
                const toggleYa = document.querySelector('input[name="is_approved_toggle"][value="ya"]');
                const toggleTidak = document.querySelector('input[name="is_approved_toggle"][value="tidak"]');
                const infoBox = document.getElementById('approval-confirmation-info');
                const manualForm = document.getElementById('manual-validation-container');
                
                // Form Fields
                const statusRadios = document.querySelectorAll('input[name="pengend_status_atsn"]');
                const analisisTextarea = document.querySelector('textarea[name="pengend_analisis_atsn"]');
                const importantRadios = document.querySelectorAll('input[name="pengend_important_matrix_atsn"]');
                const urgentRadios = document.querySelectorAll('input[name="pengend_urgent_matrix_atsn"]');
                
                // Auditee Values
                const auditeeStatus = document.getElementById('auditee-val-status').textContent;
                const auditeeAnalisis = document.getElementById('auditee-val-analisis').textContent;
                const auditeeImportant = document.getElementById('auditee-val-important').textContent;
                const auditeeUrgent = document.getElementById('auditee-val-urgent').textContent;

                function syncToAuditeeValues() {
                    statusRadios.forEach(r => { if(r.value === auditeeStatus) r.checked = true; });
                    if(analisisTextarea) analisisTextarea.value = auditeeAnalisis;
                    importantRadios.forEach(r => { if(r.value === auditeeImportant) r.checked = true; });
                    urgentRadios.forEach(r => { if(r.value === auditeeUrgent) r.checked = true; });
                }

                function updateView() {
                    if (toggleYa.checked) {
                        infoBox.classList.remove('d-none');
                        manualForm.classList.add('d-none');
                        syncToAuditeeValues();
                    } else {
                        infoBox.classList.add('d-none');
                        manualForm.classList.remove('d-none');
                    }
                }

                toggleYa.addEventListener('change', updateView);
                toggleTidak.addEventListener('change', updateView);

                // Initial run
                updateView();
            }, 100);
        </script>

        </fieldset>
    </x-tabler.form-modal>
@endif
