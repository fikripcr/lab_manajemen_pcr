<div class="card mb-3">
    <div class="card-header border-bottom">
        <div class="d-flex flex-wrap gap-2 w-100 align-items-center">
            <h3 class="card-title mb-0">Daftar File Pegawai</h3>
            <div class="ms-auto d-flex gap-2">
                <x-tabler.datatable-page-length dataTableId="files-table" />
                <x-tabler.datatable-search dataTableId="files-table" />
                <x-tabler.button 
                    style="primary" 
                    class="ajax-modal-btn" 
                    data-url="{{ route('hr.pegawai.files.create', $pegawai->encrypted_pegawai_id) }}" 
                    data-modal-title="Upload File Pegawai"
                    icon="ti ti-upload"
                    text="Upload" />
            </div>
        </div>
    </div>
    <x-tabler.datatable
        id="files-table"
        route="{{ route('hr.pegawai.files.data', $pegawai->encrypted_pegawai_id) }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'category', 'name' => 'jenisfile.jenisfile', 'title' => 'Kategori'],
            ['data' => 'filename', 'name' => 'filename', 'title' => 'Nama File'],
            ['data' => 'size', 'name' => 'size', 'title' => 'Ukuran', 'class' => 'text-end'],
            ['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end'],
        ]"
    />
</div>
