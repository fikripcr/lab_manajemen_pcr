@extends('layouts.admin.app')

@section('title', 'Data Perizinan')

@section('header')
<x-tabler.page-header title="Data Perizinan" pretitle="HR & Kepegawaian">
    <x-slot:actions>
        <div class="d-flex gap-2">
            <x-tabler.form-select id="filter-year" label="Tahun" class="form-select-sm mb-0" style="width: 100px;">
                @foreach ($years as $year)
                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </x-tabler.form-select>
            <x-tabler.button type="button" icon="ti ti-plus" text="Ajukan Izin" class="ajax-modal-btn" data-url="{{ route('hr.perizinan.create') }}" data-modal-title="Form Pengajuan Izin" />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
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
        $('#filter-year').on('change', function() {
            var year = $(this).val();
            var table = $('#table-perizinan').DataTable();
            var url = '{{ route("hr.perizinan.data") }}?year=' + year;
            table.ajax.url(url).load();
        });
    });
</script>
@endpush
