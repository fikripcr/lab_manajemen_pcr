@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Daftar Pendaftar PMB" pretitle="PMB" />
@endsection

@section('content')
<x-tabler.card>
    <x-tabler.card-header>
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'table-pendaftar'" />
            </div>
            <div class="ms-auto">
                <x-tabler.datatable-search :dataTableId="'table-pendaftar'" />
            </div>
        </div>
    </x-tabler.card-header>
    <x-tabler.card-body class="p-0">
        <x-tabler.datatable
            id="table-pendaftar"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                ['data' => 'no_pendaftaran', 'name' => 'no_pendaftaran', 'title' => 'Kode Daftar'],
                ['data' => 'nama', 'name' => 'camaba.user.name', 'title' => 'Nama'],
                ['data' => 'tanggal_daftar', 'name' => 'waktu_daftar', 'title' => 'Tanggal Daftar'],
                ['data' => 'status_upload', 'name' => 'status_upload', 'title' => 'Status Upload'],
                ['data' => 'total_verif', 'name' => 'total_verif', 'title' => 'Verif', 'orderable' => false, 'class' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ]"
            :route="route('pmb.pendaftar.data')"
        />
    </x-tabler.card-body>
</x-tabler.card>


<!-- Modal Verifikasi Berkas dengan Toggle -->
<x-tabler.form-modal id="modalVerifikasiBerkas" size="modal-lg" title="Verifikasi Berkas" method="none" data-bs-backdrop="static" hideFooter="true">
    <div id="verifikasiContent">
        <div class="text-center py-5">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</x-tabler.form-modal>
@endsection

@push('scripts')
<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest ? e.target.closest('.btn-verify-docs') : null;
    if (!btn) return;
    e.preventDefault();

    const pendaftaranId = btn.dataset.pendaftaranId;

    const modalEl = document.getElementById('modalVerifikasiBerkas');
    const modalInstance = (typeof bootstrap !== 'undefined') ? bootstrap.Modal.getOrCreateInstance(modalEl) : null;
    if (modalInstance) modalInstance.show();

    const contentEl = document.getElementById('verifikasiContent');
    if (contentEl) {
        contentEl.innerHTML = '<div class="text-center py-5"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    }

    axios.get('{{ route("pmb.pendaftar.load-berkas") }}', { params: { pendaftaran_id: pendaftaranId } })
    .then(function(response) {
        if (contentEl) contentEl.innerHTML = response.data.html ?? '<div class="alert alert-danger">Gagal memuat data berkas.</div>';
    })
    .catch(function() {
        if (contentEl) contentEl.innerHTML = '<div class="alert alert-danger">Gagal memuat data berkas.</div>';
    });
});

document.addEventListener('change', function(e) {
    const target = e.target;
    if (!target || !target.classList.contains('toggle-verifikasi')) return;

    e.preventDefault();

    const dokumenId = target.dataset.dokumenId;
    const status = target.checked ? 'Valid' : 'Pending';

    axios.post('{{ route("pmb.pendaftar.verify-document") }}', { 
        dokumen_id: dokumenId, 
        status: status 
    })
    .then(function(response) {
        if (!response.data.success) {
            const message = response.data.message || 'Terjadi kesalahan saat verifikasi.';
            showErrorMessage('Gagal!', message);
            target.checked = !target.checked;
        }
    })
    .catch(function(error) {
        let message = 'Terjadi kesalahan saat verifikasi.';
        if (error.response && error.response.data && error.response.data.message) {
            message = error.response.data.message;
        }
        showErrorMessage('Error!', message);
        target.checked = !target.checked;
    });
});
</script>
@endpush
