<div class="card-body border-bottom py-3">
    <div class="d-flex flex-wrap gap-2">
        <div>
            <x-tabler.datatable-page-length :dataTableId="'keluarga-table'" />
        </div>
        <div class="ms-auto text-muted">
            <x-tabler.datatable-search :dataTableId="'keluarga-table'" />
        </div>
    </div>
</div>
<div class="table-responsive">
    <x-tabler.datatable
        id="keluarga-table"
        route="{{ route('hr.keluarga.data') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'pegawai_nama', 'name' => 'pegawai.nama', 'title' => 'Pegawai'],
            ['data' => 'nama', 'name' => 'nama', 'title' => 'Nama'],
            ['data' => 'jenis_kelamin', 'name' => 'jenis_kelamin', 'title' => 'Gender', 'class' => 'text-center'],
            ['data' => 'hubungan', 'name' => 'hubungan', 'title' => 'Hubungan'],
            ['data' => 'tgl_lahir', 'name' => 'tgl_lahir', 'title' => 'Tanggal Lahir'],
            ['data' => 'alamat', 'name' => 'alamat', 'title' => 'Alamat'],
            ['data' => 'telp', 'name' => 'telp', 'title' => 'Kontak'],
            ['data' => 'asuransi', 'name' => 'asuransi', 'title' => 'Asuransi'],
            ['data' => 'file_pendukung', 'name' => 'file_pendukung', 'title' => 'File'],
        ]"
    />
</div>
