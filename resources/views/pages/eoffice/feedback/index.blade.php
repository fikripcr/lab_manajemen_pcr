@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="E-Office"/>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Feedback</h3>
    </div>
    <div class="card-body border-bottom py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <x-tabler.form-select name="f_jenislayanan" label="Jenis Layanan">
                    <option value="">Semua Jenis Layanan</option>
                    @foreach($jenisLayananList as $jl)
                        <option value="{{ encryptId($jl->jenislayanan_id) }}">{{ $jl->nama_layanan }}</option>
                    @endforeach
                </x-tabler.form-select>
            </div>
            <div class="col-md-3">
                <x-tabler.form-input type="date" name="f_tgl_start" label="Tanggal Mulai" />
            </div>
            <div class="col-md-3">
                <x-tabler.form-input type="date" name="f_tgl_end" label="Tanggal Akhir" />
            </div>
            <div class="col-md-3">
                <x-tabler.button type="button" class="btn-primary w-100" id="btn-filter" icon="ti ti-filter" text="Filter" />
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table" id="tbl-feedback">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>No Layanan</th>
                    <th>Jenis Layanan</th>
                    <th>Rating</th>
                    <th>Feedback</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        var table = $('#tbl-feedback').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("eoffice.feedback.data") }}',
                data: function(d) {
                    d.jenislayanan_id = document.getElementById('f_jenislayanan').value;
                    d.f_tgl_start     = document.getElementById('f_tgl_start').value;
                    d.f_tgl_end       = document.getElementById('f_tgl_end').value;
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'no_layanan' },
                { data: 'nama_layanan' },
                { data: 'rating_stars' },
                { data: 'feedback' },
                { data: 'tanggal' },
            ]
        });

        document.getElementById('btn-filter').addEventListener('click', function() {
            table.draw();
        });
    }
});
</script>
@endpush
