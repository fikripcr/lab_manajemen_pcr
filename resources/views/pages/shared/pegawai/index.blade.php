@extends('layouts.admin.app')

@section('header')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Shared Data
                </div>
                <h2 class="page-title">
                    Data Pegawai
                </h2>
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
                    <table class="table" id="table-pegawai" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Email</th>
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
    $('#table-pegawai').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('shared.pegawai.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'pegawai_id', orderable: false, searchable: false},
            {data: 'nip', name: 'nip'},
            {data: 'nama', name: 'nama'},
            {data: 'email', name: 'email'},
            {data: 'unit_kerja.name', name: 'unitKerja.name', defaultContent: '-'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>
@endpush
