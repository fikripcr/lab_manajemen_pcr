@php $isReadonly = request('readonly') == 1; @endphp
<x-tabler.form-modal 
    :title="($isReadonly ? 'Detail Pengendalian' : 'Pengendalian Indikator')" 
    :route="route('pemutu.pengendalian.update', $indOrg->encrypted_indorgunit_id)" 
    :method="$isReadonly ? 'none' : 'POST'" 
    data-redirect="false">
    <fieldset {{ $isReadonly ? 'disabled' : '' }}>
        {{-- Status Pengendalian (Usulan Unit) --}}
            <div class="">
                <div class="d-flex align-items-center mb-2">
                    <span class="avatar avatar-sm bg-blue-lt me-2"><i class="ti ti-user"></i></span>
                    <h4 class="card-title mb-0">Isi Pengendalian — Usulan Auditee</h4>
                </div>
                
                <div class="mb-3">
                    <label class="form-label required small fw-bold text-uppercase">Status Indikator</label>
                    <div class="form-selectgroup">
                        <label class="form-selectgroup-item">
                            <input type="radio" name="pengend_status" value="tetap" class="form-selectgroup-input"
                                   @checked(old('pengend_status', $indOrg->pengend_status) === 'tetap') required>
                            <span class="form-selectgroup-label">Dipertahankan</span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="pengend_status" value="penyesuaian" class="form-selectgroup-input"
                                   @checked(old('pengend_status', $indOrg->pengend_status) === 'penyesuaian') required>
                            <span class="form-selectgroup-label">Disesuaikan</span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="pengend_status" value="ditingkatkan" class="form-selectgroup-input"
                                   @checked(old('pengend_status', $indOrg->pengend_status) === 'ditingkatkan') required>
                            <span class="form-selectgroup-label">Ditingkatkan</span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="pengend_status" value="nonaktif" class="form-selectgroup-input"
                                   @checked(old('pengend_status', $indOrg->pengend_status) === 'nonaktif') required>
                            <span class="form-selectgroup-label">Nonaktif</span>
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <x-tabler.form-textarea
                        name="pengend_analisis"
                        label="Deskripsi Analisis"
                        rows="3"
                        required="true"
                        :value="$indOrg->pengend_analisis ?? ''"
                    />
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <label class="small text-muted mb-1 d-block">Matrix Important <span class="text-danger">*</span></label>
                        <div class="form-selectgroup w-100">
                            <label class="form-selectgroup-item flex-fill">
                                <input type="radio" name="pengend_important_matrix" value="important" class="form-selectgroup-input"
                                       @checked($indOrg->pengend_important_matrix === 'important') required>
                                <span class="form-selectgroup-label">Important</span>
                            </label>
                            <label class="form-selectgroup-item flex-fill">
                                <input type="radio" name="pengend_important_matrix" value="not_important" class="form-selectgroup-input"
                                       @checked($indOrg->pengend_important_matrix === 'not_important') required>
                                <span class="form-selectgroup-label">Not Important</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="small text-muted mb-1 d-block">Matrix Urgent <span class="text-danger">*</span></label>
                        <div class="form-selectgroup w-100">
                            <label class="form-selectgroup-item flex-fill">
                                <input type="radio" name="pengend_urgent_matrix" value="urgent" class="form-selectgroup-input"
                                       @checked($indOrg->pengend_urgent_matrix === 'urgent') required>
                                <span class="form-selectgroup-label">Urgent</span>
                            </label>
                            <label class="form-selectgroup-item flex-fill">
                                <input type="radio" name="pengend_urgent_matrix" value="not_urgent" class="form-selectgroup-input"
                                       @checked($indOrg->pengend_urgent_matrix === 'not_urgent') required>
                                <span class="form-selectgroup-label">Not Urgent</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
    </fieldset>
</x-tabler.form-modal>
