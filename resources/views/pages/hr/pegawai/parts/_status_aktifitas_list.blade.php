<div class="card mb-3">
    <div class="card-header border-bottom">
        <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
            <h3 class="card-title mb-0">Riwayat Status Aktifitas</h3>
            <div class="ms-auto d-flex gap-2">
                <x-tabler.datatable-page-length dataTableId="status-aktifitas-table" />
                <x-tabler.datatable-search dataTableId="status-aktifitas-table" />
                <x-tabler.button 
                    style="primary" 
                    class="ajax-modal-btn" 
                    data-url="{{ route('hr.pegawai.status-aktifitas.create', $pegawai->encrypted_pegawai_id) }}" 
                    data-modal-title="Ubah Status Aktifitas"
                    icon="ti ti-edit"
                    text="Ubah" />
            </div>
        </div>
    </div>
    <x-tabler.datatable
        id="status-aktifitas-table"
        route="{{ route('hr.status-aktifitas-history.data', ['pegawai_id' => $pegawai->encrypted_pegawai_id]) }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'status_nama', 'name' => 'statusAktifitas.nama', 'title' => 'Status'],
            ['data' => 'tmt', 'name' => 'tmt', 'title' => 'TMT', 'class' => 'text-center'],
            ['data' => 'no_sk', 'name' => 'no_sk', 'title' => 'No. SK'],
            ['data' => 'approval_status', 'name' => 'approval_status', 'title' => 'Status', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
        ]"
    />
</div>
