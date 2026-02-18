@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Master Data HR" pretitle="HR & Kepegawaian">
    <x-slot:actions>
        @if($activeTab == 'status-pegawai')
            <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Status Pegawai" class="ajax-modal-btn" data-url="{{ route('hr.status-pegawai.create') }}" data-modal-title="Tambah Status Pegawai" />
        @elseif($activeTab == 'status-aktifitas')
            <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Status Aktifitas" class="ajax-modal-btn" data-url="{{ route('hr.status-aktifitas.create') }}" data-modal-title="Tambah Status Aktifitas" />
        @elseif($activeTab == 'jabatan-fungsional')
            <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Jabatan Fungsional" class="ajax-modal-btn" data-url="{{ route('hr.jabatan-fungsional.create') }}" data-modal-title="Tambah Jabatan Fungsional" />
        @elseif($activeTab == 'jenis-izin')
            <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Jenis Izin" class="ajax-modal-btn" data-url="{{ route('hr.jenis-izin.create') }}" data-modal-title="Tambah Jenis Izin" />
        @elseif($activeTab == 'jenis-indisipliner')
            <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Jenis Indisipliner" class="ajax-modal-btn" data-url="{{ route('hr.jenis-indisipliner.create') }}" data-modal-title="Tambah Jenis Indisipliner" />
        @elseif($activeTab == 'jenis-shift')
            <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Jenis Shift" class="ajax-modal-btn" data-url="{{ route('hr.jenis-shift.create') }}" data-modal-title="Tambah Jenis Shift" />
        @endif
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="{{ route('hr.status-pegawai.index', ['tab' => 'status-pegawai']) }}" class="nav-link {{ $activeTab == 'status-pegawai' ? 'active' : '' }}">
                            <i class="ti ti-user-check me-1"></i> Status Pegawai
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('hr.status-pegawai.index', ['tab' => 'status-aktifitas']) }}" class="nav-link {{ $activeTab == 'status-aktifitas' ? 'active' : '' }}">
                            <i class="ti ti-activity me-1"></i> Status Aktifitas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('hr.status-pegawai.index', ['tab' => 'jabatan-fungsional']) }}" class="nav-link {{ $activeTab == 'jabatan-fungsional' ? 'active' : '' }}">
                            <i class="ti ti-medal me-1"></i> Jabatan Fungsional
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('hr.status-pegawai.index', ['tab' => 'jenis-izin']) }}" class="nav-link {{ $activeTab == 'jenis-izin' ? 'active' : '' }}">
                            <i class="ti ti-file-text me-1"></i> Jenis Izin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('hr.status-pegawai.index', ['tab' => 'jenis-indisipliner']) }}" class="nav-link {{ $activeTab == 'jenis-indisipliner' ? 'active' : '' }}">
                            <i class="ti ti-alert-triangle me-1"></i> Jenis Indisipliner
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('hr.status-pegawai.index', ['tab' => 'jenis-shift']) }}" class="nav-link {{ $activeTab == 'jenis-shift' ? 'active' : '' }}">
                            <i class="ti ti-clock me-1"></i> Jenis Shift
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    {{-- Status Pegawai --}}
                    @if($activeTab == 'status-pegawai')
                    <div class="tab-pane active show" id="tabs-status-pegawai">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div><x-tabler.datatable-page-length :dataTableId="'status-pegawai-table'" /></div>
                            <div><x-tabler.datatable-search :dataTableId="'status-pegawai-table'" /></div>
                        </div>
                        <x-tabler.flash-message />
                        <x-tabler.datatable 
                            id="status-pegawai-table"
                            route="{{ route('hr.status-pegawai.data') }}"
                            :columns="[
                                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '50px'],
                                ['data' => 'kode_status', 'name' => 'kode_status', 'title' => 'Kode'],
                                ['data' => 'nama_status', 'name' => 'nama_status', 'title' => 'Nama Status'],
                                ['data' => 'organisasi', 'name' => 'organisasi', 'title' => 'Organisasi'],
                                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'className' => 'text-center', 'width' => '100px'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end', 'width' => '100px'],
                            ]"
                        />
                    </div>
                    @endif

                    {{-- Status Aktifitas --}}
                    @if($activeTab == 'status-aktifitas')
                    <div class="tab-pane active show" id="tabs-status-aktifitas">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div><x-tabler.datatable-page-length :dataTableId="'status-aktifitas-table'" /></div>
                            <div><x-tabler.datatable-search :dataTableId="'status-aktifitas-table'" /></div>
                        </div>
                        <x-tabler.flash-message />
                        <x-tabler.datatable 
                            id="status-aktifitas-table"
                            route="{{ route('hr.status-aktifitas.data') }}"
                            :columns="[
                                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '50px'],
                                ['data' => 'kode_status', 'name' => 'kode_status', 'title' => 'Kode'],
                                ['data' => 'nama_status', 'name' => 'nama_status', 'title' => 'Nama Status'],
                                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'className' => 'text-center', 'width' => '100px'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end', 'width' => '100px'],
                            ]"
                        />
                    </div>
                    @endif

                    {{-- Jabatan Fungsional --}}
                    @if($activeTab == 'jabatan-fungsional')
                    <div class="tab-pane active show" id="tabs-jabatan-fungsional">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div><x-tabler.datatable-page-length :dataTableId="'jabatan-fungsional-table'" /></div>
                            <div><x-tabler.datatable-search :dataTableId="'jabatan-fungsional-table'" /></div>
                        </div>
                        <x-tabler.flash-message />
                        <x-tabler.datatable 
                            id="jabatan-fungsional-table"
                            route="{{ route('hr.jabatan-fungsional.data') }}"
                            :columns="[
                                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '50px'],
                                ['data' => 'kode_jabatan', 'name' => 'kode_jabatan', 'title' => 'Kode'],
                                ['data' => 'jabfungsional', 'name' => 'jabfungsional', 'title' => 'Nama Jabatan'],
                                ['data' => 'tunjangan', 'name' => 'tunjangan', 'title' => 'Tunjangan', 'className' => 'text-end'],
                                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'className' => 'text-center', 'width' => '100px'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end', 'width' => '100px'],
                            ]"
                        />
                    </div>
                    @endif

                    {{-- Jenis Izin --}}
                    @if($activeTab == 'jenis-izin')
                    <div class="tab-pane active show" id="tabs-jenis-izin">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div><x-tabler.datatable-page-length :dataTableId="'table-jenis-izin'" /></div>
                            <div><x-tabler.datatable-search :dataTableId="'table-jenis-izin'" /></div>
                        </div>
                        <x-tabler.flash-message />
                        <x-tabler.datatable
                            id="table-jenis-izin"
                            route="{{ route('hr.jenis-izin.data') }}"
                            :columns="[
                                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%', 'class' => 'text-center'],
                                ['data' => 'nama', 'name' => 'nama', 'title' => 'Nama Jenis Izin'],
                                ['data' => 'kategori', 'name' => 'kategori', 'title' => 'Kategori'],
                                ['data' => 'max_hari', 'name' => 'max_hari', 'title' => 'Max Hari', 'class' => 'text-center'],
                                ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'class' => 'text-center'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '10%']
                            ]"
                        />
                    </div>
                    @endif

                    {{-- Jenis Indisipliner --}}
                    @if($activeTab == 'jenis-indisipliner')
                    <div class="tab-pane active show" id="tabs-jenis-indisipliner">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div><x-tabler.datatable-page-length :dataTableId="'jenis-indisipliner-table'" /></div>
                            <div><x-tabler.datatable-search :dataTableId="'jenis-indisipliner-table'" /></div>
                        </div>
                        <x-tabler.flash-message />
                        <x-tabler.datatable 
                            id="jenis-indisipliner-table"
                            route="{{ route('hr.jenis-indisipliner.data') }}"
                            :columns="[
                                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '50px'],
                                ['data' => 'jenis_indisipliner', 'name' => 'jenis_indisipliner', 'title' => 'Jenis Indisipliner'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '100px'],
                            ]"
                        />
                    </div>
                    @endif

                    {{-- Jenis Shift --}}
                    @if($activeTab == 'jenis-shift')
                    <div class="tab-pane active show" id="tabs-jenis-shift">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div><x-tabler.datatable-page-length :dataTableId="'jenis-shift-table'" /></div>
                            <div><x-tabler.datatable-search :dataTableId="'jenis-shift-table'" /></div>
                        </div>
                        <x-tabler.flash-message />
                        <x-tabler.datatable 
                            id="jenis-shift-table"
                            route="{{ route('hr.jenis-shift.data') }}"
                            :columns="[
                                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                                ['data' => 'jenis_shift', 'name' => 'jenis_shift', 'title' => 'Nama Shift'],
                                ['data' => 'jam_masuk', 'name' => 'jam_masuk', 'title' => 'Jam Masuk'],
                                ['data' => 'jam_pulang', 'name' => 'jam_pulang', 'title' => 'Jam Pulang'],
                                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'className' => 'text-center'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end'],
                            ]"
                        />
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
