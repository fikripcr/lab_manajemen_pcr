@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Persetujuan Perubahan Data" pretitle="Human Resources" />
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengajuan (Pending)</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable" id="approval-table">
                <thead>
                    <tr>
                        <th class="text-center w-1">Tanggal Pengajuan</th>
                        <th>Pegawai</th>
                        <th>Tipe Perubahan</th>
                        <th>Keterangan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.loadDataTables().then(() => {
        const table = $('#approval-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('hr.approval.index') }}",
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'pegawai_nama', name: 'pegawai.nama' },
                { data: 'tipe_request', name: 'model_type' }, // Simplified sort logic
                { data: 'keterangan', name: 'keterangan' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Handle Approve
        $(document).on('click', '.btn-approve', function() {
            const url = $(this).data('url');
            if(confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')) {
                $.post(url, {
                    _token: "{{ csrf_token() }}"
                })
                .done(function(res) {
                    if(res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Pengajuan berhasil disetujui',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        table.ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal: ' + res.message
                        });
                    }
                })
                .fail(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan sistem.'
                    });
                });
            }
        });

        // Handle Reject
        $(document).on('click', '.btn-reject', function() {
            const url = $(this).data('url');
            
            Swal.fire({
                title: 'Tolak Pengajuan',
                text: 'Apakah Anda yakin ingin menolak pengajuan ini?',
                input: 'textarea',
                inputLabel: 'Alasan Penolakan',
                inputPlaceholder: 'Masukkan alasan penolakan...',
                inputAttributes: {
                    'aria-label': 'Alasan Penolakan'
                },
                showCancelButton: true,
                confirmButtonText: 'Tolak',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                showLoaderOnConfirm: true,
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('Alasan penolakan harus diisi');
                        return false;
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                        _token: "{{ csrf_token() }}",
                        reason: result.value
                    })
                    .done(function(res) {
                        if(res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Pengajuan berhasil ditolak',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal: ' + res.message
                            });
                        }
                    })
                    .fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan sistem.'
                        });
                    });
                }
            });
        });
    });
});
</script>
@endpush
