@php
    $isRenopPoint = $type === 'poin' && strtolower(trim($item->dokumen->jenis ?? '')) === 'renop';
    $showIndikatorSection = $type === 'poin' && ($item->is_hasilkan_indikator || $isRenopPoint);
@endphp

    $childrenColumns = [
        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
        ['data' => 'judul', 'name' => 'judul', 'title' => 'Judul / Nama'],
        ['data' => 'jumlah_turunan', 'name' => 'jumlah_turunan', 'title' => 'Jumlah Turunan'],
        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
    ];

    $indikatorColumns = [
        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
        ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Nama Indikator'],
        ['data' => 'unit_target', 'name' => 'unit_target', 'title' => 'Unit & Target', 'orderable' => false, 'searchable' => false],
        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
    ];

    $poinChildrenColumns = [
        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
        ['data' => 'judul', 'name' => 'judul', 'title' => 'Sub-Dokumen'],
        ['data' => 'jenis', 'name' => 'jenis', 'title' => 'Jenis'],
        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
    ];
@endphp

<div class="card">
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
            <div class="btn-group">
                @if($type === 'dokumen')
                    <x-tabler.button type="success" class="btn-sm ajax-modal-btn" text="Approval" icon="ti ti-users"  data-url="{{ route('pemutu.dokumens.approve.create', $item->encrypted_dok_id) }}" data-modal-title="Form Approval Dokumen" />
                    <x-tabler.button type="primary" class="btn-sm ajax-modal-btn" text="Edit" icon="ti ti-edit" data-url="{{ route('pemutu.dokumen-spmi.edit', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id]) }}" data-modal-title="Ubah Dokumen" />
                    <x-tabler.button type="delete" class="btn-sm ajax-delete" text="Hapus" icon="ti ti-trash" data-url="{{ route('pemutu.dokumen-spmi.destroy', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id]) }}" data-title="Hapus Dokumen ini?" />
                @elseif($type === 'poin')
                    @if(!$isRenopPoint)
                        <x-tabler.button class="btn-primary btn-sm ajax-modal-btn" href="#"
                            data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'dokumen', 'parent_doksub_id' => $item->encrypted_doksub_id, 'parent_id' => $item->dok_id]) }}"
                            data-modal-title="Tambah Dokumen Turunan" icon="ti ti-plus" text="Tambah Turunan Dokumen" />
                    @endif
                    @if($showIndikatorSection)
                        @if($isRenopPoint)
                            <x-tabler.button class="btn-success" href="{{ route('pemutu.indikators.create', ['parent_dok_id' => $item->encrypted_dok_id, 'parent_doksub_id' => $item->encrypted_doksub_id, 'type' => 'renop', 'is_renop_context' => 1, 'redirect_to' => url()->current()]) }}"
                                icon="ti ti-plus" text="Tambah Indikator" />
                        @else
                            <x-tabler.button class="btn-success ajax-modal-btn" href="#"
                                data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'indikator', 'parent_doksub_id' => $item->encrypted_doksub_id, 'is_renop_context' => 0]) }}"
                                data-modal-title="Tambah Indikator" icon="ti ti-plus" text="Tambah Indikator" />
                        @endif
                    @endif
                @endif
            </div>

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
                <h2 class="card-title">Daftar {{ $childLabel ?? 'Turunan' }}</h2>
                <x-tabler.button class="ajax-modal-btn" text="Tambah {{ $childLabel ?? 'Turunan' }}" icon="ti ti-plus" data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => $isDokSubBased ? 'poin' : 'dokumen', 'parent_id' => $item->encrypted_dok_id]) }}" data-modal-title="Tambah {{ $childLabel ?? 'Turunan' }}" size="sm" />
            </div>

            <x-tabler.datatable id="children-table" :url="route('pemutu.dokumen-spmi.children-data', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id])" :columns="$childrenColumns" ajax-load />
        </div>

    {{-- POIN: Show Indikator or Child Dokumens --}}
    @elseif($type === 'poin')
        <div class="hr-text">Komponen Terkait Poin Ini</div>

        @if($showIndikatorSection)
            <div class="card bg-transparent shadow-none border mb-3">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h2 class="card-title">
                        {{ $isRenopPoint ? 'Daftar Indikator Renop' : 'Indikator Terlampir' }}
                        <span class="badge bg-muted-lt ms-2">{{ $item->indikators->count() }}</span>
                    </h2>
                    @if($isRenopPoint)
                        <x-tabler.button class="btn-danger" href="{{ route('pemutu.indikators.create', ['parent_dok_id' => $item->encrypted_dok_id, 'parent_doksub_id' => $item->encrypted_doksub_id, 'type' => 'renop', 'is_renop_context' => 1, 'redirect_to' => url()->current()]) }}"
                            icon="ti ti-plus" text="Tambah Indikator" size="sm" />
                    @else
                        <x-tabler.button class="ajax-modal-btn" text="Tambah Indikator" icon="ti ti-plus" data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'indikator', 'parent_doksub_id' => $item->encrypted_doksub_id, 'is_renop_context' => 0]) }}" data-modal-title="Tambah Indikator" size="sm" />
                    @endif
                </div>
                <x-tabler.datatable id="indikators-table" :url="route('pemutu.dokumen-spmi.children-data', ['type' => 'poin_indikator', 'id' => $item->encrypted_doksub_id])" :columns="$indikatorColumns" ajax-load />
            </div>
        @endif

        @if(!$isRenopPoint)
        {{-- Always show child dokumens relation if exist or to add them --}}
        <div class="card bg-transparent shadow-none border">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
                <h2 class="card-title">Berdasarkan Poin Ini (Sub-Dokumen)</h2>
                <x-tabler.button class="ajax-modal-btn" text="Tambah Dokumen Turunan" icon="ti ti-plus" data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'dokumen', 'parent_doksub_id' => $item->encrypted_doksub_id, 'parent_id' => $item->dok_id]) }}" data-modal-title="Tambah Dokumen Turunan" size="sm" />
            </div>
            <x-tabler.datatable id="poin-children-table" :url="route('pemutu.dokumen-spmi.children-data', ['type' => 'poin_dokumen', 'id' => $item->encrypted_doksub_id])" :columns="$poinChildrenColumns" ajax-load />
        </div>
        @endif
    @endif

    {{-- Approval History --}}
    @if($type === 'dokumen')
        <x-tabler.approval-history :approvals="$item->approvals" />
    @endif
</div>
        </div>


