<x-tabler.card-header class="border-bottom">
    <div class="d-flex gap-2 align-items-center">
        <x-tabler.datatable-page-length dataTableId="status-pegawai-table" />
        <x-tabler.datatable-filter dataTableId="status-pegawai-table" type="button" target="#status-pegawai-filter-area" />
        <x-tabler.datatable-search dataTableId="status-pegawai-table" />
    </div>
</x-tabler.card-header>
<div class="collapse" id="status-pegawai-filter-area">
    <x-tabler.datatable-filter dataTableId="status-pegawai-table" type="bare">
        <div class="row g-3">
            <div class="col-md-4">
                <x-tabler.form-select name="status_pegawai_id" label="Status Pegawai" placeholder="" class="mb-0" :options="[]">
                    <option value="all" selected>Semua Status Pegawai</option>
                </x-tabler.form-select>
            </div>
        </div>
    </x-tabler.datatable-filter>
</div>
<div class="table-responsive">
    <x-tabler.datatable
        id="status-pegawai-table"
        route="{{ route('hr.status-pegawai-history.data') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'pegawai_nama', 'name' => 'pegawai.nama', 'title' => 'Pegawai'],
            ['data' => 'status_nama', 'name' => 'statusPegawai.nama', 'title' => 'Status Pegawai'],
            ['data' => 'tmt', 'name' => 'tmt', 'title' => 'TMT', 'class' => 'text-center'],
            ['data' => 'no_sk', 'name' => 'no_sk', 'title' => 'No SK'],
        ]"
    />
</div>
