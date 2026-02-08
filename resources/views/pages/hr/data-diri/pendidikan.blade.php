<div class="card-body border-bottom py-3">
    <div class="d-flex flex-wrap gap-2">
        <div>
            <x-tabler.datatable-page-length :dataTableId="'pendidikan-table'" />
        </div>
        <div class="ms-auto text-muted">
            <x-tabler.datatable-search :dataTableId="'pendidikan-table'" />
        </div>
    </div>
</div>
<div class="table-responsive">
    <x-tabler.datatable 
        id="pendidikan-table"
        route="{{ route('hr.pendidikan.data') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
            ['data' => 'pegawai_nama', 'name' => 'pegawai.nama', 'title' => 'Pegawai'],
            ['data' => 'jenjang_pendidikan', 'name' => 'jenjang_pendidikan', 'title' => 'Jenjang'],
            ['data' => 'nama_pt', 'name' => 'nama_pt', 'title' => 'Nama Perguruan Tinggi'],
            ['data' => 'bidang_ilmu', 'name' => 'bidang_ilmu', 'title' => 'Bidang Ilmu'],
            ['data' => 'tgl_ijazah', 'name' => 'tgl_ijazah', 'title' => 'Tanggal Ijazah'],
            ['data' => 'kotaasal_pt', 'name' => 'kotaasal_pt', 'title' => 'Kota Asal'],
            ['data' => 'kodenegara_pt', 'name' => 'kodenegara_pt', 'title' => 'Negara'],
        ]"
    />
</div>
