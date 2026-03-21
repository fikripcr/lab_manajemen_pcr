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
    <x-tabler.form-modal 
        :title="'Validasi Pengendalian — ' . ($indOrg->indikator->no_indikator ?? '')" 
        :route="route('pemutu.pengendalian.validasi', $indOrg->encrypted_indorgunit_id)" 
        method="POST" 
        data-redirect="false">

        {{-- Info Singkat Indikator --}}
        <div class="alert alert-info p-2 mb-3">
            <div class="fw-bold">{{ $indOrg->indikator->no_indikator }}</div>
            <div class="small text-muted">{{ $indOrg->indikator->indikator }}</div>
            <div class="mt-1">
                {!! pemutuDtColLabelsList($indOrg->indikator) !!}
            </div>
        </div>

        {{-- Hasil AMI --}}
        @if($hasilAmi)
        <div class="mb-3 d-flex align-items-center gap-2">
            <span class="text-muted small">Hasil AMI:</span>
            <span class="badge bg-{{ $hasilAmi['color'] }}-lt text-{{ $hasilAmi['color'] }}">{{ $hasilAmi['label'] }}</span>
        </div>
        @endif

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

                <span class="text-muted small ms-2">Important:</span>
                @if($indOrg->pengend_important_matrix === 'important')
                    <span class="badge bg-red-lt text-red">Important</span>
                @else
                    <span class="badge bg-secondary-lt">Not Important</span>
                @endif

                <span class="text-muted small ms-2">Urgent:</span>
                @if($indOrg->pengend_urgent_matrix === 'urgent')
                    <span class="badge bg-orange-lt text-orange">Urgent</span>
                @else
                    <span class="badge bg-secondary-lt">Not Urgent</span>
                @endif
            </div>
            @if($indOrg->pengend_analisis)
                <div class="mt-2 small text-muted" style="max-height: 80px; overflow-y: auto;">
                    <em>Analisis: {{ $indOrg->pengend_analisis }}</em>
                </div>
            @endif
        </div>

        {{-- Form: field _atsn, pre-filled --}}
        <div class="mb-3">
            <label class="form-label required small fw-bold text-uppercase">Status Indikator</label>
            <div class="form-selectgroup">
                <label class="form-selectgroup-item">
                    <input type="radio" name="pengend_status_atsn" value="tetap" class="form-selectgroup-input"
                           @checked(old('pengend_status_atsn', $indOrg->pengend_status_atsn) === 'tetap') required>
                    <span class="form-selectgroup-label">Dipertahankan</span>
                </label>
                <label class="form-selectgroup-item">
                    <input type="radio" name="pengend_status_atsn" value="penyesuaian" class="form-selectgroup-input"
                           @checked(old('pengend_status_atsn', $indOrg->pengend_status_atsn) === 'penyesuaian') required>
                    <span class="form-selectgroup-label">Disesuaikan</span>
                </label>
                <label class="form-selectgroup-item">
                    <input type="radio" name="pengend_status_atsn" value="ditingkatkan" class="form-selectgroup-input"
                           @checked(old('pengend_status_atsn', $indOrg->pengend_status_atsn) === 'ditingkatkan') required>
                    <span class="form-selectgroup-label">Ditingkatkan</span>
                </label>
                <label class="form-selectgroup-item">
                    <input type="radio" name="pengend_status_atsn" value="nonaktif" class="form-selectgroup-input"
                           @checked(old('pengend_status_atsn', $indOrg->pengend_status_atsn) === 'nonaktif') required>
                    <span class="form-selectgroup-label">Nonaktif</span>
                </label>
            </div>
        </div>

        <div class="mb-3">
            <x-tabler.form-textarea
                name="pengend_analisis_atsn"
                label="Catatan / Analisis"
                rows="3"
                placeholder="Berikan catatan tambahan jika ada..."
                :value="$indOrg->pengend_analisis_atsn ?? ''"
            />
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="small text-muted mb-1 d-block">Matrix Important</label>
                <div class="form-selectgroup w-100">
                    <label class="form-selectgroup-item flex-fill">
                        <input type="radio" name="pengend_important_matrix_atsn" value="important" class="form-selectgroup-input"
                               @checked($indOrg->pengend_important_matrix_atsn === 'important') required>
                        <span class="form-selectgroup-label">Important</span>
                    </label>
                    <label class="form-selectgroup-item flex-fill">
                        <input type="radio" name="pengend_important_matrix_atsn" value="not_important" class="form-selectgroup-input"
                               @checked($indOrg->pengend_important_matrix_atsn === 'not_important') required>
                        <span class="form-selectgroup-label">Not Important</span>
                    </label>
                </div>
            </div>
            <div class="col-6">
                <label class="small text-muted mb-1 d-block">Matrix Urgent</label>
                <div class="form-selectgroup w-100">
                    <label class="form-selectgroup-item flex-fill">
                        <input type="radio" name="pengend_urgent_matrix_atsn" value="urgent" class="form-selectgroup-input"
                               @checked($indOrg->pengend_urgent_matrix_atsn === 'urgent') required>
                        <span class="form-selectgroup-label">Urgent</span>
                    </label>
                    <label class="form-selectgroup-item flex-fill">
                        <input type="radio" name="pengend_urgent_matrix_atsn" value="not_urgent" class="form-selectgroup-input"
                               @checked($indOrg->pengend_urgent_matrix_atsn === 'not_urgent') required>
                        <span class="form-selectgroup-label">Not Urgent</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Note --}}
        <div class="alert alert-muted small mb-0">
            <i class="ti ti-info-circle me-1"></i>
            Data di atas sudah terisi sesuai isian auditee. Silakan sesuaikan jika ada yang perlu diubah.
        </div>
    </x-tabler.form-modal>
@endif
