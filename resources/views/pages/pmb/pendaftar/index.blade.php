@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Daftar Pendaftar PMB" pretitle="PMB" />
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'table-pendaftar'" />
            </div>
            <div class="ms-auto">
                <x-tabler.datatable-search :dataTableId="'table-pendaftar'" />
            </div>
        </div>
    </div>
    <div class="card-body">
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
    </div>
</div>


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

    fetch('{{ route("pmb.pendaftar.verify-document") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ dokumen_id: dokumenId, status: status })
    })
    .then(function(response) {
        return response.json().then(function(data) {
            if (!response.ok || !data.success) {
                const message = data?.message || 'Terjadi kesalahan saat verifikasi.';
                Swal.fire({ icon: 'error', title: 'Gagal!', text: message });
                target.checked = !target.checked;
            }
        }).catch(function() {
            // JSON parse failed
            Swal.fire({ icon: 'error', title: 'Error!', text: 'Terjadi kesalahan saat verifikasi.' });
            target.checked = !target.checked;
        });
    })
    .catch(function(error) {
        const message = error?.message || 'Terjadi kesalahan saat verifikasi.';
        Swal.fire({ icon: 'error', title: 'Error!', text: message });
        target.checked = !target.checked;
    });
});
</script>
@endpush
