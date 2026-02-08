<div class="card-body border-bottom py-3">
    <div class="d-flex flex-wrap gap-2">
        <div>
            <x-tabler.datatable-page-length :dataTableId="'status-aktifitas-table'" />
        </div>
        <div class="ms-auto text-muted">
            <x-tabler.datatable-search :dataTableId="'status-aktifitas-table'" />
        </div>
    </div>
</div>
<div class="table-responsive">
    <x-tabler.datatable 
        id="status-aktifitas-table"
        route="{{ route('hr.status-aktifitas-history.data') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
            ['data' => 'pegawai_nama', 'name' => 'pegawai.nama', 'title' => 'Pegawai'],
            ['data' => 'status_nama', 'name' => 'statusAktifitas.nama', 'title' => 'Status Aktifitas'],
            ['data' => 'tmt', 'name' => 'tmt', 'title' => 'TMT', 'className' => 'text-center'],
            ['data' => 'no_sk', 'name' => 'no_sk', 'title' => 'No SK'],
        ]"
    />
</div>
