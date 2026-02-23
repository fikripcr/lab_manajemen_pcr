@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Master Data E-Office" pretitle="Manajemen Data Layanan">
    <x-slot:actions>
        @if($activeTab == 'jenis-layanan')
            <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Jenis Layanan" class="ajax-modal-btn" data-url="{{ route('eoffice.jenis-layanan.create') }}" data-modal-title="Tambah Jenis Layanan" />
        @elseif($activeTab == 'kategori-isian')
            <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Kategori Isian" class="ajax-modal-btn" data-url="{{ route('eoffice.kategori-isian.create') }}" data-modal-title="Tambah Kategori Isian" />
        @elseif($activeTab == 'kategori-perusahaan')
            <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Kategori Perusahaan" class="ajax-modal-btn" data-url="{{ route('eoffice.kategori-perusahaan.create') }}" data-modal-title="Tambah Kategori Perusahaan" />
        @elseif($activeTab == 'perusahaan')
            <x-tabler.button type="button" icon="ti ti-plus" text="Tambah Perusahaan" class="ajax-modal-btn" data-url="{{ route('eoffice.perusahaan.create') }}" data-modal-title="Tambah Perusahaan" />
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
                        <a href="{{ route('eoffice.master-data.index', ['tab' => 'jenis-layanan']) }}" class="nav-link {{ $activeTab == 'jenis-layanan' ? 'active' : '' }}">
                            <i class="ti ti-file-description me-1"></i> Jenis Layanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('eoffice.master-data.index', ['tab' => 'kategori-isian']) }}" class="nav-link {{ $activeTab == 'kategori-isian' ? 'active' : '' }}">
                            <i class="ti ti-category me-1"></i> Kategori Isian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('eoffice.master-data.index', ['tab' => 'kategori-perusahaan']) }}" class="nav-link {{ $activeTab == 'kategori-perusahaan' ? 'active' : '' }}">
                            <i class="ti ti-building me-1"></i> Kategori Perusahaan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('eoffice.master-data.index', ['tab' => 'perusahaan']) }}" class="nav-link {{ $activeTab == 'perusahaan' ? 'active' : '' }}">
                            <i class="ti ti-building-bank me-1"></i> Perusahaan
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    {{-- Jenis Layanan Tab --}}
                    @if($activeTab == 'jenis-layanan')
                    <div class="tab-pane active show" id="tabs-jenis-layanan">
                        <div class="alert alert-info" role="alert">
                            <i class="ti ti-info-circle me-1"></i>
                            Kelola jenis layanan yang tersedia di E-Office. Setiap jenis layanan dapat memiliki form isian dinamis, PIC, dan alur disposisi.
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div><x-tabler.datatable-page-length :dataTableId="'jenis-layanan-table'" /></div>
                            <div><x-tabler.datatable-search :dataTableId="'jenis-layanan-table'" /></div>
                        </div>
                        <x-tabler.flash-message />
                        <x-tabler.datatable
                            id="jenis-layanan-table"
                            route="{{ route('eoffice.jenis-layanan.data') }}"
                            :columns="[
                                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '50px'],
                                ['data' => 'nama_layanan', 'name' => 'nama_layanan', 'title' => 'Nama Layanan'],
                                ['data' => 'kategori', 'name' => 'kategori', 'title' => 'Kategori'],
                                ['data' => 'batas_pengerjaan', 'name' => 'batas_pengerjaan', 'title' => 'Est. Pengerjaan (Jam)', 'className' => 'text-center'],
                                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'className' => 'text-center', 'width' => '100px'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end', 'width' => '120px'],
                            ]"
                        />
                    </div>
                    @endif

                    {{-- Kategori Isian Tab --}}
                    @if($activeTab == 'kategori-isian')
                    <div class="tab-pane active show" id="tabs-kategori-isian">
                        <div class="alert alert-info" role="alert">
                            <i class="ti ti-info-circle me-1"></i>
                            Kelola kategori isian untuk form dinamis. Kategori ini digunakan saat membuat form isian untuk jenis layanan.
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div><x-tabler.datatable-page-length :dataTableId="'kategori-isian-table'" /></div>
                            <div><x-tabler.datatable-search :dataTableId="'kategori-isian-table'" /></div>
                        </div>
                        <x-tabler.flash-message />
                        <x-tabler.datatable
                            id="kategori-isian-table"
                            route="{{ route('eoffice.kategori-isian.data') }}"
                            :columns="[
                                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '50px'],
                                ['data' => 'nama_isian', 'name' => 'nama_isian', 'title' => 'Nama Isian'],
                                ['data' => 'type', 'name' => 'type', 'title' => 'Tipe Input', 'className' => 'text-center'],
                                ['data' => 'is_required', 'name' => 'is_required', 'title' => 'Wajib', 'className' => 'text-center', 'width' => '80px'],
                                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'className' => 'text-center', 'width' => '100px'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end', 'width' => '100px'],
                            ]"
                        />
                    </div>
                    @endif

                    {{-- Kategori Perusahaan Tab --}}
                    @if($activeTab == 'kategori-perusahaan')
                    <div class="tab-pane active show" id="tabs-kategori-perusahaan">
                        <div class="alert alert-info" role="alert">
                            <i class="ti ti-info-circle me-1"></i>
                            Kelola kategori perusahaan untuk mengelompokkan jenis perusahaan/mitra.
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div><x-tabler.datatable-page-length :dataTableId="'kategori-perusahaan-table'" /></div>
                            <div><x-tabler.datatable-search :dataTableId="'kategori-perusahaan-table'" /></div>
                        </div>
                        <x-tabler.flash-message />
                        <x-tabler.datatable
                            id="kategori-perusahaan-table"
                            route="{{ route('eoffice.kategori-perusahaan.data') }}"
                            :columns="[
                                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '50px'],
                                ['data' => 'nama_kategori', 'name' => 'nama_kategori', 'title' => 'Nama Kategori'],
                                ['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan'],
                                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'className' => 'text-center', 'width' => '100px'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end', 'width' => '100px'],
                            ]"
                        />
                    </div>
                    @endif

                    {{-- Perusahaan Tab --}}
                    @if($activeTab == 'perusahaan')
                    <div class="tab-pane active show" id="tabs-perusahaan">
                        <div class="alert alert-info" role="alert">
                            <i class="ti ti-info-circle me-1"></i>
                            Kelola data perusahaan/mitra yang menggunakan layanan E-Office.
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div><x-tabler.datatable-page-length :dataTableId="'perusahaan-table'" /></div>
                            <div><x-tabler.datatable-search :dataTableId="'perusahaan-table'" /></div>
                        </div>
                        <x-tabler.flash-message />
                        <x-tabler.datatable
                            id="perusahaan-table"
                            route="{{ route('eoffice.perusahaan.data') }}"
                            :columns="[
                                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center', 'width' => '50px'],
                                ['data' => 'nama_perusahaan', 'name' => 'nama_perusahaan', 'title' => 'Nama Perusahaan'],
                                ['data' => 'kategoriPerusahaan.nama_kategori', 'name' => 'kategori', 'title' => 'Kategori'],
                                ['data' => 'alamat', 'name' => 'alamat', 'title' => 'Alamat'],
                                ['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status', 'className' => 'text-center', 'width' => '100px'],
                                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end', 'width' => '100px'],
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

@push('scripts')
<script>
    // Initialize DataTables with proper error handling
    document.addEventListener('DOMContentLoaded', function() {
        @if($activeTab == 'jenis-layanan')
            window.initDataTables('jenis-layanan-table');
        @elseif($activeTab == 'kategori-isian')
            window.initDataTables('kategori-isian-table');
        @elseif($activeTab == 'kategori-perusahaan')
            window.initDataTables('kategori-perusahaan-table');
        @elseif($activeTab == 'perusahaan')
            window.initDataTables('perusahaan-table');
        @endif
    });
</script>
@endpush
