@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Penjadwalan Ujian (CBT)" pretitle="CBT">
    <x-slot:actions>
        <x-tabler.button type="button" class="btn-primary ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Tambah Jadwal Ujian" data-url="{{ route('cbt.jadwal.create') }}" icon="ti ti-plus" text="Tambah Jadwal" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'table-jadwal'" />
                </div>
                <div class="ms-auto">
                    <x-tabler.datatable-search :dataTableId="'table-jadwal'" />
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-tabler.datatable
                id="table-jadwal"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                    ['data' => 'kegiatan_paket', 'name' => 'nama_kegiatan', 'title' => 'Ujian & Paket', 'orderable' => false],
                    ['data' => 'token_info', 'name' => 'token_ujian', 'title' => 'Token', 'orderable' => false],
                    ['data' => 'waktu_status', 'name' => 'waktu_mulai', 'title' => 'Waktu & Status', 'orderable' => false],
                    ['data' => 'peserta', 'name' => 'peserta', 'title' => 'Peserta', 'orderable' => false, 'class' => 'text-center'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'width' => '150px', 'class' => 'text-center']
                ]"
                :url="route('cbt.jadwal.data')"
            />
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-jadwal-action');
    if (!btn) return;

    const url = btn.dataset.url;
    btn.disabled = true;

    axios.post(url)
        .then(function(res) {
            if (res.data.success || res.data.status === 'success') {
                toastr.success(res.data.message || 'Berhasil');
                $('#table-jadwal').DataTable().ajax.reload(null, false);
            } else {
                toastr.error(res.data.message || 'Terjadi kesalahan');
            }
        })
        .catch(function(error) {
            const errorMsg = error.response?.data?.message || 'Terjadi kesalahan';
            toastr.error(errorMsg);
        })
        .finally(function() {
            btn.disabled = false;
        });
});
</script>
@endpush

