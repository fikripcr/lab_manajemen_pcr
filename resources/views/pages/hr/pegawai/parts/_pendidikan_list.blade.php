<div class="card mb-3">
    <div class="card-header border-bottom">
        <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
            <h3 class="card-title mb-0">Riwayat Pendidikan</h3>
            <div class="ms-auto d-flex gap-2">
                <x-tabler.datatable-page-length dataTableId="pendidikan-table" />
                <x-tabler.datatable-search dataTableId="pendidikan-table" />
                <x-tabler.button 
                    style="primary" 
                    class="ajax-modal-btn" 
                    data-url="{{ route('hr.pegawai.pendidikan.create', $pegawai->encrypted_pegawai_id) }}" 
                    data-modal-title="Tambah Pendidikan"
                    icon="ti ti-plus"
                    text="Tambah" />
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <x-tabler.datatable
            id="pendidikan-table"
            route="{{ route('hr.pendidikan.data', ['pegawai_id' => $pegawai->encrypted_pegawai_id]) }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                ['data' => 'jenjang_pendidikan', 'name' => 'jenjang_pendidikan', 'title' => 'Jenjang'],
                ['data' => 'nama_pt', 'name' => 'nama_pt', 'title' => 'Institusi'],
                ['data' => 'bidang_ilmu', 'name' => 'bidang_ilmu', 'title' => 'Bidang Ilmu'],
                ['data' => 'tgl_ijazah', 'name' => 'tgl_ijazah', 'title' => 'Tahun', 'class' => 'text-center'],
                ['data' => 'ijazah', 'name' => 'ijazah', 'title' => 'Ijazah', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end'],
            ]"
        />
    </div>
</div>
