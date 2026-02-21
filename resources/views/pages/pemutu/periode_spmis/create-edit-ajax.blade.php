@php
    $isEdit = isset($periodeSpmi) && $periodeSpmi->exists;
    $title  = $isEdit ? 'Edit Periode SPMI' : 'Tambah Periode SPMI';
    $route  = $isEdit 
        ? route('pemutu.periode-spmis.update', $periodeSpmi->encrypted_periodespmi_id) 
        : route('pemutu.periode-spmis.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Update' : 'Simpan'"
>
    <div class="row">
         {{-- Info Dasar --}}
         <div class="col-md-6 mb-3">
            <x-tabler.form-input 
                name="periode" 
                label="Tahun Periode" 
                type="number" 
                placeholder="Contoh: 2026"
                value="{{ $periodeSpmi->periode ?? date('Y') }}" 
                required="true" 
            />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-select 
                name="jenis_periode" 
                label="Jenis Periode" 
                required="true"
                :options="[
                    'Akademik' => 'Akademik',
                    'Non Akademik' => 'Non Akademik'
                ]"
                :selected="$periodeSpmi->jenis_periode ?? ''"
            />
        </div>

        <div class="col-12"><div class="hr-text">Siklus PPEPP</div></div>

        {{-- 1. Penetapan --}}
        <div class="col-12 mb-2">
            <label class="form-label fw-bold text-primary"><i class="ti ti-checkbox me-1"></i> 1. Penetapan</label>
            <div class="row g-2">
                <div class="col-md-6">
                    <x-tabler.form-input name="penetapan_awal" label="Tgl Awal" type="date" value="{{ $periodeSpmi->penetapan_awal ? $periodeSpmi->penetapan_awal->format('Y-m-d') : '' }}" />
                </div>
                <div class="col-md-6">
                    <x-tabler.form-input name="penetapan_akhir" label="Tgl Akhir" type="date" value="{{ $periodeSpmi->penetapan_akhir ? $periodeSpmi->penetapan_akhir->format('Y-m-d') : '' }}" />
                </div>
            </div>
        </div>

        {{-- 2. Pelaksanaan --}}
         <div class="col-12 mb-2">
            <label class="form-label fw-bold text-success"><i class="ti ti-player-play me-1"></i> 2. Pelaksanaan / Evaluasi Diri</label>
            <div class="row g-2">
                <div class="col-md-6">
                    <x-tabler.form-input name="ed_awal" label="Tgl Awal" type="date" value="{{ $periodeSpmi->ed_awal ? $periodeSpmi->ed_awal->format('Y-m-d') : '' }}" />
                </div>
                <div class="col-md-6">
                    <x-tabler.form-input name="ed_akhir" label="Tgl Akhir" type="date" value="{{ $periodeSpmi->ed_akhir ? $periodeSpmi->ed_akhir->format('Y-m-d') : '' }}" />
                </div>
            </div>
        </div>

        {{-- 3. Evaluasi --}}
         <div class="col-12 mb-2">
            <label class="form-label fw-bold text-info"><i class="ti ti-search me-1"></i> 3. Evaluasi / AMI</label>
            <div class="row g-2">
                <div class="col-md-6">
                    <x-tabler.form-input name="ami_awal" label="Tgl Awal" type="date" value="{{ $periodeSpmi->ami_awal ? $periodeSpmi->ami_awal->format('Y-m-d') : '' }}" />
                </div>
                <div class="col-md-6">
                    <x-tabler.form-input name="ami_akhir" label="Tgl Akhir" type="date" value="{{ $periodeSpmi->ami_akhir ? $periodeSpmi->ami_akhir->format('Y-m-d') : '' }}" />
                </div>
            </div>
        </div>

        {{-- 4. Pengendalian --}}
         <div class="col-12 mb-2">
            <label class="form-label fw-bold text-warning"><i class="ti ti-shield-check me-1"></i> 4. Pengendalian</label>
            <div class="row g-2">
                <div class="col-md-6">
                    <x-tabler.form-input name="pengendalian_awal" label="Tgl Awal" type="date" value="{{ $periodeSpmi->pengendalian_awal ? $periodeSpmi->pengendalian_awal->format('Y-m-d') : '' }}" />
                </div>
                <div class="col-md-6">
                    <x-tabler.form-input name="pengendalian_akhir" label="Tgl Akhir" type="date" value="{{ $periodeSpmi->pengendalian_akhir ? $periodeSpmi->pengendalian_akhir->format('Y-m-d') : '' }}" />
                </div>
            </div>
        </div>

        {{-- 5. Peningkatan --}}
         <div class="col-12 mb-2">
            <label class="form-label fw-bold text-danger"><i class="ti ti-trending-up me-1"></i> 5. Peningkatan</label>
            <div class="row g-2">
                <div class="col-md-6">
                    <x-tabler.form-input name="peningkatan_awal" label="Tgl Awal" type="date" value="{{ $periodeSpmi->peningkatan_awal ? $periodeSpmi->peningkatan_awal->format('Y-m-d') : '' }}" />
                </div>
                <div class="col-md-6">
                    <x-tabler.form-input name="peningkatan_akhir" label="Tgl Akhir" type="date" value="{{ $periodeSpmi->peningkatan_akhir ? $periodeSpmi->peningkatan_akhir->format('Y-m-d') : '' }}" />
                </div>
            </div>
        </div>
    </div>
</x-tabler.form-modal>
