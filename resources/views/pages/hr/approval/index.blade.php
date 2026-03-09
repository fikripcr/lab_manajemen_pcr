@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Persetujuan Perubahan Data" pretitle="Human Resources" />
@endsection

@section('content')
<x-tabler.card class="overflow-hidden">
    <x-tabler.card-header>
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length dataTableId="approval-table" />
            </div>
            <div>
                <x-tabler.datatable-search dataTableId="approval-table" />
            </div>
            <div>
                <x-tabler.datatable-filter dataTableId="approval-table">
                    <div style="min-width: 160px;">
                        <x-tabler.form-select name="status" placeholder="Semua Status" class="mb-0"
                            :options="['Pending' => 'Pending', 'Approved' => 'Disetujui', 'Rejected' => 'Ditolak', 'Tangguhkan' => 'Tangguhkan']" />
                    </div>
                </x-tabler.datatable-filter>
            </div>
        </div>
    </x-tabler.card-header>
    <x-tabler.card-body class="p-0">
        <x-tabler.datatable
            id="approval-table"
            route="{{ route('hr.approval.index') }}"
            :columns="[
                ['data' => 'created_at',   'name' => 'created_at', 'title' => 'Tanggal', 'class' => 'text-center', 'width' => '110px'],
                ['data' => 'pegawai_nama', 'name' => 'pegawai_nama', 'title' => 'Pegawai'],
                ['data' => 'tipe_request', 'name' => 'tipe_request', 'title' => 'Tipe Perubahan'],
                ['data' => 'keterangan',   'name' => 'keterangan', 'title' => 'Keterangan'],
                ['data' => 'status',       'name' => 'status', 'title' => 'Status', 'class' => 'text-center', 'orderable' => false, 'searchable' => false, 'width' => '120px'],
                ['data' => 'action',       'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '160px'],
            ]"
        />
    </x-tabler.card-body>
</x-tabler.card>

{{-- Modal Keterangan (Tolak / Tangguhkan) --}}
<x-tabler.form-modal 
    id="modalKeterangan" 
    title="Keterangan" 
    :hide-footer="false"
>
    <div class="mb-0">
        <label class="form-label">Keterangan / Alasan</label>
        <x-tabler.form-textarea 
            id="inputKeterangan" 
            name="keterangan" 
            label="Keterangan / Alasan" 
            rows="4" 
            placeholder="Masukkan keterangan atau alasan..." 
        />
        <div class="invalid-feedback" id="keteranganError">Keterangan harus diisi.</div>
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn" id="btnConfirmProcess">
            <span id="btnConfirmText">Proses</span>
        </button>
    </x-slot:footer>
</x-tabler.form-modal>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let _pendingUrl    = null;
    let _pendingStatus = null;

    function reloadTable() {
        if ($.fn.DataTable.isDataTable('#approval-table')) {
            $('#approval-table').DataTable().ajax.reload(null, false);
        }
    }

    function doProcess(url, status, reason) {
        return axios.post(url, { status: status, reason: reason ?? '' });
    }

    function showResult(success, message) {
        if (success) {
            showSuccessMessage('Berhasil', message);
            reloadTable();
        } else {
            showErrorMessage('Gagal', message);
        }
    }

    // ── Unified: btn-process (both from DataTable action and from modal in show.blade.php)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-process');
        if (!btn) return;

        const url        = btn.dataset.url;
        const status     = btn.dataset.status;
        const needReason = btn.dataset.needReason === '1' || status === 'Tangguhkan';

        if (needReason) {
            _pendingUrl    = url;
            _pendingStatus = status;

            document.querySelector('#modalKeterangan .modal-title').textContent =
                status === 'Rejected' ? 'Alasan Penolakan' : 'Keterangan Penangguhan';
            document.getElementById('btnConfirmText').textContent =
                status === 'Rejected' ? 'Tolak' : 'Tangguhkan';
            document.getElementById('btnConfirmProcess').className =
                'btn ' + (status === 'Rejected' ? 'btn-danger' : 'btn-warning');
            document.getElementById('inputKeterangan').value = '';
            document.getElementById('inputKeterangan').classList.remove('is-invalid');

            new bootstrap.Modal(document.getElementById('modalKeterangan')).show();
        } else {
            doProcess(url, status, null)
                .then(r => showResult(r.data.success, r.data.message))
                .catch(() => showResult(false, 'Terjadi kesalahan sistem.'));
        }
    });

    // ── Confirm inside modal
    document.getElementById('btnConfirmProcess').addEventListener('click', function () {
        const reason = document.getElementById('inputKeterangan').value.trim();
        if (_pendingStatus === 'Rejected' && !reason) {
            document.getElementById('inputKeterangan').classList.add('is-invalid');
            return;
        }

        bootstrap.Modal.getInstance(document.getElementById('modalKeterangan')).hide();

        doProcess(_pendingUrl, _pendingStatus, reason)
            .then(r => showResult(r.data.success, r.data.message))
            .catch(() => showResult(false, 'Terjadi kesalahan sistem.'));

        _pendingUrl = _pendingStatus = null;
    });
});
</script>
@endpush
