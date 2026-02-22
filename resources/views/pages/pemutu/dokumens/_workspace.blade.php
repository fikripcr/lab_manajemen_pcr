<div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            @if($type === 'dokumen')
                <div class="badge bg-blue-lt mb-2">{{ strtoupper($item->jenis) }}</div>
                <h2 class="m-0">{{ $item->judul }}</h2>
                @if($item->kode)
                    <div class="text-muted mt-1">Kode: {{ $item->kode }}</div>
                @endif
            @elseif($type === 'poin')
                <div class="badge bg-purple-lt mb-2">POIN ({{ strtoupper($item->dokumen->jenis) }})</div>
                <h2 class="m-0">{{ $item->judul }}</h2>
                <div class="text-muted mt-1">Bagian dari Dokumen: {{ $item->dokumen->judul }}</div>
            @endif
        </div>
        <div class="d-flex gap-2">
            @if($type === 'dokumen')
                <x-tabler.button class="ajax-modal-btn" text="Edit" icon="ti ti-edit" data-url="{{ route('pemutu.dokumen-spmi.edit', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id]) }}" data-modal-title="Ubah Dokumen" />
                <x-tabler.button text="Hapus" color="danger" icon="ti ti-trash" class="ajax-delete" data-url="{{ route('pemutu.dokumen-spmi.destroy', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id]) }}" data-title="Hapus Dokumen ini?" />
            @elseif($type === 'poin')
                <x-tabler.button class="btn-primary ajax-modal-btn" href="#"
                    data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'dokumen', 'parent_doksub_id' => $item->encrypted_doksub_id, 'parent_id' => $item->dok_id]) }}"
                    data-modal-title="Tambah Dokumen Turunan" icon="ti ti-plus" text="Tambah Turunan Dokumen" />
                @if($item->is_hasilkan_indikator)
                    <x-tabler.button class="btn-success ajax-modal-btn" href="#"
                        data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'indikator', 'parent_doksub_id' => $item->encrypted_doksub_id]) }}"
                        data-modal-title="Tambah Indikator" icon="ti ti-plus" text="Tambah Indikator" />
                @endif
            @endif
        </div>
    </div>

    @if($item->isi)
        <div class="hr-text">Konten Dokumen</div>
        <div class="markdown text-muted mb-4">
            {!! $item->isi !!}
        </div>
    @endif

    {{-- DOKUMEN: Show Poins (DokSubs) or Child Dokumens --}}
    @if($type === 'dokumen')
        <div class="hr-text">Komponen Terkait</div>
        
        <div class="card bg-transparent shadow-none border">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar {{ $childLabel ?? 'Turunan' }}</h3>
                <x-tabler.button class="ajax-modal-btn" text="Tambah {{ $childLabel ?? 'Turunan' }}" icon="ti ti-plus" data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => $isDokSubBased ? 'poin' : 'dokumen', 'parent_id' => $item->encrypted_dok_id]) }}" data-modal-title="Tambah {{ $childLabel ?? 'Turunan' }}" size="sm" />
            </div>
            
            {{-- Unified DataTable placeholder for children --}}
            <div class="table-responsive">
                <table class="table table-vcenter table-striped dataTable" id="children-table" data-url="{{ route('pemutu.dokumen-spmi.children-data', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id]) }}">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Judul / Nama</th>
                            <th>Jumlah Turunan</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    {{-- POIN: Show Indikator or Child Dokumens --}}
    @elseif($type === 'poin')
        <div class="hr-text">Komponen Terkait Poin Ini</div>

        @if($item->is_hasilkan_indikator)
            <div class="card bg-transparent shadow-none border mb-3">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Indikator Terlampir</h3>
                    <x-tabler.button class="ajax-modal-btn" text="Tambah Indikator" icon="ti ti-plus" data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'indikator', 'parent_doksub_id' => $item->encrypted_doksub_id]) }}" data-modal-title="Tambah Indikator" size="sm" />
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter table-striped dataTable" id="indikators-table" data-url="{{ route('pemutu.dokumen-spmi.children-data', ['type' => 'poin_indikator', 'id' => $item->encrypted_doksub_id]) }}">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Indikator</th>
                                <th>Target</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Always show child dokumens relation if exist or to add them --}}
        <div class="card bg-transparent shadow-none border">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title">Berdasarkan Poin Ini (Sub-Dokumen)</h3>
                <x-tabler.button class="ajax-modal-btn" text="Tambah Dokumen Turunan" icon="ti ti-plus" data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'dokumen', 'parent_doksub_id' => $item->encrypted_doksub_id, 'parent_id' => $item->dok_id]) }}" data-modal-title="Tambah Dokumen Turunan" size="sm" />
            </div>
             <div class="table-responsive">
                <table class="table table-vcenter table-striped dataTable" id="poin-children-table" data-url="{{ route('pemutu.dokumen-spmi.children-data', ['type' => 'poin_dokumen', 'id' => $item->encrypted_doksub_id]) }}">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Sub-Dokumen</th>
                            <th>Jenis</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<script>
    if (window.loadDataTables) {
        window.loadDataTables().then(() => {
            if ($('#children-table').length) {
                if ($.fn.DataTable.isDataTable('#children-table')) $('#children-table').DataTable().destroy();
                $('#children-table').DataTable({
                    processing: true, serverSide: true,
                    ajax: $('#children-table').data('url'),
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'judul', name: 'judul' },
                        { data: 'jumlah_turunan', name: 'jumlah_turunan' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
                });
            }

            if($('#indikators-table').length) {
                if ($.fn.DataTable.isDataTable('#indikators-table')) $('#indikators-table').DataTable().destroy();
                $('#indikators-table').DataTable({
                    processing: true, serverSide: true,
                    ajax: $('#indikators-table').data('url'),
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'indikator', name: 'indikator' },
                        { data: 'keterangan', name: 'keterangan' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
                });
            }

            if($('#poin-children-table').length) {
                if ($.fn.DataTable.isDataTable('#poin-children-table')) $('#poin-children-table').DataTable().destroy();
                $('#poin-children-table').DataTable({
                    processing: true, serverSide: true,
                    ajax: $('#poin-children-table').data('url'),
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'judul', name: 'judul' },
                        { data: 'jenis', name: 'jenis' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
                });
            }
        });
    }
</script>
