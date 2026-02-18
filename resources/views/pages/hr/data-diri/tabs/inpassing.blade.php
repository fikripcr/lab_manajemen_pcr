@extends('layouts.admin.app')
@section('header')
@if(isset($pegawai))
    @include('components.hr.profile-header')
@else
    <x-tabler.page-header title="Data Inpassing" pretitle="HR Management" />
@endif
@endsection

@section('content')
<div class="card">
    @include('pages.hr.data-diri.global-tab-nav')
    <div class="card-body p-0">
        @if(isset($pegawai))
             @include('pages.hr.pegawai.parts._inpassing_list')
        @else
            {{-- Global Table View for Inpassing --}}
            <div class="card-table">
                 <x-tabler.datatable
                    id="table-inpassing"
                    route="{{ route('hr.inpassing.data') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nama_pegawai', 'name' => 'pegawai.nama', 'title' => 'Pegawai'],
                        ['data' => 'golongan', 'name' => 'golonganInpassing.golongan', 'title' => 'Golongan'],
                        ['data' => 'tmt', 'name' => 'tmt', 'title' => 'Terhitung Mulai Tanggal'],
                        ['data' => 'no_sk', 'name' => 'no_sk', 'title' => 'Nomor SK'],
                        ['data' => 'tgl_sk', 'name' => 'tgl_sk', 'title' => 'Tanggal SK'],
                        ['data' => 'masa_kerja_tahun', 'name' => 'masa_kerja_tahun', 'title' => 'Masa Kerja', 'render' => 'function(data, type, row) { return (row.masa_kerja_tahun || 0) + \' Tahun \' + (row.masa_kerja_bulan || 0) + \' Bulan\'; }'],
                        ['data' => 'gaji_pokok', 'name' => 'gaji_pokok', 'title' => 'Gaji Pokok', 'render' => 'function(data, type, row) { return new Intl.NumberFormat(\'id-ID\', { style: \'currency\', currency: \'IDR\', minimumFractionDigits: 0 }).format(data); }']
                    ]"
                />
            </div>
        @endif
    </div>
</div>
@endsection
