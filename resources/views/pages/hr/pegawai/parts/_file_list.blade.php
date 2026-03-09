<x-tabler.card class="mb-3">
    <x-tabler.card-header title="Daftar File Pegawai">
        <x-slot:actions>
            <div class="d-flex gap-2">
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
        </x-slot:actions>
    </x-tabler.card-header>
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
</x-tabler.card>
