<div class="card-header border-bottom">
    <div class="d-flex flex-wrap gap-2 w-100">
        <div>
            <x-tabler.datatable-page-length dataTableId="fungsional-table" />
        </div>
        <div>
            <x-tabler.datatable-search dataTableId="fungsional-table" />
        </div>
        <div>
            <x-tabler.datatable-filter dataTableId="fungsional-table">
                <div style="min-width: 150px;">
                    <x-tabler.form-select name="jabatan_fungsional_id" placeholder="Semua Jabatan" class="mb-0" :options="[]" />
                </div>
            </x-tabler.datatable-filter>
        </div>
    </div>
</div>
<div class="table-responsive">
    <x-tabler.datatable
        id="fungsional-table"
        route="{{ route('hr.jabatan-fungsional-history.data') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'pegawai_nama', 'name' => 'pegawai.nama', 'title' => 'Pegawai'],
            ['data' => 'jabatan_nama', 'name' => 'jabatanFungsional.nama', 'title' => 'Jabatan Fungsional'],
            ['data' => 'tmt', 'name' => 'tmt', 'title' => 'TMT', 'class' => 'text-center'],
            ['data' => 'no_sk', 'name' => 'no_sk', 'title' => 'No SK'],
        ]"
    />
</div>
