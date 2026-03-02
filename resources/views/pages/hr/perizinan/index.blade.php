@extends('layouts.tabler.app')

@section('title', 'Data Perizinan')

@section('header')
<x-tabler.page-header title="Data Perizinan" pretitle="HR & Kepegawaian">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-plus" text="Ajukan Izin" class="ajax-modal-btn" data-url="{{ route('hr.perizinan.create') }}" data-modal-title="Form Pengajuan Izin" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header border-bottom">
        <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
            <h3 class="card-title mb-0">Data Perizinan</h3>
            <div class="ms-auto d-flex gap-2 align-items-center">
                <x-tabler.datatable-page-length dataTableId="table-perizinan" />
                <x-tabler.datatable-search dataTableId="table-perizinan" />
                <x-tabler.datatable-filter dataTableId="table-perizinan">
                    <div style="min-width: 120px;">
                        <x-tabler.form-select name="year" placeholder="Tahun" class="mb-0">
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </x-tabler.form-select>
                    </div>
                    <div style="min-width: 150px;">
                        <x-tabler.form-select name="status" placeholder="Semua Status" class="mb-0"
                            :options="['Diajukan' => 'Diajukan', 'Approved' => 'Disetujui', 'Rejected' => 'Ditolak']" />
                    </div>
                </x-tabler.datatable-filter>
            </div>
        </div>
    </div>
    <div class="card-body">
        <x-tabler.datatable
            id="table-perizinan"
            route="{{ route('hr.perizinan.data') }}?year={{ $selectedYear }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%', 'class' => 'text-center'],
                ['data' => 'nama_pegawai', 'name' => 'nama_pegawai', 'title' => 'Pegawai'],
                ['data' => 'jenis_izin', 'name' => 'jenis_izin', 'title' => 'Jenis Izin'],
                ['data' => 'tanggal', 'name' => 'tanggal', 'title' => 'Tanggal'],
                ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'class' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '12%']
            ]"
        />
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // x-tabler.datatable-filter handles the reloading automatically
    });
</script>
@endpush
