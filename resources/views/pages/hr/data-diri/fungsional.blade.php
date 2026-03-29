<x-tabler.card-header class="border-bottom">
    <div class="d-flex gap-2 align-items-center">
        <x-tabler.datatable-page-length dataTableId="fungsional-table" />
        <x-tabler.datatable-filter dataTableId="fungsional-table" type="button" target="#fungsional-filter-area" />
        <x-tabler.datatable-search dataTableId="fungsional-table" />
    </div>
</x-tabler.card-header>
<div class="collapse" id="fungsional-filter-area">
    <x-tabler.datatable-filter dataTableId="fungsional-table" type="bare">
        <div class="row g-3">
            <div class="col-md-4">
                <x-tabler.form-select name="jabatan_fungsional_id" label="Jabatan Fungsional" placeholder="" class="mb-0" :options="[]">
                    <option value="all" selected>Semua Jabatan Fungsional</option>
                </x-tabler.form-select>
            </div>
        </div>
    </x-tabler.datatable-filter>
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
