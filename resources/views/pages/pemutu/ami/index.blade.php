@extends('layouts.tabler.app')
@section('title', 'Audit Mutu Internal - Siklus ' . $siklus['tahun'])

@section('header')
<x-tabler.page-header title="Audit Mutu Internal (AMI) {{ $siklus['tahun'] }}" pretitle="Evaluasi">
    <x-slot:actions>
        <div class="btn-group p-1 bg-light rounded-pill shadow-sm" style="border: 1px solid #e6e8e9;">
            <a href="{{ route('pemutu.set-kelompok', 'akademik') }}" 
               class="btn {{ $activeKelompok === 'akademik' ? 'btn-white shadow-sm fw-bold border-0 active text-primary' : 'btn-ghost-secondary border-0 opacity-75' }} rounded-pill px-4 transition-all duration-200">
                <i class="ti ti-school me-2"></i>Akademik
            </a>
            <a href="{{ route('pemutu.set-kelompok', 'non_akademik') }}" 
               class="btn {{ $activeKelompok === 'non_akademik' ? 'btn-white shadow-sm fw-bold border-0 active text-primary' : 'btn-ghost-secondary border-0 opacity-75' }} rounded-pill px-4 transition-all duration-200">
                <i class="ti ti-building-community me-2"></i>Non Akademik
            </a>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    @php 
        $prevYear = $periode ? (int)$periode->periode - 1 : null;
    @endphp
    
    @if($periode)
        @php $jadwalTersedia = $periode->ami_awal && $periode->ami_akhir; @endphp
        
        <x-tabler.card>
            <x-tabler.card-header class="border-bottom-0 pt-4">
                <ul class="nav nav-pills card-header-pills" id="ami-tabs" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tab-ami" class="nav-link active" data-bs-toggle="tab" role="tab">
                            <i class="ti ti-shield-check me-2"></i>Audit Mutu Internal
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-te" class="nav-link" data-bs-toggle="tab" role="tab" tabindex="-1">
                            <i class="ti ti-search me-2"></i>Tinjauan Efektivitas ({{ $prevYear }})
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-rtp" class="nav-link" data-bs-toggle="tab" role="tab" tabindex="-1">
                            <i class="ti ti-pennant me-2"></i>Rencana Tindakan Perbaikan (RTP)
                        </a>
                    </li>
                </ul>
            </x-tabler.card-header>

            <div class="tab-content">
                {{-- SUB-TAB: AMI --}}
                <div class="tab-pane active show" id="tab-ami" role="tabpanel">
                    <x-tabler.card-body class="border-top">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-1">Periode {{ $periode->jenis_periode }} {{ $periode->periode }}</h3>
                                <div class="text-muted small mt-1">
                                    @php $periodeInfo = pemutuPeriodeStatus($periode->ami_awal, $periode->ami_akhir); @endphp
                                    @if($periode->ami_awal && $periode->ami_akhir)
                                        <i class="ti ti-calendar me-1"></i>
                                        Jadwal: {{ $periode->ami_awal->format('d M Y') }} s.d. {{ $periode->ami_akhir->format('d M Y') }}
                                    @endif
                                    <span class="badge bg-{{ $periodeInfo['color'] }}-lt ms-2">{{ $periodeInfo['status_text'] }}</span>
                                </div>
                            </div>
                            <div class="col-auto d-flex gap-2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-file-export me-1"></i> Export
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="#" class="dropdown-item export-btn" data-export-type="ptk" data-periode="{{ $periode->encrypted_periodespmi_id }}" data-type="{{ $activeKelompok }}">
                                                <i class="ti ti-file-text me-2"></i>PTK (DOCX)
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="dropdown-item export-btn" data-export-type="temuan-audit" data-periode="{{ $periode->encrypted_periodespmi_id }}" data-type="{{ $activeKelompok }}">
                                                <i class="ti ti-file-x me-2"></i>Temuan Audit - KTS (XLSX)
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="dropdown-item export-btn" data-export-type="temuan-positif" data-periode="{{ $periode->encrypted_periodespmi_id }}" data-type="{{ $activeKelompok }}">
                                                <i class="ti ti-file-check me-2"></i>Temuan Positif (XLSX)
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <x-tabler.datatable-page-length dataTableId="table-ami" />
                                <x-tabler.datatable-filter dataTableId="table-ami" type="button" target="#table-ami-filter-area" />
                                <x-tabler.datatable-search dataTableId="table-ami" />
                            </div>
                        </div>
                    </x-tabler.card-body>
                    <div class="collapse" id="table-ami-filter-area">
                        <x-tabler.datatable-filter dataTableId="table-ami" type="bare">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <x-tabler.form-select name="orgunit_id" id="orgunit_id" label="Unit / Area" placeholder="">
                                        <option value="all">Semua Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ encryptId($unit->orgunit_id) }}">{!! $unit->indented_name !!}</option>
                                        @endforeach
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-3">
                                    <x-tabler.form-select name="dok_id" id="dok_id" label="Standar / Dokumen" placeholder="">
                                        <option value="all">Semua Standar</option>
                                        @foreach($rootDoks as $dok)
                                            <option value="{{ $dok->encrypted_dok_id }}">{{ $dok->judul }}</option>
                                        @endforeach
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-3">
                                    <x-tabler.form-select name="ami_hasil_akhir" id="ami_hasil_akhir" label="Hasil AMI" placeholder="">
                                        <option value="all">Semua Hasil</option>
                                        <option value="empty">Belum Dinilai</option>
                                        <option value="0">KTS</option>
                                        <option value="1">Terpenuhi</option>
                                        <option value="2">Terlampaui</option>
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-3">
                                    <x-tabler.form-select name="ed_status" id="ed_status" label="Status Evaluasi" placeholder="">
                                        <option value="all">Semua Status</option>
                                        <option value="filled">Sudah Isi</option>
                                        <option value="empty">Belum Isi</option>
                                    </x-tabler.form-select>
                                </div>
                            </div>
                        </x-tabler.datatable-filter>
                    </div>
                    <div class="table-responsive border-top">
                        <x-tabler.datatable
                            id="table-ami"
                            route="{{ route('pemutu.ami.data', $periode->encrypted_periodespmi_id) }}"
                            :columns="[
                                ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                ['data' => 'indikator_full', 'name' => 'indikator_full', 'title' => 'Indikator'],
                                ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '5%', 'class' => 'text-center'],
                                ['data' => 'ed_capaian', 'name' => 'ed_capaian', 'title' => 'Capaian', 'width' => '5%', 'class' => 'text-center', 'orderable' => false],
                                ['data' => 'ed_analisis', 'name' => 'ed_analisis', 'title' => 'Analisis Capaian', 'class' => 'text-left', 'orderable' => false],
                                ['data' => 'ami_hasil', 'name' => 'ami_hasil', 'title' => 'Hasil AMI',  'class' => 'text-left', 'orderable' => false],
                                ['data' => 'auditor_recom', 'name' => 'auditor_recom', 'title' => 'Rekomendasi Auditor','class' => 'text-left', 'orderable' => false, 'searchable' => false],
                                ['data' => 'action', 'name' => 'action', 'title' => 'AMI', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                            ]"
                        />
                    </div>
                </div>

                {{-- SUB-TAB: TE --}}
                <div class="tab-pane" id="tab-te" role="tabpanel">
                    <x-tabler.card-body class="border-top">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-1">Tinjauan Efektivitas (Hasil AMI {{ $prevYear }})</h3>
                                <p class="text-muted mb-0 small">Daftar temuan KTS dari periode <span class="badge bg-purple-lt text-purple small">AMI {{ $prevYear }}</span> yang harus ditinjau perbaikannya.</p>
                            </div>
                            <div class="col-auto d-flex gap-2">
                                <x-tabler.datatable-page-length dataTableId="table-te" />
                                <x-tabler.datatable-filter dataTableId="table-te" type="button" target="#table-te-filter-area" />
                                <x-tabler.datatable-search dataTableId="table-te" />
                            </div>
                        </div>
                    </x-tabler.card-body>
                    <div class="collapse" id="table-te-filter-area">
                        <x-tabler.datatable-filter dataTableId="table-te" type="bare">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <x-tabler.form-select name="unit_id" id="unit_id_te" label="Unit / Area" placeholder="">
                                        <option value="all">Semua Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ encryptId($unit->orgunit_id) }}">{!! $unit->indented_name !!}</option>
                                        @endforeach
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-select name="dok_id" id="dok_id_te" label="Standar / Dokumen" placeholder="">
                                        <option value="all">Semua Standar</option>
                                        @foreach($rootDoks as $dok)
                                            <option value="{{ $dok->encrypted_dok_id }}">{{ $dok->judul }}</option>
                                        @endforeach
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-select name="te_status" id="te_status" label="Status Tinjauan" placeholder="">
                                        <option value="all">Semua Status</option>
                                        <option value="filled">Sudah Ditinjau</option>
                                        <option value="empty">Belum Ditinjau</option>
                                    </x-tabler.form-select>
                                </div>
                            </div>
                        </x-tabler.datatable-filter>
                    </div>
                    <div class="table-responsive border-top">
                        <x-tabler.datatable
                            id="table-te"
                            route="{{ route('pemutu.ami.te-data', $periode->encrypted_periodespmi_id) }}"
                            :columns="[
                                ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                ['data' => 'indikator_full', 'name' => 'indikator_full', 'title' => 'Indikator'],
                                ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%', 'class' => 'text-left'],
                                ['data' => 'rtp', 'name' => 'rtp', 'title' => 'Rencana (RTP)', 'width' => '15%'],
                                ['data' => 'ptp', 'name' => 'ptp', 'title' => 'Pelaksanaan (PTP)', 'width' => '15%'],
                                ['data' => 'te', 'name' => 'te', 'title' => 'Tinjauan (TE)', 'width' => '15%'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                            ]"
                        />
                    </div>
                </div>

                {{-- SUB-TAB: RTP ONLY (Findings) --}}
                <div class="tab-pane" id="tab-rtp" role="tabpanel">
                    <x-tabler.card-body class="border-top">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-1">Rencana Tindakan Perbaikan (RTP)</h3>
                                <p class="text-muted mb-0 small">Indikator dengan temuan Audit (KTS) yang memerlukan rencana perbaikan.</p>
                            </div>
                            <div class="col-auto d-flex gap-2">
                                <x-tabler.datatable-page-length dataTableId="table-rtp-only" />
                                <x-tabler.datatable-filter dataTableId="table-rtp-only" type="button" target="#table-rtp-only-filter-area" />
                                <x-tabler.datatable-search dataTableId="table-rtp-only" />
                            </div>
                        </div>
                    </x-tabler.card-body>
                    <div class="collapse" id="table-rtp-only-filter-area">
                        <x-tabler.datatable-filter dataTableId="table-rtp-only" type="bare">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <x-tabler.form-select name="unit_id" id="unit_id_rtp" label="Unit / Area" placeholder="">
                                        <option value="all">Semua Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ encryptId($unit->orgunit_id) }}">{!! $unit->indented_name !!}</option>
                                        @endforeach
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-select name="dok_id" id="dok_id_rtp" label="Standar / Dokumen" placeholder="">
                                        <option value="all">Semua Standar</option>
                                        @foreach($rootDoks as $dok)
                                            <option value="{{ $dok->encrypted_dok_id }}">{{ $dok->judul }}</option>
                                        @endforeach
                                    </x-tabler.form-select>
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-select name="rtp_status" id="rtp_status" label="Status RTP" placeholder="">
                                        <option value="all">Semua Status</option>
                                        <option value="filled">Sudah Mengisi</option>
                                        <option value="empty">Belum Mengisi</option>
                                    </x-tabler.form-select>
                                </div>
                            </div>
                        </x-tabler.datatable-filter>
                    </div>
                    <div class="table-responsive border-top">
                        <x-tabler.datatable
                            id="table-rtp-only"
                            route="{{ route('pemutu.ami.data', [$periode->encrypted_periodespmi_id, 'ami_hasil_akhir' => 0]) }}"
                            :columns="[
                                ['data' => 'no', 'name' => 'no', 'title' => '#', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                                ['data' => 'indikator_full', 'name' => 'indikator_full', 'title' => 'Indikator'],
                                ['data' => 'auditor_recom', 'name' => 'auditor_recom', 'title' => 'Rekomendasi Auditor', 'width' => '15%'],
                                ['data' => 'target', 'name' => 'target', 'title' => 'Target', 'width' => '10%', 'class' => 'text-left'],
                                ['data' => 'rtp_isi', 'name' => 'rtp_isi', 'title' => 'Rencana Perbaikan', 'width' => '20%', 'class' => 'text-left', 'orderable' => false, 'searchable' => false],
                                ['data' => 'rtp_tgl', 'name' => 'rtp_tgl', 'title' => 'Tgl Pelaksanaan', 'width' => '10%', 'class' => 'text-center'],
                                ['data' => 'action_rtp', 'name' => 'action_rtp', 'title' => 'Aksi', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                            ]"
                        />
                    </div>
                </div>
            </div>
        </x-tabler.card>
    @else
        <x-tabler.card>
            <x-tabler.card-body class="py-5 text-center">
                <x-tabler.empty-state 
                    title="Periode Belum Tersedia" 
                    text="Data periode {{ str_replace('_', ' ', $activeKelompok) }} untuk tahun {{ $siklus['tahun'] }} belum dibuat."
                    icon="ti ti-calendar-off" 
                />
            </x-tabler.card-body>
        </x-tabler.card>
    @endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle export buttons with filter parameters
    document.querySelectorAll('.export-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const exportType = this.dataset.exportType;
            const periodeId = this.dataset.periode;
            
            // Get current filter values
            const unitIdEl = document.querySelector('#table-ami-filter-area [name="unit_id"]');
            const dokIdEl = document.querySelector('#table-ami-filter-area [name="dok_id"]');
            const amiHasilEl = document.querySelector('#table-ami-filter-area [name="ami_hasil_akhir"]');
            const edStatusEl = document.querySelector('#table-ami-filter-area [name="ed_status"]');
            
            // Build URL with query parameters
            let url = '';
            if (exportType === 'ptk') {
                url = `/pemutu/ami/${periodeId}/export-ptk`;
            } else if (exportType === 'temuan-audit') {
                url = `/pemutu/ami/${periodeId}/export-temuan-audit`;
            } else if (exportType === 'temuan-positif') {
                url = `/pemutu/ami/${periodeId}/export-temuan-positif`;
            }
            
            const params = new URLSearchParams();
            if (unitIdEl && unitIdEl.value) params.append('unit_id', unitIdEl.value);
            if (dokIdEl && dokIdEl.value && dokIdEl.value !== 'all') params.append('dok_id', dokIdEl.value);
            
            if (exportType === 'ptk') {
                params.append('ami_hasil_akhir', '0');
            } else if (amiHasilEl && amiHasilEl.value && amiHasilEl.value !== 'all') {
                params.append('ami_hasil_akhir', amiHasilEl.value);
            }
            
            if (edStatusEl && edStatusEl.value && edStatusEl.value !== 'all') params.append('ed_status', edStatusEl.value);
            
            // Show loading indicator
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="ti ti-loader ti-spin me-1"></i>Preparing...';
            this.classList.add('disabled');
            
            // Navigate to export URL
            window.location.href = url + (params.toString() ? '?' + params.toString() : '');
            
            // Re-enable button after 2 seconds
            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.remove('disabled');
            }, 2000);
        });
    });
});
</script>
@endpush

