@php
    $isEdit = isset($periodeSpmi) && $periodeSpmi->exists;
    $title  = $isEdit ? 'Edit Periode SPMI' : 'Tambah Periode SPMI';
    $route  = $isEdit 
        ? route('pemutu.periode-spmi.update', $periodeSpmi->encrypted_periodespmi_id) 
        : route('pemutu.periode-spmi.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Update' : 'Simpan'"
>
    <div class="row g-3">
         {{-- Info Dasar --}}
         <div class="col-md-6">
            <x-tabler.form-input 
                name="periode" 
                label="Tahun Periode" 
                type="number" 
                placeholder="Contoh: 2026"
                value="{{ $periodeSpmi->periode ?? date('Y') }}" 
                required="true" 
                class="mb-0"
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-select 
                name="jenis_periode" 
                label="Jenis Periode" 
                required="true"
                :options="[
                    'Akademik' => 'Akademik',
                    'Non Akademik' => 'Non Akademik'
                ]"
                :selected="$periodeSpmi->jenis_periode ?? ''"
                class="mb-0"
            />
        </div>

        <div class="col-12"><div class="hr-text text-uppercase text-muted my-2">Siklus PPEPP</div></div>

        {{-- 1. Penetapan --}}
        <div class="col-12">
            <label class="form-label fw-bold text-primary mb-2">
                <i class="ti ti-checkbox me-1"></i> 1. Penetapan
            </label>
            <div class="row g-2">
                <div class="col-6">
                    <x-tabler.form-input name="penetapan_awal" placeholder="Tanggal Awal" type="date" value="{{ $periodeSpmi->penetapan_awal ? $periodeSpmi->penetapan_awal->format('Y-m-d') : '' }}" class="mb-0" />
                </div>
                <div class="col-6">
                    <x-tabler.form-input name="penetapan_akhir" placeholder="Tanggal Akhir" type="date" value="{{ $periodeSpmi->penetapan_akhir ? $periodeSpmi->penetapan_akhir->format('Y-m-d') : '' }}" class="mb-0" />
                </div>
            </div>
        </div>

        {{-- 2. Pelaksanaan --}}
         <div class="col-12">
            <label class="form-label fw-bold text-success mb-2">
                <i class="ti ti-player-play me-1"></i> 2. Pelaksanaan / Evaluasi Diri
            </label>
            <div class="row g-2">
                <div class="col-6">
                    <x-tabler.form-input name="ed_awal" placeholder="Tanggal Awal" type="date" value="{{ $periodeSpmi->ed_awal ? $periodeSpmi->ed_awal->format('Y-m-d') : '' }}" class="mb-0" />
                </div>
                <div class="col-6">
                    <x-tabler.form-input name="ed_akhir" placeholder="Tanggal Akhir" type="date" value="{{ $periodeSpmi->ed_akhir ? $periodeSpmi->ed_akhir->format('Y-m-d') : '' }}" class="mb-0" />
                </div>
            </div>
        </div>

        {{-- 3. Evaluasi --}}
         <div class="col-12">
            <label class="form-label fw-bold text-info mb-2">
                <i class="ti ti-search me-1"></i> 3. Evaluasi / AMI
            </label>
            <div class="row g-2">
                <div class="col-6">
                    <x-tabler.form-input name="ami_awal" placeholder="Tanggal Awal" type="date" value="{{ $periodeSpmi->ami_awal ? $periodeSpmi->ami_awal->format('Y-m-d') : '' }}" class="mb-0" />
                </div>
                <div class="col-6">
                    <x-tabler.form-input name="ami_akhir" placeholder="Tanggal Akhir" type="date" value="{{ $periodeSpmi->ami_akhir ? $periodeSpmi->ami_akhir->format('Y-m-d') : '' }}" class="mb-0" />
                </div>
            </div>
        </div>

        {{-- 4. Pengendalian --}}
         <div class="col-12">
            <label class="form-label fw-bold text-warning mb-2">
                <i class="ti ti-shield-check me-1"></i> 4. Pengendalian (RTM)
            </label>
            <div class="row g-2">
                <div class="col-6">
                    <x-tabler.form-input name="pengendalian_awal" placeholder="Tanggal Awal" type="date" value="{{ $periodeSpmi->pengendalian_awal ? $periodeSpmi->pengendalian_awal->format('Y-m-d') : '' }}" class="mb-0" />
                </div>
                <div class="col-6">
                    <x-tabler.form-input name="pengendalian_akhir" placeholder="Tanggal Akhir" type="date" value="{{ $periodeSpmi->pengendalian_akhir ? $periodeSpmi->pengendalian_akhir->format('Y-m-d') : '' }}" class="mb-0" />
                </div>
            </div>
        </div>

        {{-- 5. Peningkatan --}}
         <div class="col-12">
            <label class="form-label fw-bold text-danger mb-2">
                <i class="ti ti-trending-up me-1"></i> 5. Peningkatan
            </label>
            <div class="row g-2">
                <div class="col-6">
                    <x-tabler.form-input name="peningkatan_awal" placeholder="Tanggal Awal" type="date" value="{{ $periodeSpmi->peningkatan_awal ? $periodeSpmi->peningkatan_awal->format('Y-m-d') : '' }}" class="mb-0" />
                </div>
                <div class="col-6">
                    <x-tabler.form-input name="peningkatan_akhir" placeholder="Tanggal Akhir" type="date" value="{{ $periodeSpmi->peningkatan_akhir ? $periodeSpmi->peningkatan_akhir->format('Y-m-d') : '' }}" class="mb-0" />
                </div>
            </div>
        </div>
    </div>
    </div>
</x-tabler.form-modal>
