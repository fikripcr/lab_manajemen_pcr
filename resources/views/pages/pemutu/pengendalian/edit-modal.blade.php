<x-tabler.form-modal 
    :title="'Pengendalian — ' . ($indOrg->indikator->no_indikator ?? '')" 
    :route="route('pemutu.pengendalian.update', $indOrg->encrypted_indorgunit_id)" 
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
        @php
            $hasilAmi = $indOrg->ami_hasil_akhir !== null ? ($hasilMap[$indOrg->ami_hasil_akhir] ?? null) : null;
        @endphp
        @if($hasilAmi)
        <div class="mb-3 d-flex align-items-center gap-2">
            <span class="text-muted small">Hasil AMI:</span>
            <span class="badge bg-{{ $hasilAmi['color'] }}-lt text-{{ $hasilAmi['color'] }}">{{ $hasilAmi['label'] }}</span>
        </div>
        @endif

        {{-- Status Pengendalian (Usulan Unit) --}}
        <div class="card mb-3 bg-light-lt">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <span class="avatar avatar-sm bg-blue-lt me-2"><i class="ti ti-user"></i></span>
                    <h4 class="card-title mb-0">Usulan Unit / Pelaksana</h4>
                </div>
                
                <div class="mb-3">
                    <label class="form-label required small fw-bold text-uppercase">Status Indikator (Unit)</label>
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
                        label="Deskripsi Analisis (Unit)"
                        rows="3"
                        required="true"
                        :value="$indOrg->pengend_analisis ?? ''"
                    />
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="small text-muted mb-1">Matrix Important</div>
                        <select name="pengend_important_matrix" class="form-select form-select-sm">
                            <option value="important" @selected($indOrg->pengend_important_matrix === 'important')>Important</option>
                            <option value="not_important" @selected($indOrg->pengend_important_matrix === 'not_important')>Not Important</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted mb-1">Matrix Urgent</div>
                        <select name="pengend_urgent_matrix" class="form-select form-select-sm">
                            <option value="urgent" @selected($indOrg->pengend_urgent_matrix === 'urgent')>Urgent</option>
                            <option value="not_urgent" @selected($indOrg->pengend_urgent_matrix === 'not_urgent')>Not Urgent</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Pengendalian (Keputusan Atasan) --}}
        @php
            // Idealnya pengecekan role dilakukan di sini, namun untuk demo kita tampilkan keduanya
            // atau gunakan is_admin/is_pemutu logic
            $canEditAtsn = true; 
        @endphp

        @if($canEditAtsn)
        <div class="card mb-0 border-primary shadow-sm">
            <div class="card-status-top bg-primary"></div>
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-3">
                    <span class="avatar avatar-sm bg-primary text-white me-2"><i class="ti ti-crown"></i></span>
                    <h4 class="card-title mb-0">Keputusan Atasan / Pemutu</h4>
                </div>

                <div class="mb-3">
                    <label class="form-label required small fw-bold text-uppercase text-primary">Status Akhir (Review)</label>
                    <div class="form-selectgroup">
                        <label class="form-selectgroup-item">
                            <input type="radio" name="pengend_status_atsn" value="tetap" class="form-selectgroup-input"
                                   @checked(old('pengend_status_atsn', $indOrg->pengend_status_atsn) === 'tetap') required>
                            <span class="form-selectgroup-label border-primary-subtle">Dipertahankan</span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="pengend_status_atsn" value="penyesuaian" class="form-selectgroup-input"
                                   @checked(old('pengend_status_atsn', $indOrg->pengend_status_atsn) === 'penyesuaian') required>
                            <span class="form-selectgroup-label border-primary-subtle">Disesuaikan</span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="pengend_status_atsn" value="ditingkatkan" class="form-selectgroup-input"
                                   @checked(old('pengend_status_atsn', $indOrg->pengend_status_atsn) === 'ditingkatkan') required>
                            <span class="form-selectgroup-label border-primary-subtle">Ditingkatkan</span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="pengend_status_atsn" value="nonaktif" class="form-selectgroup-input"
                                   @checked(old('pengend_status_atsn', $indOrg->pengend_status_atsn) === 'nonaktif') required>
                            <span class="form-selectgroup-label border-primary-subtle">Nonaktif</span>
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <x-tabler.form-textarea
                        name="pengend_analisis_atsn"
                        label="Catatan/Analisis Atasan"
                        rows="3"
                        placeholder="Berikan catatan tambahan jika ada perubahan keputusan..."
                        :value="$indOrg->pengend_analisis_atsn ?? ''"
                    />
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="small text-muted mb-1">Matrix Important (Final)</div>
                        <select name="pengend_important_matrix_atsn" class="form-select form-select-sm border-primary-subtle">
                            <option value="important" @selected($indOrg->pengend_important_matrix_atsn === 'important')>Important</option>
                            <option value="not_important" @selected($indOrg->pengend_important_matrix_atsn === 'not_important')>Not Important</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted mb-1">Matrix Urgent (Final)</div>
                        <select name="pengend_urgent_matrix_atsn" class="form-select form-select-sm border-primary-subtle">
                            <option value="urgent" @selected($indOrg->pengend_urgent_matrix_atsn === 'urgent')>Urgent</option>
                            <option value="not_urgent" @selected($indOrg->pengend_urgent_matrix_atsn === 'not_urgent')>Not Urgent</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        @endif
</x-tabler.form-modal>

