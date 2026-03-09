@php
    $isEdit = $indikator->exists;
    $title = $isEdit ? 'Edit Indikator' : 'Tambah Indikator Baru';
    $route = $isEdit ? route('pemutu.indikator.update', $indikator) : route('pemutu.indikator.store');
    $method = $isEdit ? 'PUT' : 'POST';

    $assignedMap = $isEdit ? $indikator->orgUnits->keyBy('orgunit_id') : collect([]);
@endphp


    @extends('layouts.tabler.app')

    @section('header')
    <x-tabler.page-header :title="$title" pretitle="SPMI / Indikator">
        <x-slot:actions>
            <x-tabler.button href="javascript:history.back()" type="back" />
        </x-slot:actions>
    </x-tabler.page-header>
    @endsection

    @section('content')
    <form action="{{ $route }}" method="POST" class="ajax-form" novalidate>
        @csrf
        @if($isEdit) @method('PUT') @endif
        <input type="hidden" name="redirect_to" value="{{ old('redirect_to', request('redirect_to', url()->previous())) }}">

        @php
            $type = old('type', $indikator->type ?? request('type', 'standar'));
            $isRenop = $type === 'renop';
            $isStandar = $type === 'standar';
            $isPerforma = $type === 'performa';
        @endphp

        <div class="row row-cards">
            <!-- INFORMASI UMUM & SKALA (KIRI) -->
            <div class="col-lg-7">
                <x-tabler.card>
                    <x-tabler.card-header class="border-bottom-0">
                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                            <li class="nav-item">
                                <a href="#tab-informasi-umum" class="nav-link active" data-bs-toggle="tab">
                                    <i class="ti ti-info-circle me-2"></i>Informasi Umum
                                </a>
                            </li>
                            @if(!$isRenop)
                            <li class="nav-item tab-link-skala-container" id="tab-link-skala-container">
                                <a href="#tab-penilaian-skala" class="nav-link" data-bs-toggle="tab" id="tab-link-skala">
                                    <i class="ti ti-list-numbers me-2"></i>Penilaian Skala
                                </a>
                            </li>
                            @endif
                        </ul>
                    </x-tabler.card-header>
                    
                    <x-tabler.card-body>
                        <div class="tab-content">
                            <!-- TAB: INFORMASI UMUM -->
                            <div class="tab-pane active show" id="tab-informasi-umum">
                                <input type="hidden" name="type" value="{{ $type }}">
                                
                                <input type="hidden" name="type" value="{{ $type }}">
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label text-muted small">Tipe Indikator</label>
                                        <div class="form-control-plaintext fw-bold text-primary">
                                            <i class="ti ti-tag me-1"></i> 
                                            @if($isRenop) Indikator Renop 
                                            @elseif($isStandar) Indikator Standar 
                                            @elseif($isPerforma) Indikator Performa 
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label text-muted small">No Indikator</label>
                                        <div class="form-control-plaintext">
                                            @if($isEdit)
                                                <span class="badge bg-blue-lt">{{ $indikator->no_indikator }}</span>
                                            @else
                                                <span class="text-muted"><i class="ti ti-wand me-1"></i> Auto</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <x-tabler.form-select
                                            name="kelompok_indikator"
                                            label="Kelompok Indikator"
                                            :required="true"
                                            :options="[
                                                'Akademik' => 'Akademik',
                                                'Non Akademik' => 'Non Akademik'
                                            ]"
                                            :selected="old('kelompok_indikator', $indikator->kelompok_indikator)"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <x-tabler.form-textarea name="indikator" label="Nama Indikator" rows="2" placeholder="Masukkan nama indikator..." value="{{ old('indikator', $indikator->indikator) }}" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <x-tabler.form-select name="doksub_ids" label="Dokumen Terkait" class="select2-ajax" multiple="true" data-placeholder="Cari & pilih dokumen penjaminan mutu..." data-ajax-url="{{ route('pemutu.indikator.search-doksub') }}">
                                            @if(isset($selectedDokSubs))
                                                @foreach($selectedDokSubs as $ds)
                                                    @if($ds instanceof \App\Models\Pemutu\DokSub)
                                                        <option value="{{ $ds->encrypted_doksub_id }}" selected>
                                                            [{{ strtoupper($ds->dokumen?->jenis ?? 'DOC') }}] {{ $ds->dokumen?->judul ?? '-' }} &raquo; {{ $ds->judul }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </x-tabler.form-select>
                                    </div>
                                </div>

                                <div class="row">
                                    @foreach($labelTypes as $type)
                                    <div class="col-md-4 mb-1">
                                        <x-tabler.form-select name="labels" id="label-{{ $type->labeltype_id }}" type="select2" label="{{ $type->name }}" multiple="true" data-placeholder="Pilih {{ $type->name }}...">
                                            @php
                                                $selectedLabelIds = $isEdit ? $indikator->labels->where('type_id', $type->labeltype_id)->pluck('label_id')->toArray() : [];
                                            @endphp
                                            @foreach($type->labels as $label)
                                                <option value="{{ $label->encrypted_label_id }}" {{ in_array($label->label_id, $selectedLabelIds) ? 'selected' : '' }}>{{ $label->name }}</option>
                                            @endforeach
                                        </x-tabler.form-select>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <x-tabler.form-textarea id="keterangan" name="keterangan" label="Definisi / Keterangan" height="300" :value="old('keterangan', $indikator->keterangan)" />
                                    </div>
                                </div>
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
                                    <div class="row g-2">
                                    @foreach([0,1,2,3,4] as $level)
                                    <div class="col-12 mb-2">
                                        <x-tabler.card class="card-sm border-blue-lt mb-0">
                                            <x-tabler.card-header class="bg-blue-lt py-2" title="Level Skala {{ $level }}" />
                                            <x-tabler.card-body class="p-2">
                                                <x-tabler.form-textarea
                                                    id="skala-{{ $level }}"
                                                    name="skala[{{ $level }}]"
                                                    label=""
                                                    height="180"
                                                    :value="old('skala.' . $level, ($indikator->skala[$level] ?? ($indikator->skala ? ($indikator->skala[$level] ?? '') : '')))"
                                                />
                                            </x-tabler.card-body>
                                        </x-tabler.card>
                                    </div>
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- END TAB: PENILAIAN SKALA -->
                        </div>
                    </div>
                </div>
            </div>{{-- end col-lg-7 --}}

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
                                <div class="form-hint">Indikator Performa HARUS merujuk pada satu Indikator Standar.</div>
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
                                                            <select class="form-select select2-kpi" name="kpi_assign[{{$index}}][pegawai_id]" required data-placeholder="Pilih pegawai...">
                                                                <option value="">Pilih pegawai...</option>
                                                                @foreach($pegawais as $p)
                                                                    <option value="{{ $p->encrypted_pegawai_id }}" {{ $p->pegawai_id == $kpi->pegawai_id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                                                @endforeach
                                                            </select>
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
                    </div>
                </div>
                @endif

                @if($isStandar)
                <!-- CARD: TARGET & UNIT -->
                <x-tabler.card id="card-target">
                    <x-tabler.card-header title="<i class='ti ti-target me-2'></i>Target & Unit Kerja" />
                    <x-tabler.card-body>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label required">Unit Kerja Penanggung Jawab & Target</label>
                                
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
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Submit Button moved to bottom of right col or as a separate card -->
                <x-tabler.card class="mt-3">
                    <x-tabler.card-body>
                        <x-tabler.button type="submit" :text="$isEdit ? 'Update Indikator' : 'Simpan Indikator'" />
                    </x-tabler.card-body>
                </x-tabler.card>
            </div>
        </div>
    </form>
    @endsection
    @push('scripts')
    <script type="module">
    // Logic form ada di resources/js/helpers/pemutu-indikator.js
    window.initPemutuIndikatorForm({
        kpiInitialIndex: {{ isset($indikator) && $indikator->pegawai ? $indikator->pegawai->count() : 0 }},
        pegawaiOptionsHtml: `@foreach($pegawais as $p)<option value="{{ $p->encrypted_pegawai_id }}">{{ $p->nama }}</option>@endforeach`
    });

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
