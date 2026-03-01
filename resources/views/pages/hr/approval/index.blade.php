@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Persetujuan Perubahan Data" pretitle="Human Resources">
    <x-slot:actions>
        <div class="d-flex gap-2">
            <x-tabler.datatable-filter dataTableId="approval-table" :useCollapse="true">
                <div class="col-12">
                    <x-tabler.form-select name="status" label="Status Approval" class="mb-0">
                        <option value="Pending" selected>Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                        <option value="all">Semua</option>
                    </x-tabler.form-select>
                </div>
            </x-tabler.datatable-filter>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengajuan (Pending)</h3>
    </div>
    <div class="card-body">
        <x-tabler.datatable
            id="approval-table"
            route="{{ route('hr.approval.index') }}?status=Pending"
            :columns="[
                ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Pengajuan', 'class' => 'text-center w-1'],
                ['data' => 'pegawai_nama', 'name' => 'pegawai.nama', 'title' => 'Pegawai'],
                ['data' => 'tipe_request', 'name' => 'model_type', 'title' => 'Tipe Perubahan'],
                ['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
            ]"
        />
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle Approve
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-approve');
        if (!btn) return;

        const url = btn.dataset.url;
        if (confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')) {
            axios.post(url)
                .then(function(res) {
                    if (res.data.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Pengajuan berhasil disetujui', timer: 2000, showConfirmButton: false });
                        $('#approval-table').DataTable().ajax.reload();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal: ' + res.data.message });
                    }
                })
                .catch(function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.' });
                });
        }
    });

    // Handle Reject
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-reject');
        if (!btn) return;

        const url = btn.dataset.url;

        Swal.fire({
            title: 'Tolak Pengajuan',
            text: 'Apakah Anda yakin ingin menolak pengajuan ini?',
            input: 'textarea',
            inputLabel: 'Alasan Penolakan',
            inputPlaceholder: 'Masukkan alasan penolakan...',
            inputAttributes: { 'aria-label': 'Alasan Penolakan' },
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
                axios.post(url, { reason: result.value })
                    .then(function(res) {
                        if (res.data.status === 'success') {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Pengajuan berhasil ditolak', timer: 2000, showConfirmButton: false });
                            $('#approval-table').DataTable().ajax.reload();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal: ' + res.data.message });
                        }
                    })
                    .catch(function() {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.' });
                    });
            }
        });
    });
});
</script>
@endpush

