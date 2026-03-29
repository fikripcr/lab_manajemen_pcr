<x-tabler.card-header class="border-bottom">
    <div class="d-flex gap-2 align-items-center">
        <x-tabler.datatable-page-length dataTableId="pendidikan-table" />
        <x-tabler.datatable-filter dataTableId="pendidikan-table" type="button" target="#pendidikan-filter-area" />
        <x-tabler.datatable-search dataTableId="pendidikan-table" />
    </div>
</x-tabler.card-header>
<div class="collapse" id="pendidikan-filter-area">
    <x-tabler.datatable-filter dataTableId="pendidikan-table" type="bare">
        <div class="row g-3">
            <div class="col-md-4">
                <x-tabler.form-select name="jenjang" label="Jenjang" placeholder="" class="mb-0"
                    :options="['D3' => 'D3', 'D4' => 'D4', 'S1' => 'S1', 'S2' => 'S2', 'S3' => 'S3']">
                    <option value="all" selected>Semua Jenjang</option>
                </x-tabler.form-select>
            </div>
        </div>
    </x-tabler.datatable-filter>
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
