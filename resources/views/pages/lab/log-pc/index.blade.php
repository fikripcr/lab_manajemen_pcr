@extends('layouts.admin.app')

@section('title', 'Log Penggunaan PC')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Log Penggunaan PC
                </h2>
                <div class="text-muted mt-1">
                    Monitoring penggunaan PC harian
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.log-pc.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-2"></i> Isi Log Sekarang
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table" id="table-log-pc">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Mahasiswa</th>
                                <th>Waktu</th>
                                <th>PC Info</th>
                                <th>Kondisi & Catatan</th>
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
        $('#table-log-pc').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('lab.log-pc.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'mahasiswa', name: 'user.name' },
                { data: 'waktu', name: 'waktu_isi' },
                { data: 'pc_info', name: 'lab.name' }, // PC Info usually derived
                { 
                    data: 'kondisi', 
                    name: 'status_pc',
                    render: function(data, type, row) {
                         // Note: 'kondisi' column from controller already returns HTML badge.
                         // But let's refine it if needed or trust controller.
                         // Controller logic: $color = $log->status_pc == 'Baik' ? 'success' : 'danger';
                         // It returns: <span class='badge bg-{$color}'>{$log->status_pc}</span><br><small>{$log->catatan_umum}</small>
                         // This is already good. We just ensure the column definition matches.
                         return data;
                    }
                },
            ],
            order: [[2, 'desc']] // Sort by Waktu desc
        });
    });
</script>
@endpush
