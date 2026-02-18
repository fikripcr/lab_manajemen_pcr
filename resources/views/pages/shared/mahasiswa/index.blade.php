@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Data Mahasiswa" pretitle="Shared Data" />
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table-mahasiswa" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Prodi</th>
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
    $('#table-mahasiswa').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('shared.mahasiswa.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'mahasiswa_id', orderable: false, searchable: false},
            {data: 'nim', name: 'nim'},
            {data: 'nama', name: 'nama'},
            {data: 'email', name: 'email'},
            {data: 'prodi_nama', name: 'prodi_nama'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>
@endpush
