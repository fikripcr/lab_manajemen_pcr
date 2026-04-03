@php
    $isEdit = $indikator->exists;
    $title = $isEdit ? 'Edit Indikator [' . $indikator->no_indikator . ']' : 'Tambah Indikator Baru';
    $route = $isEdit ? route('pemutu.indikator.update', $indikator) : route('pemutu.indikator.store');
    $method = $isEdit ? 'PUT' : 'POST';

    $assignedMap = $isEdit ? $indikator->orgUnits->keyBy('orgunit_id') : collect([]);

    $type = old('type', $indikator->type ?? request('type', 'standar'));
    $isPerforma = $type === 'performa';
    $isStandar = true; // All indicators are technically standar type
@endphp


    @extends('layouts.tabler.app')

    @section('header')
    <x-tabler.page-header :title="$title" pretitle="SPMI / Indikator">
        <span class="status status-{{ $isPerforma ? 'danger' : 'primary' }} fs-3 fw-bold ms-3 px-3 py-1 border shadow-sm">
            <i class="ti ti-tag me-2"></i>{{ $isPerforma ? 'INDIKATOR PERFORMA' : 'INDIKATOR STANDAR' }}
        </span>

        @if($isStaging ?? false)
            <span class="status status-yellow fs-3 fw-bold ms-2 px-3 py-1 border border-yellow shadow-sm">
                <i class="ti ti-clock-pause me-2"></i>DRAFT / STAGING
            </span>
        @endif
        <x-slot:actions>
            <x-tabler.button href="javascript:history.back()" type="back" />
            <x-tabler.button type="submit" :text="$isEdit ? 'Update Indikator' : 'Simpan Indikator'" form="form-indikator" />
        </x-slot:actions>
    </x-tabler.page-header>
    @endsection

    @section('content')
    <form action="{{ $route }}" method="POST" class="ajax-form" id="form-indikator" novalidate>
        @csrf
        @if($isEdit) @method('PUT') @endif
        <input type="hidden" name="redirect_to" value="{{ old('redirect_to', request('redirect_to', url()->previous())) }}">

        <input type="hidden" name="type" value="{{ $type }}">

        <div class="row row-cards">
            <!-- INFORMASI UMUM & SKALA (KIRI) -->
            <div class="col-lg-7">
                <x-tabler.card>
                    <x-tabler.card-header>
                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" id="indikator-form-tabs">
                            <li class="nav-item">
                                <a href="#tab-informasi-umum" class="nav-link active" data-bs-toggle="tab">
                                    <i class="ti ti-info-circle me-2"></i>Informasi Umum
                                </a>
                            </li>
                            <li class="nav-item tab-link-skala-container" id="tab-link-skala-container">
                                <a href="#tab-penilaian-skala" class="nav-link" data-bs-toggle="tab" id="tab-link-skala">
                                    <i class="ti ti-list-numbers me-2"></i>Penilaian Skala
                                </a>
                            </li>
                        </ul>
                    </x-tabler.card-header>
                    
                    <x-tabler.card-body>
                        <div class="tab-content">
                            <!-- TAB: INFORMASI UMUM -->
                            <div class="tab-pane active show" id="tab-informasi-umum">
                                
                                <div class="row mb-3">

                                    <div class="col-md-3">
                                        <x-tabler.form-select
                                            name="kelompok_indikator"
                                            label="Kelompok"
                                            :required="true"
                                            :options="[
                                                'Akademik' => 'Akademik',
                                                'Non Akademik' => 'Non Akademik'
                                            ]"
                                            :selected="old('kelompok_indikator', $indikator->kelompok_indikator)"
                                        />
                                    </div>

                                    <div class="col-md-3">
                                        <x-tabler.form-select
                                            name="jenis_data"
                                            label="Jenis Data"
                                            :required="false"
                                            :options="[
                                                'Kualitatif' => 'Kualitatif',
                                                'Kuantitatif' => 'Kuantitatif'
                                            ]"
                                            :selected="old('jenis_data', $indikator->jenis_data)"
                                        />
                                    </div>

                                    <div class="col-md-3">
                                        <x-tabler.form-select
                                            name="level_risk"
                                            label="Level Risiko"
                                            :required="false"
                                            :options="[
                                                'LOW RISK' => 'Low Risk',
                                                'MEDIUM RISK' => 'Medium Risk',
                                                'HIGH RISK' => 'High Risk'
                                            ]"
                                            :selected="old('level_risk', $indikator->level_risk ?? 'LOW RISK')"
                                        />
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label text-muted small">Sumber / Origin</label>
                                        @if($isEdit && $indikator->prevIndikator)
                                            <div class="form-control-plaintext">
                                                <a href="{{ route('pemutu.indikator.edit', $indikator->prevIndikator->encrypted_indikator_id) }}" target="_blank" class="badge bg-purple-lt border border-purple px-2 py-1 text-decoration-none fs-6" title="Buka Indikator Tahun Sebelumnya">
                                                    <i class="ti ti-external-link me-1"></i> {{ $indikator->prevIndikator->no_indikator ?: 'Tahun Lalu' }}
                                                </a>
                                            </div>
                                            <input type="hidden" name="origin_from" value="{{ $indikator->origin_from }}">
                                        @else
                                            <input type="text" class="form-control" name="origin_from" value="{{ old('origin_from', $indikator->origin_from) }}" placeholder="-">
                                        @endif
                                    </div>
                                </div>

                                <x-tabler.form-textarea name="indikator" label="Nama Indikator" rows="2" placeholder="Masukkan nama indikator..." value="{{ old('indikator', $indikator->indikator) }}" />

                                @if(!empty($prevData))
                                    @php
                                        // Take example from one of the units (typically uniform) or summarize
                                        $firstPrev = reset($prevData);
                                    @endphp
                                    <div class="alert alert-info border border-info-subtle bg-info-lt shadow-sm mb-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar avatar-sm bg-info text-white">
                                                <i class="ti ti-history"></i>
                                            </div>
                                            <div class="flex-fill">
                                                <div class="fw-bold mb-1">Referensi Periode Sebelumnya ({{ $indikator->prevIndikator->periode ?? 'Lalu' }})</div>
                                                <div class="row g-3 small">
                                                    <div class="col-md-4">
                                                        <span class="text-muted d-block">Target Lama:</span>
                                                        <span class="fw-semibold">{{ $firstPrev['target'] ?? '-' }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <span class="text-muted d-block">Status Pengendalian:</span>
                                                        <span class="status status-{{ $firstPrev['status'] === 'sesuai' ? 'success' : ($firstPrev['status'] === 'penyesuaian' ? 'warning' : 'danger') }} py-0">
                                                            {{ ucwords($firstPrev['status'] ?? 'N/A') }}
                                                        </span>
                                                    </div>
                                                    @if($firstPrev['analisis_atsn'])
                                                    <div class="col-md-4">
                                                        <span class="text-muted d-block">Analisis Atasan:</span>
                                                        <span class="fst-italic text-truncate d-inline-block mw-100" title="{{ $firstPrev['analisis_atsn'] }}">
                                                            {{ \Str::limit($firstPrev['analisis_atsn'], 100) }}
                                                        </span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <x-tabler.form-select name="doksub_ids[]" label="Dokumen Standar" type="select2" class="mb-3" data-placeholder="Pilih dokumen standar...">
                                    <option value="">-- Pilih Dokumen Standar --</option>
                                    @foreach($standardOptions as $so)
                                        @php
                                            $isSelected = (isset($selectedDokSubs) && collect($selectedDokSubs)->pluck('doksub_id')->contains($so->doksub_id)) || 
                                                          (is_array(old('doksub_ids')) && in_array($so->encrypted_doksub_id, old('doksub_ids'))) ||
                                                          (old('doksub_ids') == $so->encrypted_doksub_id);
                                        @endphp
                                        <option value="{{ $so->encrypted_doksub_id }}" {{ $isSelected ? 'selected' : '' }}>
                                            [{{ $so->dokumen?->periode ?? 'STANDAR' }}] {{ $so->dokumen?->judul ?? '-' }} &raquo; {{ $so->judul }}
                                        </option>
                                    @endforeach
                                </x-tabler.form-select>

                                @if($isStandar)
                                <x-tabler.form-select name="renstra_poin_id" type="select2" label="Mapping ke Poin Renstra" class="mb-3" data-placeholder="Pilih poin Renstra...">
                                    <option value="">-- Tidak Dipetakan --</option>
                                    @foreach($renstraOptions as $ro)
                                        <option value="{{ $ro->encrypted_doksub_id }}" {{ old('renstra_poin_id', $indikator->encrypted_renstra_poin_id) == $ro->encrypted_doksub_id ? 'selected' : '' }}>
                                            [{{ $ro->dokumen?->periode ?? 'RENSTRA' }}] {{ $ro->judul }} {{ $ro->kode ? '('.$ro->kode.')' : '' }}
                                        </option>
                                    @endforeach
                                </x-tabler.form-select>
                                @endif

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <x-tabler.form-select name="labels" id="label-selector" type="select2" label="Label" multiple="true" data-placeholder="Pilih Label (bisa pilih banyak)...">
                                            @php
                                                $selectedLabelIds = $isEdit ? collect($indikator->labels)->pluck('label_id')->toArray() : [];
                                                $oldLabels = old('labels', []);
                                                $oldLabelIds = array_filter(array_map('decryptIdIfEncrypted', is_array($oldLabels) ? $oldLabels : [$oldLabels]));
                                                $mergedSelectedIds = array_unique(array_merge($selectedLabelIds, $oldLabelIds));
                                            @endphp
                                            @foreach($labelParents as $parent)
                                                @if($parent->children->count() > 0)
                                                    <optgroup label="{{ $parent->name }}">
                                                        @foreach($parent->children as $child)
                                                            <option value="{{ $child->encrypted_label_id }}" {{ in_array($child->label_id, $mergedSelectedIds) ? 'selected' : '' }}>
                                                                {{ $parent->name }} - {{ $child->name }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @else
                                                    <option value="{{ $parent->encrypted_label_id }}" {{ in_array($parent->label_id, $mergedSelectedIds) ? 'selected' : '' }}>
                                                        {{ $parent->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </x-tabler.form-select>
                                    </div>
                                </div>

                                <x-tabler.form-textarea id="keterangan" name="keterangan" label="Definisi / Keterangan" height="300" :value="old('keterangan', $indikator->keterangan)" />
                            </div>
                            <!-- END TAB: INFORMASI UMUM -->

                            <!-- TAB: PENILAIAN SKALA -->
                            <div class="tab-pane" id="tab-penilaian-skala">
                                @php
                                    $hasSkala = false;
                                    if ($indikator->skala && is_array($indikator->skala)) {
                                        foreach($indikator->skala as $val) {
                                            if (!empty(trim(strip_tags($val)))) {
                                                $hasSkala = true;
                                                break;
                                            }
                                        }
                                    } elseif (old('enable_skala')) {
                                        $hasSkala = true;
                                    }
                                @endphp

                                <div class="mb-3">
                                    <label class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="enable_skala" name="enable_skala" value="1" {{ $hasSkala ? 'checked' : '' }}>
                                        <span class="form-check-label fw-bold text-primary">Aktifkan Penilaian Skala Khusus</span>
                                    </label>
                                    <div class="text-muted small mt-1">Definisikan deskripsi untuk setiap level skala penilaian (0 – 4). Centang opsi ini jika Indikator ini memerlukan kriteria kustom. Skala ini akan otomatis ditampilkan kepada auditee saat mengisi Evaluasi Diri. JIKA TIDAK DICENTANG, nilai skala akan dikosongkan.</div>
                                </div>

                                <div id="skala-container" style="{{ $hasSkala ? '' : 'display: none;' }}">
                                    <div class="row row-cols-1 row-cols-md-2 g-3">
                                    @foreach([0,1,2,3,4] as $level)
                                    <div class="col">
                                        <div class="p-3 border rounded-3 bg-light-lt h-100 shadow-sm border-blue-lt">
                                            <div class="d-flex align-items-center mb-2 gap-2">
                                                <div class="avatar avatar-sm bg-blue-lt fw-bold border border-blue">
                                                    {{ $level }}
                                                </div>
                                                <div class="fw-bold text-primary">Level Skala {{ $level }}</div>
                                            </div>
                                            <x-tabler.form-textarea
                                                id="skala-{{ $level }}"
                                                name="skala[{{ $level }}]"
                                                label=""
                                                height="180"
                                                :value="old('skala.' . $level, ($indikator->skala[$level] ?? ($indikator->skala ? ($indikator->skala[$level] ?? '') : '')))"
                                            />
                                        </div>
                                    </div>
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- END TAB: PENILAIAN SKALA -->
                        </div>
                    </x-tabler.card-body>
                </x-tabler.card>
            </div>

            <!-- ASSIGNMENTS (KANAN) -->
            <div class="col-lg-5">
                @if($isPerforma)
                <!-- CARD: KPI ASSIGN -->
                <x-tabler.card id="card-performa">
                    <x-tabler.card-header title="<i class='ti ti-users me-2'></i>Penugasan KPI (Performa)" />
                    <x-tabler.card-body>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <x-tabler.form-select name="parent_id" id="parent-id-selector" label="Indikator Standar Terkait (Induk)" type="select2" :required="true" required="required">
                                    <option value="">-- Pilih Indikator Standar --</option>
                                    @foreach($parents as $p)
                                        <option value="{{ $p->encrypted_indikator_id }}" {{ $p->indikator_id == $indikator->parent_id ? 'selected' : '' }}>
                                            [{{ $p->no_indikator }}] {{ \Str::limit($p->indikator, 150) }}
                                        </option>
                                    @endforeach
                                </x-tabler.form-select>
                                <div class="font-small text-muted">Indikator Performa HARUS merujuk pada satu Indikator Standar.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label required">Daftar Sasaran Kinerja Pegawai</label>
                                <div class="table-responsive border rounded mb-3">
                                    <table class="table table-vcenter border-bottom" id="kpi-repeater-table">
                                        <thead>
                                            <tr>
                                                <th width="50%">Penanggung Jawab <span class="text-danger">*</span></th>
                                                <th width="45%">Target & Satuan</th>
                                                <th width="5%" class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="kpi-repeater-body">
                                            @php
                                                $pegawaiAssigned = $isEdit ? $indikator->pegawai : collect([]);
                                            @endphp
                                            @if($pegawaiAssigned->count() > 0)
                                                @foreach($pegawaiAssigned as $index => $kpi)
                                                    <tr class="kpi-row">
                                                        <td>
                                                            <input type="hidden" name="kpi_assign[{{$index}}][selected]" value="1">

                                                        </td>
                                                        <td>
                                                            <x-tabler.form-input class="mb-2" name="kpi_assign[{{$index}}][target_value]" placeholder="Nilai Target" value="{{ $kpi->target_value }}" />
                                                            <x-tabler.form-input name="kpi_assign[{{$index}}][unit_ukuran]" placeholder="%, org, dll (Satuan)" value="{{ $kpi->unit_ukuran ?? '' }}" />
                                                        </td>
                                                        <td class="text-center">
                                                            <x-tabler.button type="button" class="btn-danger btn-sm btn-remove-row" title="Hapus" iconOnly="true" icon="ti ti-trash" />
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <x-tabler.button type="create" id="btn-add-kpi" class="btn-outline-danger btn-sm w-100" text="Tambah Sasaran" />
                            </div>
                        </div>
                    </x-tabler.card-body>
                </x-tabler.card>
                @endif

                @if($isStandar)
                <!-- CARD: TARGET & UNIT -->
                <x-tabler.card id="card-target">
                    <x-tabler.card-header title="<i class='ti ti-target me-2'></i>Target & Unit Kerja" />
                    <x-tabler.card-body>
                        <div class="d-flex align-items-center mb-2 gap-2">
                            <div class="input-icon flex-fill">
                                <span class="input-icon-addon"><i class="ti ti-search"></i></span>
                                <input type="text" id="unit-search" class="form-control" placeholder="Cari unit atau kode...">
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-primary active btn-unit-filter" data-filter="all">Semua</button>
                                <button type="button" class="btn btn-outline-primary btn-unit-filter" data-filter="selected">Terpilih</button>
                            </div>
                        </div>

                        <div class="table-responsive border rounded" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-vcenter card-table table-striped" id="unit-selection-table">
                                <thead>
                                    <tr>
                                        <th width="50%">Unit</th>
                                        <th width="50%">Target Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        if(!function_exists('renderUnitRow')){
                                            function renderUnitRow($unit, $level = 0, $assignedMap, &$visited = []) {
                                                if (isset($visited[$unit->orgunit_id])) return; // Circuit breaker for circular dependencies
                                                $visited[$unit->orgunit_id] = true;

                                                $padding = $level * 20;
                                                $isBold = $level < 2 ? 'fw-bold' : '';
                                                $bg = '';

                                                $isChecked = $assignedMap->has($unit->orgunit_id);
                                                $targetVal = $isChecked ? $assignedMap->get($unit->orgunit_id)->pivot->target : '';
                                                $isDisabled = !$isChecked ? 'disabled' : '';

                                                    $rowClasses = "unit-row " . ($isChecked ? "is-assigned" : "");
                                                    $rowAttributes = "data-title='".strtolower($unit->name)."' data-code='".strtolower($unit->code)."'";
                                                    echo "<tr class='$rowClasses' $rowAttributes>";
                                                echo '<td>';
                                                echo '<div style="padding-left: '.$padding.'px">';
                                                echo '<label class="form-check form-check-inline mb-0">';
                                                echo '<input class="form-check-input unit-checkbox" type="checkbox" name="assignments['.$unit->encrypted_org_unit_id.'][selected]" value="1" data-id="'.$unit->encrypted_org_unit_id.'" '.($isChecked ? 'checked' : '').'>';
                                                echo '<span class="form-check-label '.$isBold.'">'.$unit->name.'</span>';
                                                echo '</label>';
                                                echo '</div>';
                                                echo '</td>';
                                                echo '<td>';
                                                echo '<input type="text" class="form-control form-control-sm" name="assignments['.$unit->encrypted_org_unit_id.'][target]" id="target-'.$unit->encrypted_org_unit_id.'" placeholder="Target..." value="'.$targetVal.'" '.$isDisabled.'>';
                                                echo '</td>';
                                                echo '</tr>';

                                                if ($unit->children && $unit->children->count()) {
                                                    foreach($unit->children as $child) {
                                                        renderUnitRow($child, $level + 1, $assignedMap, $visited);
                                                    }
                                                }
                                            }
                                        }

                                        $visited = [];
                                    @endphp

                                    @foreach($orgUnits as $rootUnit)
                                        {{ renderUnitRow($rootUnit, 0, $assignedMap, $visited) }}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-tabler.card-body>
                </x-tabler.card>
                @endif


            </div>
        </div>
    </form>
    @endsection
    @push('scripts')
    <script type="module">


    if (window.loadHugeRTE) {
        window.loadHugeRTE('#keterangan', { 
            height: 200,
            menubar: false,
            statusbar: false,
            plugins: 'lists',
            toolbar: 'bold italic | outdent indent | bullist numlist',
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
        window.loadHugeRTE('textarea.form-control[id^="skala-"]', { 
            height: 180,
            menubar: false,
            statusbar: false,
            plugins: 'lists',
            toolbar: 'bold italic | outdent indent | bullist numlist',
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    }

    // Toggle Skala Logic
    document.getElementById('enable_skala').addEventListener('change', function() {
        const container = document.getElementById('skala-container');
        if (this.checked) {
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
            // Clear content via HugeRTE API if initialized
            if (window.hugerte) {
                [0, 1, 2, 3, 4].forEach(level => {
                    const editor = window.hugerte.get('skala-' + level);
                    if (editor) {
                        editor.setContent('');
                    } else {
                        // fallback clear textarea value directly
                        const textarea = document.getElementById('skala-' + level);
                        if (textarea) textarea.value = '';
                    }
                });
            }
        }
    });
    </script>
    @endpush
