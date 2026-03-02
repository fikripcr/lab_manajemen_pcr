<div class="card-header border-bottom">
    <div class="d-flex flex-wrap gap-2 w-100">
        <div>
            <x-tabler.datatable-page-length dataTableId="pendidikan-table" />
        </div>
        <div>
            <x-tabler.datatable-search dataTableId="pendidikan-table" />
        </div>
        <div>
            <x-tabler.datatable-filter dataTableId="pendidikan-table">
                <div style="min-width: 150px;">
                    <x-tabler.form-select name="jenjang" placeholder="Semua Jenjang" class="mb-0"
                        :options="['D3' => 'D3', 'D4' => 'D4', 'S1' => 'S1', 'S2' => 'S2', 'S3' => 'S3']" />
                </div>
            </x-tabler.datatable-filter>
        </div>
    </div>
</div>
<div class="table-responsive">
    <x-tabler.datatable
        id="pendidikan-table"
        route="{{ route('hr.pendidikan.data') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
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
