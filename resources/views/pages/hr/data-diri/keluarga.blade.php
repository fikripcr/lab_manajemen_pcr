<x-tabler.card-header class="border-bottom">
    <div class="d-flex gap-2 align-items-center">
        <x-tabler.datatable-page-length dataTableId="keluarga-table" />
        <x-tabler.datatable-filter dataTableId="keluarga-table" type="button" target="#keluarga-filter-area" />
        <x-tabler.datatable-search dataTableId="keluarga-table" />
    </div>
</x-tabler.card-header>
<div class="collapse" id="keluarga-filter-area">
    <x-tabler.datatable-filter dataTableId="keluarga-table" type="bare">
        <div class="row g-3">
            <div class="col-md-4">
                <x-tabler.form-select name="hubungan" label="Hubungan Keluarga" placeholder="" class="mb-0"
                    :options="['Suami' => 'Suami', 'Istri' => 'Istri', 'Anak' => 'Anak', 'Orang Tua' => 'Orang Tua']">
                    <option value="all" selected>Semua Hubungan</option>
                </x-tabler.form-select>
            </div>
        </div>
    </x-tabler.datatable-filter>
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
