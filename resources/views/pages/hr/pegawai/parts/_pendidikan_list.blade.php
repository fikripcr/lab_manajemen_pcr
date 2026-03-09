<x-tabler.card class="mb-3">
    <x-tabler.card-header title="Riwayat Pendidikan">
        <x-slot:actions>
            <div class="d-flex gap-2">
                <x-tabler.datatable-page-length dataTableId="pendidikan-table" />
                <x-tabler.datatable-search dataTableId="pendidikan-table" />
                <x-tabler.button 
                    type="create"
                    class="ajax-modal-btn" 
                    data-url="{{ route('hr.pegawai.pendidikan.create', $pegawai->encrypted_pegawai_id) }}" 
                    data-modal-title="Tambah Pendidikan"
                    text="Tambah" />
            </div>
        </x-slot:actions>
    </x-tabler.card-header>
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
</x-tabler.card>
