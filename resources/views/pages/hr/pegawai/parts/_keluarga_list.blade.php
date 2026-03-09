<x-tabler.card class="mb-3">
    <x-tabler.card-header title="Data Keluarga">
        <x-slot:actions>
            <div class="d-flex gap-2">
                <x-tabler.datatable-page-length dataTableId="keluarga-table" />
                <x-tabler.datatable-search dataTableId="keluarga-table" />
                <x-tabler.button 
                    type="create"
                    class="ajax-modal-btn" 
                    data-url="{{ route('hr.pegawai.keluarga.create', $pegawai->encrypted_pegawai_id) }}" 
                    data-modal-title="Tambah Keluarga"
                    text="Tambah" />
            </div>
        </x-slot:actions>
    </x-tabler.card-header>
    <x-tabler.datatable
        id="keluarga-table"
        route="{{ route('hr.keluarga.data', ['pegawai_id' => $pegawai->encrypted_pegawai_id]) }}"
        :columns="[
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'nama', 'name' => 'nama', 'title' => 'Nama'],
            ['data' => 'hubungan', 'name' => 'hubungan', 'title' => 'Hubungan'],
            ['data' => 'tgl_lahir', 'name' => 'tgl_lahir', 'title' => 'Tgl Lahir', 'class' => 'text-center'],
            ['data' => 'pekerjaan', 'name' => 'pekerjaan', 'title' => 'Pekerjaan'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end'],
        ]"
    />
</x-tabler.card>
