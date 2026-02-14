@extends('layouts.admin.app')

@section('title', 'Laporan Kerusakan')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Laporan Kerusakan
                </h2>
                <div class="text-muted mt-1">
                    Daftar laporan kerusakan inventaris lab
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.laporan-kerusakan.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-2"></i> Buat Laporan
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table" id="table-laporan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Alat / Inventaris</th>
                                <th>Pelapor</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="w-1">Action</th>
                            </tr>
                        </thead>
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
        $('#table-laporan').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('lab.laporan-kerusakan.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'alat_info', name: 'inventaris.nama_alat' },
                { data: 'pelapor_id', name: 'created_by' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    });
</script>
@endpush
