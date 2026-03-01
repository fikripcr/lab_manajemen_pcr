@extends('layouts.tabler.app')
@section('header')
<x-tabler.page-header title="Manajemen Survei" pretitle="Feedback Module">
    <x-slot:actions>
        <x-tabler.button type="button" class="btn-primary ajax-modal-btn" icon="ti ti-plus" text="Buat Survei"
            data-modal-target="#modalAction" data-modal-title="Buat Survei Baru" data-url="{{ route('survei.create') }}" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection
@section('content')
        <div class="card">
            <div class="card-body p-0">
                <div class="d-flex align-items-center mb-3">
                    <div class="btn-group" id="bulk-actions" style="display: none;">
                        <x-tabler.button type="button" class="btn-outline-secondary btn-duplicate-bulk" icon="ti ti-copy" text="Duplikasi Terpilih" />
                    </div>
                </div>
                <x-tabler.datatable
                id="table-survei"
                checkbox="true"
                :columns="[
                    ['data' => 'judul', 'name' => 'judul', 'title' => 'Judul Survei'],
                    ['data' => 'periode', 'name' => 'periode', 'title' => 'Periode'],
                    ['data' => 'pelaksanaan', 'name' => 'pelaksanaan', 'title' => 'Pelaksanaan', 'orderable' => false],
                    ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                ]"
                :url="route('survei.data')" />
            </div>
        </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Bulk Actions based on CustomDataTables selection
    function updateBulkActions() {
        const dt = window['DT_table-survei'];
        if (dt && dt.selectedIds.size > 0) {
            $('#bulk-actions').fadeIn();
        } else {
            $('#bulk-actions').fadeOut();
        }
    }

    // Listen to checkbox changes (delegated)
    $(document).on('change', '.select-row, #selectAll-table-survei', function() {
        // Small delay to let CustomDataTables update its Set
        setTimeout(updateBulkActions, 50);
    });

    // Bulk Duplicate
    $('.btn-duplicate-bulk').on('click', function() {
        const dt = window['DT_table-survei'];
        if (!dt || dt.selectedIds.size === 0) return;

        const ids = Array.from(dt.selectedIds);

        Swal.fire({
            title: 'Duplikasi survei terpilih?',
            text: `Seluruh struktur (${ids.length} survei) akan diduplikasi.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Duplikasi',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#206bc4',
        }).then((result) => {
            if (result.isConfirmed) {
                showLoadingMessage('Menduplikasi...', 'Harap tunggu');

                const promises = ids.map(id => {
                    const url = "{{ route('survei.duplicate', ':id') }}".replace(':id', id);
                    return axios.post(url);
                });

                Promise.all(promises)
                    .then(() => {
                        window['DT_table-survei'].table.ajax.reload();
                        window['DT_table-survei'].selectedIds.clear();
                        $('#selectAll-table-survei').prop('checked', false);
                        updateBulkActions();
                        showSuccessMessage('Berhasil menduplikasi survei terpilih.');
                    })
                    .catch(() => {
                        showErrorMessage('Error!', 'Gagal menduplikasi beberapa survei.');
                    });
            }
        });
    });

    // Single Duplicate (from dropdown)
    $(document).on('click', '.btn-duplicate-single', function(e) {
        e.preventDefault();
        const url = $(this).data('url');

        Swal.fire({
            title: 'Duplikasi survei ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Duplikasi',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                showLoadingMessage('Menduplikasi...', 'Harap tunggu');
                axios.post(url).then(response => {
                    if (window['DT_table-survei']) window['DT_table-survei'].table.ajax.reload(null, false);
                    showSuccessMessage('Survei berhasil diduplikasi.');
                }).catch(error => {
                    showErrorMessage('Error!', 'Gagal menduplikasi survei.');
                });
            }
        });
    });

    // Toggle Status (Publish/Unpublish)
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
                        if (window['DT_table-survei']) {
                            window['DT_table-survei'].table.ajax.reload(null, false);
                        }
                        showSuccessMessage(response.data.message || 'Berhasil!');
                    })
                    .catch(error => {
                        showErrorMessage('Error!', 'Gagal mengubah status');
                    });
            }
        });
    });

    // Copy Shareable Link (delegated because it's in dropdown)
    $(document).on('click', '.btn-copy-link', function(e) {
        e.preventDefault();
        const link = $(this).data('link');
        navigator.clipboard.writeText(link).then(() => {
            showSuccessMessage('Link survei berhasil disalin!');
        });
    });
});
</script>
@endpush
