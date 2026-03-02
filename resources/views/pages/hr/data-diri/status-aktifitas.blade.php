<div class="card-header border-bottom">
    <div class="d-flex flex-wrap gap-2 w-100">
        <div>
            <x-tabler.datatable-page-length dataTableId="status-aktifitas-table" />
        </div>
        <div>
            <x-tabler.datatable-search dataTableId="status-aktifitas-table" />
        </div>
        <div>
            <x-tabler.datatable-filter dataTableId="status-aktifitas-table">
                <div style="min-width: 150px;">
                    <x-tabler.form-select name="status_aktifitas_id" placeholder="Semua Aktifitas" class="mb-0" :options="[]" />
                </div>
            </x-tabler.datatable-filter>
        </div>
    </div>
</div>
<div class="table-responsive">
    <x-tabler.datatable
        id="status-aktifitas-table"
        route="{{ route('hr.status-aktifitas-history.data') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'pegawai_nama', 'name' => 'pegawai.nama', 'title' => 'Pegawai'],
            ['data' => 'status_nama', 'name' => 'statusAktifitas.nama', 'title' => 'Status Aktifitas'],
            ['data' => 'tmt', 'name' => 'tmt', 'title' => 'TMT', 'class' => 'text-center'],
            ['data' => 'no_sk', 'name' => 'no_sk', 'title' => 'No SK'],
        ]"
    />
</div>
