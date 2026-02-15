@php
    $item = $periodeSpmi ?? new \App\Models\Pemutu\PeriodeSpmi();
    $route = $item->exists 
        ? route('pemutu.periode-spmis.update', $item->encrypted_periodespmi_id) 
        : route('pemutu.periode-spmis.store');
    $method = $item->exists ? 'PUT' : 'POST';
    $title = $item->exists ? 'Edit Periode SPMI' : 'Tambah Periode SPMI';
@endphp

<form action="{{ $route }}" method="POST" class="ajax-form">
    @csrf
    @method($method)
    
    <div class="modal-header">
        <h5 class="modal-title">{{ $title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="row">
             {{-- Info Dasar --}}
             <div class="col-md-6 mb-3">
                <x-tabler.form-input 
                    name="periode" 
                    label="Tahun Periode" 
                    type="number" 
                    placeholder="Contoh: 2026"
                    value="{{ $item->periode ?? date('Y') }}" 
                    required="true" 
                />
            </div>
            <div class="col-md-6 mb-3">
                <x-tabler.form-select 
                    name="jenis_periode" 
                    label="Jenis Periode" 
                    required="true"
                    :options="[
                        'Tahunan' => 'Tahunan',
                        'Semester Ganjil' => 'Semester Ganjil',
                        'Semester Genap' => 'Semester Genap'
                    ]"
                    :selected="$item->jenis_periode ?? ''"
                />
            </div>

            <div class="col-12"><div class="hr-text">Siklus PPEPP</div></div>

            {{-- 1. Penetapan --}}
            <div class="col-12 mb-2">
                <label class="form-label fw-bold text-primary"><i class="ti ti-checkbox me-1"></i> 1. Penetapan</label>
                <div class="row g-2">
                    <div class="col-md-6">
                        <x-tabler.form-input name="penetapan_awal" label="Tgl Awal" type="date" value="{{ $item->penetapan_awal ? $item->penetapan_awal->format('Y-m-d') : '' }}" />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input name="penetapan_akhir" label="Tgl Akhir" type="date" value="{{ $item->penetapan_akhir ? $item->penetapan_akhir->format('Y-m-d') : '' }}" />
                    </div>
                </div>
            </div>

            {{-- 2. Pelaksanaan --}}
             <div class="col-12 mb-2">
                <label class="form-label fw-bold text-success"><i class="ti ti-player-play me-1"></i> 2. Pelaksanaan / Evaluasi Diri</label>
                <div class="row g-2">
                    <div class="col-md-6">
                        <x-tabler.form-input name="ed_awal" label="Tgl Awal" type="date" value="{{ $item->ed_awal ? $item->ed_awal->format('Y-m-d') : '' }}" />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input name="ed_akhir" label="Tgl Akhir" type="date" value="{{ $item->ed_akhir ? $item->ed_akhir->format('Y-m-d') : '' }}" />
                    </div>
                </div>
            </div>

            {{-- 3. Evaluasi --}}
             <div class="col-12 mb-2">
                <label class="form-label fw-bold text-info"><i class="ti ti-search me-1"></i> 3. Evaluasi / AMI</label>
                <div class="row g-2">
                    <div class="col-md-6">
                        <x-tabler.form-input name="ami_awal" label="Tgl Awal" type="date" value="{{ $item->ami_awal ? $item->ami_awal->format('Y-m-d') : '' }}" />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input name="ami_akhir" label="Tgl Akhir" type="date" value="{{ $item->ami_akhir ? $item->ami_akhir->format('Y-m-d') : '' }}" />
                    </div>
                </div>
            </div>

            {{-- 4. Pengendalian --}}
             <div class="col-12 mb-2">
                <label class="form-label fw-bold text-warning"><i class="ti ti-shield-check me-1"></i> 4. Pengendalian</label>
                <div class="row g-2">
                    <div class="col-md-6">
                        <x-tabler.form-input name="pengendalian_awal" label="Tgl Awal" type="date" value="{{ $item->pengendalian_awal ? $item->pengendalian_awal->format('Y-m-d') : '' }}" />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input name="pengendalian_akhir" label="Tgl Akhir" type="date" value="{{ $item->pengendalian_akhir ? $item->pengendalian_akhir->format('Y-m-d') : '' }}" />
                    </div>
                </div>
            </div>

            {{-- 5. Peningkatan --}}
             <div class="col-12 mb-2">
                <label class="form-label fw-bold text-danger"><i class="ti ti-trending-up me-1"></i> 5. Peningkatan</label>
                <div class="row g-2">
                    <div class="col-md-6">
                        <x-tabler.form-input name="peningkatan_awal" label="Tgl Awal" type="date" value="{{ $item->peningkatan_awal ? $item->peningkatan_awal->format('Y-m-d') : '' }}" />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input name="peningkatan_akhir" label="Tgl Akhir" type="date" value="{{ $item->peningkatan_akhir ? $item->peningkatan_akhir->format('Y-m-d') : '' }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary ms-auto">
            <i class="ti ti-device-floppy me-2"></i> Simpan
        </button>
    </div>
</form>
