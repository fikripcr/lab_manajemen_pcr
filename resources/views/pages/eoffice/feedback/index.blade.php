@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="E-Office"/>
@endsection

@section('content')
<x-tabler.card>
    <x-tabler.card-header>
        <h3 class="card-title">Daftar Feedback</h3>
        <x-slot:actions>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-download me-2"></i> Export Data
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    {{-- Export options would go here --}}
                </ul>
            </div>
            <x-tabler.datatable-filter :dataTableId="'tbl-feedback'" :useCollapse="true">
                <div class="row g-2">
                    <div class="col-12">
                        <x-tabler.form-select name="jenislayanan_id" label="Jenis Layanan" id="f_jenislayanan">
                            <option value="">Semua Jenis Layanan</option>
                            @foreach($jenisLayananList as $jl)
                                <option value="{{ encryptId($jl->jenislayanan_id) }}">{{ $jl->nama_layanan }}</option>
                            @endforeach
                        </x-tabler.form-select>
                    </div>
                    <div class="col-12">
                        <x-tabler.form-input type="date" name="f_tgl_start" label="Tanggal Mulai" id="f_tgl_start" />
                    </div>
                    <div class="col-12">
                        <x-tabler.form-input type="date" name="f_tgl_end" label="Tanggal Akhir" id="f_tgl_end" />
                    </div>
                </div>
            </x-tabler.datatable-filter>
        </x-slot:actions>
    </x-tabler.card-header>
    <x-tabler.card-body class="p-0">
        <x-tabler.datatable
            id="tbl-feedback"
            route="{{ route('eoffice.feedback.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'title' => '#', 'class' => 'text-center', 'width' => '5%'],
                ['data' => 'no_layanan', 'title' => 'No Layanan'],
                ['data' => 'nama_layanan', 'title' => 'Jenis Layanan'],
                ['data' => 'rating_stars', 'title' => 'Rating'],
                ['data' => 'feedback', 'title' => 'Feedback'],
                ['data' => 'tanggal', 'title' => 'Tanggal']
            ]"
        />
    </x-tabler.card-body>
</x-tabler.card>
@endsection

@push('scripts')
<script>
</script>
@endpush
