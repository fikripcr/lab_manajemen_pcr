@extends('layouts.admin.app')
@section('header')
<x-tabler.page-header title="Manajemen Survei" pretitle="Feedback Module">
    <x-slot:actions>
        <button type="button" class="btn btn-primary ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Buat Survei Baru" data-url="{{ route('survei.create') }}">
            <i class="ti ti-plus"></i> Buat Survei
        </button>
    </x-slot:actions>
</x-tabler.page-header>
@endsection
@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                id="table-survei" 
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                    ['data' => 'judul', 'name' => 'judul', 'title' => 'Judul Survei'],
                    ['data' => 'target_role', 'name' => 'target_role', 'title' => 'Target'],
                    ['data' => 'tanggal_mulai', 'name' => 'tanggal_mulai', 'title' => 'Mulai'],
                    ['data' => 'tanggal_selesai', 'name' => 'tanggal_selesai', 'title' => 'Selesai'],
                    ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                ]" 
                :url="route('survei.paginate')" />
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Status (Publish/Unpublish) via POST with SweetAlert confirmation
    $(document).on('click', '.btn-toggle-status', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const url = $btn.data('url');
        const title = $btn.data('title') || 'Ubah status survei?';

        Swal.fire({
            title: title,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#206bc4',
        }).then((result) => {
            if (result.isConfirmed) {
                showLoadingMessage('Memproses...', 'Harap tunggu');
                axios.post(url)
                    .then(response => {
                        if ($.fn.DataTable && $('.dataTable').length) {
                            $('.dataTable').DataTable().ajax.reload(null, false);
                        }
                        showSuccessMessage(response.data.message || 'Berhasil!');
                    })
                    .catch(error => {
                        let msg = 'Gagal mengubah status';
                        if (error.response && error.response.data && error.response.data.message) {
                            msg = error.response.data.message;
                        }
                        showErrorMessage('Error!', msg);
                    });
            }
        });
    });

    // Copy Shareable Link to clipboard
    $(document).on('click', '.btn-copy-link', function(e) {
        e.preventDefault();
        const link = $(this).data('link');
        navigator.clipboard.writeText(link).then(() => {
            showSuccessMessage('Link survei berhasil disalin!');
        }).catch(() => {
            // Fallback for older browsers
            const input = document.createElement('input');
            input.value = link;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            showSuccessMessage('Link survei berhasil disalin!');
        });
    });
});
</script>
@endpush
