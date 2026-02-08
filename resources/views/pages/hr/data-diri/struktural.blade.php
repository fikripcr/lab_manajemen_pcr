<div class="card-body border-bottom py-3">
    <div class="d-flex flex-wrap gap-2">
        <div>
            <x-tabler.datatable-page-length :dataTableId="'struktural-table'" />
        </div>
        <div class="ms-auto text-muted">
            <x-tabler.datatable-search :dataTableId="'struktural-table'" />
        </div>
    </div>
</div>
<div class="table-responsive">
    <x-tabler.datatable 
        id="struktural-table"
        route="{{ route('hr.jabatan-struktural-history.data') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
            ['data' => 'pegawai_nama', 'name' => 'pegawai.nama', 'title' => 'Pegawai'],
            ['data' => 'jabatan_nama', 'name' => 'jabatanStruktural.nama', 'title' => 'Jabatan Struktural'],
            ['data' => 'tgl_awal', 'name' => 'tgl_awal', 'title' => 'Tgl Awal', 'className' => 'text-center'],
            ['data' => 'tgl_akhir', 'name' => 'tgl_akhir', 'title' => 'Tgl Akhir', 'className' => 'text-center'],
            ['data' => 'no_sk', 'name' => 'no_sk', 'title' => 'No SK'],
        ]"
    />
</div>
