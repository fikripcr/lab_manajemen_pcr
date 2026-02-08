
<div class="card-body border-bottom py-3">
    <div class="d-flex flex-wrap gap-2">
        <div>
            <x-tabler.datatable-page-length :dataTableId="'pengembangan-table'" />
        </div>
        <div class="ms-auto text-muted">
            <x-tabler.datatable-search :dataTableId="'pengembangan-table'" />
        </div>
    </div>
</div>
<div class="table-responsive">
    <x-tabler.datatable 
        id="pengembangan-table"
        route="{{ route('hr.pengembangan.data') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
            ['data' => 'pegawai_nama', 'name' => 'pegawai.nama', 'title' => 'Pegawai'],
            ['data' => 'jenis_kegiatan', 'name' => 'jenis_kegiatan', 'title' => 'Jenis'],
            ['data' => 'nama_kegiatan', 'name' => 'nama_kegiatan', 'title' => 'Nama Kegiatan'],
            ['data' => 'nama_penyelenggara', 'name' => 'nama_penyelenggara', 'title' => 'Penyelenggara'],
            ['data' => 'peran', 'name' => 'peran', 'title' => 'Peran'],
            ['data' => 'tahun', 'name' => 'tahun', 'title' => 'Tahun', 'className' => 'text-center'],
            ['data' => 'tgl_mulai', 'name' => 'tgl_mulai', 'title' => 'Tgl Mulai', 'className' => 'text-center'],
            ['data' => 'tgl_selesai', 'name' => 'tgl_selesai', 'title' => 'Tgl Selesai', 'className' => 'text-center'],
        ]"
    />
</div>
