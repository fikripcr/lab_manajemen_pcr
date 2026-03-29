<x-tabler.card-header class="border-bottom">
    <div class="d-flex gap-2 align-items-center">
        <x-tabler.datatable-page-length dataTableId="pengembangan-table" />
        <x-tabler.datatable-filter dataTableId="pengembangan-table" type="button" target="#pengembangan-filter-area" />
        <x-tabler.datatable-search dataTableId="pengembangan-table" />
    </div>
</x-tabler.card-header>
<div class="collapse" id="pengembangan-filter-area">
    <x-tabler.datatable-filter dataTableId="pengembangan-table" type="bare">
        <div class="row g-3">
            <div class="col-md-4">
                <x-tabler.form-select name="jenis_kegiatan" label="Jenis Kegiatan" placeholder="" class="mb-0" 
                    :options="['Diklat' => 'Diklat', 'Bimtek' => 'Bimtek', 'Seminar/Workshop' => 'Seminar/Workshop', 'Lainnya' => 'Lainnya']">
                    <option value="all" selected>Semua Jenis Kegiatan</option>
                </x-tabler.form-select>
            </div>
        </div>
    </x-tabler.datatable-filter>
</div>
<div class="table-responsive">
    <x-tabler.datatable
        id="pengembangan-table"
        route="{{ route('hr.pengembangan.data') }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'pegawai_nama', 'name' => 'pegawai.nama', 'title' => 'Pegawai'],
            ['data' => 'jenis_kegiatan', 'name' => 'jenis_kegiatan', 'title' => 'Jenis'],
            ['data' => 'nama_kegiatan', 'name' => 'nama_kegiatan', 'title' => 'Nama Kegiatan'],
            ['data' => 'nama_penyelenggara', 'name' => 'nama_penyelenggara', 'title' => 'Penyelenggara'],
            ['data' => 'peran', 'name' => 'peran', 'title' => 'Peran'],
            ['data' => 'tahun', 'name' => 'tahun', 'title' => 'Tahun', 'class' => 'text-center'],
            ['data' => 'tgl_mulai', 'name' => 'tgl_mulai', 'title' => 'Tgl Mulai', 'class' => 'text-center'],
            ['data' => 'tgl_selesai', 'name' => 'tgl_selesai', 'title' => 'Tgl Selesai', 'class' => 'text-center'],
        ]"
    />
</div>
