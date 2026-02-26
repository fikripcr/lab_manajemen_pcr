<div class="modal-header">
    <h5 class="modal-title">
        <i class="ti ti-settings-check me-2 text-teal"></i>
        Pengendalian — {{ $indOrg->indikator->no_indikator ?? '' }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('pemutu.pengendalian.update', $indOrg->encrypted_indorgunit_id) }}"
      method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">

        {{-- Info Singkat Indikator --}}
        <div class="alert alert-info p-2 mb-3">
            <div class="fw-bold">{{ $indOrg->indikator->no_indikator }}</div>
            <div class="small text-muted">{{ $indOrg->indikator->indikator }}</div>
            <div class="mt-1">
                @forelse($indOrg->indikator->labels as $label)
                    <span class="badge bg-{{ $label->color ?? 'secondary' }}-lt text-{{ $label->color ?? 'secondary' }}">{{ $label->name }}</span>
                @empty @endforelse
            </div>
        </div>

        {{-- Hasil AMI --}}
        @php
            $hasilMap = \App\Models\Pemutu\IndikatorOrgUnit::$hasilAkhirLabels;
            $hasilAmi = $indOrg->ami_hasil_akhir !== null ? ($hasilMap[$indOrg->ami_hasil_akhir] ?? null) : null;
        @endphp
        @if($hasilAmi)
        <div class="mb-3 d-flex align-items-center gap-2">
            <span class="text-muted small">Hasil AMI:</span>
            <span class="badge bg-{{ $hasilAmi['color'] }}-lt text-{{ $hasilAmi['color'] }}">{{ $hasilAmi['label'] }}</span>
        </div>
        @endif

        {{-- Status Pengendalian --}}
        <div class="mb-3">
            <label class="form-label required fw-semibold">Status Indikator</label>
            <div class="form-selectgroup">
                <label class="form-selectgroup-item">
                    <input type="radio" name="pengend_status" value="tetap" class="form-selectgroup-input"
                           @checked(old('pengend_status', $indOrg->pengend_status) === 'tetap')>
                    <span class="form-selectgroup-label">
                        <i class="ti ti-check me-1 text-success"></i> Tetap
                    </span>
                </label>
                <label class="form-selectgroup-item">
                    <input type="radio" name="pengend_status" value="penyesuaian" class="form-selectgroup-input"
                           @checked(old('pengend_status', $indOrg->pengend_status) === 'penyesuaian')>
                    <span class="form-selectgroup-label">
                        <i class="ti ti-edit me-1 text-warning"></i> Penyesuaian
                    </span>
                </label>
                <label class="form-selectgroup-item">
                    <input type="radio" name="pengend_status" value="nonaktif" class="form-selectgroup-input"
                           @checked(old('pengend_status', $indOrg->pengend_status) === 'nonaktif')>
                    <span class="form-selectgroup-label">
                        <i class="ti ti-ban me-1 text-danger"></i> Nonaktifkan
                    </span>
                </label>
            </div>
        </div>

        {{-- Eisenhower Matrix --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Eisenhower Matrix</label>
            <div class="row g-2">
                <div class="col-6">
                    <div class="small text-muted mb-1">Tingkat Kepentingan</div>
                    <div class="form-selectgroup">
                        <label class="form-selectgroup-item flex-fill">
                            <input type="radio" name="pengend_important_matrix" value="important" class="form-selectgroup-input"
                                   @checked(old('pengend_important_matrix', $indOrg->pengend_important_matrix) === 'important')>
                            <span class="form-selectgroup-label w-100 text-center">
                                <i class="ti ti-star me-1 text-danger"></i> Important
                            </span>
                        </label>
                        <label class="form-selectgroup-item flex-fill">
                            <input type="radio" name="pengend_important_matrix" value="not_important" class="form-selectgroup-input"
                                   @checked(old('pengend_important_matrix', $indOrg->pengend_important_matrix) === 'not_important')>
                            <span class="form-selectgroup-label w-100 text-center">
                                <i class="ti ti-star-off me-1 text-muted"></i> Not Important
                            </span>
                        </label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="small text-muted mb-1">Tingkat Urgensitas</div>
                    <div class="form-selectgroup">
                        <label class="form-selectgroup-item flex-fill">
                            <input type="radio" name="pengend_urgent_matrix" value="urgent" class="form-selectgroup-input"
                                   @checked(old('pengend_urgent_matrix', $indOrg->pengend_urgent_matrix) === 'urgent')>
                            <span class="form-selectgroup-label w-100 text-center">
                                <i class="ti ti-bolt me-1 text-orange"></i> Urgent
                            </span>
                        </label>
                        <label class="form-selectgroup-item flex-fill">
                            <input type="radio" name="pengend_urgent_matrix" value="not_urgent" class="form-selectgroup-input"
                                   @checked(old('pengend_urgent_matrix', $indOrg->pengend_urgent_matrix) === 'not_urgent')>
                            <span class="form-selectgroup-label w-100 text-center">
                                <i class="ti ti-bolt-off me-1 text-muted"></i> Not Urgent
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Analisis --}}
        <div class="mb-3">
            <label class="form-label required fw-semibold">Deskripsi Analisis</label>
            <textarea id="pengend_analisis" name="pengend_analisis" class="form-control" rows="5">{{ old('pengend_analisis', $indOrg->pengend_analisis) }}</textarea>
        </div>


    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <x-tabler.button type="submit" icon="ti ti-device-floppy" text="Simpan" class="btn-primary" />
    </div>
</form>
