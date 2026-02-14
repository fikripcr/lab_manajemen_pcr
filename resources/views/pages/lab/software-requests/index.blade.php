@extends('layouts.admin.app')

@section('title', 'Permintaan Software')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Daftar Permintaan Software
                </h2>
                <div class="text-muted mt-1">
                    Kelola permintaan instalasi software
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.software-requests.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-2"></i> Buat Request
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table" id="table-software-requests">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Software</th>
                                <th>Dosen</th>
                                <th>Mata Kuliah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="w-1">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#table-software-requests').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('lab.software-requests.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_software', name: 'nama_software' },
                { data: 'dosen_name', name: 'dosen.name' },
                { data: 'mata_kuliah', name: 'mata_kuliah', orderable: false, searchable: false },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            order: [[5, 'desc']] // Sort by Created At
        });
    });
</script>
@endpush
