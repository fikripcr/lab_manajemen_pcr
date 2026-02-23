@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="E-Office Transactions">
    <x-slot:actions>
        <x-tabler.button type="create" :href="route('eoffice.layanan.services')" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            {{-- Kiri: Page Length & Filter --}}
            <div class="d-flex gap-2">
                <x-tabler.datatable-page-length dataTableId="table-layanan" />
                <x-tabler.datatable-filter dataTableId="table-layanan" :useCollapse="true">
                    <div class="col-md-4">
                        <label class="form-label">Jenis Layanan</label>
                        <x-tabler.form-select name="jenislayanan_id" class="select2-filter">
                            <option value="">Semua Jenis Layanan</option>
                            @foreach($jenisLayanans as $jl)
                                <option value="{{ $jl->jenislayanan_id }}">{{ $jl->nama_layanan }}</option>
                            @endforeach
                        </x-tabler.form-select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <x-tabler.form-select name="status">
                            <option value="">Semua Status</option>
                            <option value="Diajukan">Diajukan</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Disposisi">Disposisi</option>
                            <option value="Direvisi">Butuh Revisi</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Ditolak">Ditolak</option>
                        </x-tabler.form-select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Rentang Tanggal</label>
                        <x-tabler.form-input name="date_range" placeholder="Pilih Tanggal..." />
                    </div>
                </x-tabler.datatable-filter>
            </div>
            
            {{-- Kanan: Search (ms-auto mendorong ke ujung kanan) --}}
            <div class="">
                <x-tabler.datatable-search dataTableId="table-layanan" />
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <x-tabler.datatable
            id="table-layanan"
            route="{{ route('eoffice.layanan.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%', 'class' => 'text-center'],
                ['data' => 'no_layanan', 'name' => 'no_layanan', 'title' => 'No. Pengajuan', 'class' => 'fw-bold'],
                ['data' => 'jenis_layanan.nama_layanan', 'name' => 'jenisLayanan.nama_layanan', 'title' => 'Jenis Layanan'],
                ['data' => 'pengusul_nama', 'name' => 'pengusul_nama', 'title' => 'Pemohon'],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tgl Diajukan'],
                ['data' => 'status_label', 'name' => 'latestStatus.status_layanan', 'title' => 'Status', 'class' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '10%']
            ]"
        />
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for filter if available
    if (window.loadSelect2) {
        window.loadSelect2().then(() => {
            $('.select2-filter').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih opsi...',
                dropdownParent: $('#table-layanan-filter').closest('.dropdown-menu')
            });
        });
    }
    
    // Initialize date range picker if available
    const dateRangeInput = document.querySelector('input[name="date_range"]');
    if (dateRangeInput && typeof flatpickr !== 'undefined') {
        flatpickr(dateRangeInput, {
            mode: 'range',
            dateFormat: 'Y-m-d',
            locale: 'id'
        });
    }
});
</script>
@endpush
@endsection
