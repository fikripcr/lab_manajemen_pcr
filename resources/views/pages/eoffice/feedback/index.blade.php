@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="E-Office">
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Feedback</h3>
    </div>
    <div class="card-body border-bottom py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Jenis Layanan</label>
                <select id="f_jenislayanan" class="form-select">
                    <option value="">Semua Jenis Layanan</option>
                    @foreach($jenisLayananList as $jl)
                        <option value="{{ encryptId($jl->jenislayanan_id) }}">{{ $jl->nama_layanan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" id="f_tgl_start" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" id="f_tgl_end" class="form-control">
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-primary w-100" id="btn-filter">
                    <i class="ti ti-filter"></i> Filter
                </button>
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
