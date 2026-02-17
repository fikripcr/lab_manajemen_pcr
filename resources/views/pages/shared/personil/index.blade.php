@extends('layouts.admin.app')

@section('header')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Shared Data</div>
                <h2 class="page-title">Data Personil (Outsource)</h2>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table-personil" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Posisi</th>
                                <th>Vendor</th>
                                <th>Unit Kerja</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#table-personil').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('shared.personil.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'personil_id', orderable: false, searchable: false},
            {data: 'nama', name: 'nama'},
            {data: 'email', name: 'email'},
            {data: 'posisi', name: 'posisi'},
            {data: 'vendor', name: 'vendor'},
            {data: 'unit_kerja.name', name: 'unitKerja.name', defaultContent: '-'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>
@endpush
