@php
    $isKebijakan = $isKebijakan ?? false;
    $parentJenis = '';
    $mappableOptions = $mappableOptions ?? collect();

    if ($type === 'poin') {
        $parentJenis = strtolower(trim($item->dokumen->jenis ?? ''));
        $isRenopPoint = $parentJenis === 'renop';
        $showIndikatorSection = $isRenopPoint && $item->is_hasilkan_indikator;
        $mappableJenis = pemutuMappableJenis($parentJenis);
    } else {
        $isRenopPoint = false;
        $showIndikatorSection = false;
        $mappableJenis = null;
    }

    $childrenColumns = [
        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
        ['data' => 'judul', 'name' => 'judul', 'title' => 'Judul / Nama'],
        ['data' => 'jumlah_turunan', 'name' => 'jumlah_turunan', 'title' => 'Jumlah Turunan'],
        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
    ];

    $indikatorColumns = [
        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
        ['data' => 'indikator', 'name' => 'indikator', 'title' => 'Nama Indikator'],
        ['data' => 'unit_target', 'name' => 'unit_target', 'title' => 'Unit & Target', 'orderable' => false, 'searchable' => false],
        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
    ];

    $mappingColumns = [
        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
        ['data' => 'judul', 'name' => 'judul', 'title' => 'Poin Tujuan'],
        ['data' => 'kode', 'name' => 'kode', 'title' => 'Kode'],
        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
    ];

    $poinChildrenColumns = [
        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
        ['data' => 'judul', 'name' => 'judul', 'title' => 'Sub-Dokumen'],
        ['data' => 'jenis', 'name' => 'jenis', 'title' => 'Jenis'],
        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
    ];
@endphp

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-1">
            <div>
                @if($type === 'dokumen')
                    <div class="badge bg-primary-lt">DOKUMEN {{ strtoupper($item->jenis) }}</div>
                @elseif($type === 'poin')
                    <div class="badge bg-secondary-lt">POIN {{ strtoupper($item->dokumen->jenis) }}</div>
                @endif
                @if($item->kode)
                    <div class="badge bg-secondary-lt" title="Kode Dokumen">{{ $item->kode }}</div>
                @endif
            </div>
            <div class="d-flex gap-2 flex-shrink-0">
                <div class="btn-group">
                    @if($type === 'dokumen')
                        @if(!$isKebijakan)
                            {{-- Edit/Delete only for non-kebijakan (standar, etc) --}}
                            <x-tabler.button type="success" class="btn-sm ajax-modal-btn" text="Approval" icon="ti ti-users"
                                data-url="{{ route('pemutu.dokumen.approve.create', $item->encrypted_dok_id) }}"
                                data-modal-title="Form Approval Dokumen"
                                data-approval="true" />
                            <x-tabler.button type="primary" class="btn-sm btn-secondary ajax-modal-btn me-0" text="" icon="ti ti-edit"
                                data-url="{{ route('pemutu.dokumen-spmi.edit', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id]) }}"
                                data-modal-title="Ubah Dokumen" />
                            <x-tabler.button type="delete" class="btn-sm ajax-delete" text="" icon="ti ti-trash"
                                data-url="{{ route('pemutu.dokumen-spmi.destroy', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id]) }}"
                                data-title="Hapus Dokumen ini?" />
                        @else
                            {{-- Kebijakan: only edit title allowed --}}
                            <x-tabler.button type="success" class="btn-sm ajax-modal-btn" text="Approval" icon="ti ti-users"
                                data-url="{{ route('pemutu.dokumen.approve.create', $item->encrypted_dok_id) }}"
                                data-modal-title="Form Approval Dokumen"
                                data-approval="true" />
                            <x-tabler.button type="primary" class="btn-sm btn-secondary ajax-modal-btn me-0" text="" icon="ti ti-edit"
                                data-url="{{ route('pemutu.dokumen-spmi.edit', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id]) }}"
                                data-modal-title="Ubah Judul Dokumen" />
                        @endif
                    @elseif($type === 'poin')
                        @if($isKebijakan && $isRenopPoint && $item->is_hasilkan_indikator)
                            <x-tabler.button type="create" class="btn-success"
                                href="{{ route('pemutu.indikator.create', ['parent_dok_id' => $item->encrypted_dok_id, 'parent_doksub_id' => $item->encrypted_doksub_id, 'type' => 'renop', 'is_renop_context' => 1, 'redirect_to' => url()->current()]) }}"
                                text="Tambah Indikator" />
                        @elseif(!$isKebijakan && ($item->is_hasilkan_indikator || $isRenopPoint))
                            {{-- Standar poin: create indikator --}}
                            @if($isRenopPoint)
                                <x-tabler.button type="create" class="btn-success"
                                    href="{{ route('pemutu.indikator.create', ['parent_dok_id' => $item->encrypted_dok_id, 'parent_doksub_id' => $item->encrypted_doksub_id, 'type' => 'renop', 'is_renop_context' => 1, 'redirect_to' => url()->current()]) }}"
                                    text="Tambah Indikator" />
                            @else
                                <x-tabler.button type="create" class="btn-success"
                                    href="{{ route('pemutu.indikator.create', ['parent_dok_id' => $item->encrypted_dok_id, 'parent_doksub_id' => $item->encrypted_doksub_id, 'type' => 'spmi', 'is_renop_context' => 0, 'redirect_to' => url()->current()]) }}"
                                    text="Tambah Indikator" />
                            @endif
                        @endif

                        {{-- Edit/Delete poin --}}
                        <x-tabler.button type="primary" class="btn-sm btn-secondary ajax-modal-btn me-0" text="" icon="ti ti-edit"
                            data-url="{{ route('pemutu.dokumen-spmi.edit', ['type' => 'poin', 'id' => $item->encrypted_doksub_id]) }}"
                            data-modal-title="Ubah Poin" />
                        <x-tabler.button type="delete" class="btn-sm ajax-delete" text="" icon="ti ti-trash"
                            data-url="{{ route('pemutu.dokumen-spmi.destroy', ['type' => 'poin', 'id' => $item->encrypted_doksub_id]) }}"
                            data-title="Hapus Poin ini?" />
                    @endif
                </div>
            </div>
        </div>
        <h2>{{ $item->judul }}</h2>

        <ul class="nav nav-tabs card-header-tabs my-2" role="tablist">
            @if($type === 'dokumen')
            <li class="nav-item" role="presentation">
                <a href="#tab-overview" class="nav-link active" data-bs-toggle="tab" role="tab" aria-selected="true">
                    <i class="ti ti-eye icon me-1"></i> Overview
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#tab-subdokumen" class="nav-link" data-bs-toggle="tab" role="tab" aria-selected="false">
                    <i class="ti ti-file-description icon me-1"></i> Poin
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#tab-informasi" class="nav-link" data-bs-toggle="tab" role="tab" aria-selected="false">
                    <i class="ti ti-info-circle icon me-1"></i> Approval Dokumen
                </a>
            </li>
            @elseif($type === 'poin')
            @if($isRenopPoint || (!$isKebijakan))
                <li class="nav-item" role="presentation">
                    <a href="{{ $isRenopPoint ? '#tab-indikator' : '#tab-subdokumen' }}" class="nav-link {{ ($isRenopPoint || !$isKebijakan || !$mappableJenis) ? 'active' : '' }}" data-bs-toggle="tab" role="tab" aria-selected="{{ ($isRenopPoint || !$isKebijakan || !$mappableJenis) ? 'true' : 'false' }}">
                        @if($isRenopPoint)
                            <i class="ti ti-target icon me-1"></i> Daftar Indikator
                        @else
                            <i class="ti ti-file-description icon me-1"></i> Dokumen Turunan
                        @endif
                    </a>
                </li>
            @endif

            @if($isKebijakan && $mappableJenis)
                <li class="nav-item" role="presentation">
                    <a href="#tab-mapping" class="nav-link {{ (!$isRenopPoint) ? 'active' : '' }}" data-bs-toggle="tab" role="tab" aria-selected="{{ (!$isRenopPoint) ? 'true' : 'false' }}">
                        <i class="ti ti-link icon me-1"></i> Mapping
                    </a>
                </li>
            @endif
            @endif
        </ul>
        <div class="tab-content">
            @if($type === 'dokumen')
            <div class="tab-pane active show" id="tab-overview" role="tabpanel">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="m-0 text-muted fw-medium">Konten Dokumen</h4>
                        <x-tabler.button type="edit" class="btn-sm btn-outline-secondary ajax-modal-btn me-0" 
                            data-url="{{ route('pemutu.dokumen-spmi.edit', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id]) }}"
                            data-modal-title="Ubah Dokumen" />
                    </div>
                    <div class="card card-body markdown">
                        {!! $item->isi ?: '<span class="text-muted fst-italic">Belum ada konten dokumen.</span>' !!}
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="m-0 text-muted fw-medium">File Pendukung</h4>
                        <x-tabler.button class="btn-sm btn-outline-secondary" icon="ti ti-upload" text="Upload" data-bs-toggle="modal" data-bs-target="#modal-upload-file" />
                    </div>
                    @if($item->getMedia('dokumen_pendukung')->count() > 0)
                    <div class="card card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-vcenter table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Nama File</th>
                                        <th>Ukuran</th>
                                        <th class="w-1">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->getMedia('dokumen_pendukung') as $media)
                                    <tr>
                                        <td>
                                            <a href="{{ $media->getUrl() }}" target="_blank" class="text-reset d-flex align-items-center">
                                                <i class="ti ti-file icon me-2"></i> {{ $media->file_name }}
                                            </a>
                                        </td>
                                        <td class="text-muted">
                                            {{ $media->human_readable_size }}
                                        </td>
                                        <td>
                                            <x-tabler.button type="delete" class="btn-sm btn-outline-danger btn-delete-file" 
                                                data-url="{{ route('pemutu.dokumen-spmi.delete-file', ['id' => $item->encrypted_dok_id, 'mediaId' => $media->id]) }}" />
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                        <div class="text-center text-muted p-3 border border-dashed rounded">
                            <i class="ti ti-files mb-2 fs-2 d-block"></i>
                            Belum ada file pendukung.
                        </div>
                    @endif
                </div>

                {{-- Modal Upload File --}}
                <x-tabler.form-modal id="modal-upload-file" title="Upload File Pendukung" method="none">
                    <input type="file" id="file-upload-input" class="filepond-input" name="filepond" multiple>
                    <x-slot:footer>
                        <x-tabler.button type="cancel" data-bs-dismiss="modal" />
                        <x-tabler.button class="btn-primary ms-auto" icon="ti ti-upload" text="Upload" id="btn-upload-file" data-url="{{ route('pemutu.dokumen-spmi.upload-file', $item->encrypted_dok_id) }}" />
                    </x-slot:footer>
                </x-tabler.form-modal>
            </div>
            @endif

            @if($type === 'dokumen')
                <div class="tab-pane" id="tab-subdokumen" role="tabpanel">
                    <div class="card bg-transparent shadow-none border">
                        <div class="card-header border-0 d-flex justify-content-between align-items-center">
                            <h2 class="card-title">Daftar {{ $childLabel ?? 'Turunan' }}</h2>
                            <x-tabler.button type="create" class="ajax-modal-btn"
                                text="Tambah {{ $childLabel ?? 'Turunan' }}"
                                data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => $isDokSubBased ? 'poin' : 'dokumen', 'parent_id' => $item->encrypted_dok_id]) }}"
                                data-modal-title="Tambah {{ $childLabel ?? 'Turunan' }}"
                                size="sm" />
                        </div>
                        <x-tabler.datatable
                            id="children-table"
                            :url="route('pemutu.dokumen-spmi.children-data', ['type' => 'dokumen', 'id' => $item->encrypted_dok_id])"
                            :columns="$childrenColumns"
                            ajax-load />
                    </div>
                </div>
            @elseif($type === 'poin')
                {{-- ============================================= --}}
                {{-- KEBIJAKAN POIN: Mapping Section --}}
                {{-- ============================================= --}}
                @if($isKebijakan && $mappableJenis)
                    <div class="tab-pane {{ (!$isRenopPoint) ? 'active show' : '' }}" id="tab-mapping" role="tabpanel">
                        <div class="card bg-transparent shadow-none border mb-3">
                            <div class="card-header border-0 d-flex justify-content-between align-items-center">
                                <h2 class="card-title">
                                    <i class="ti ti-link icon me-1"></i>
                                    Mapping ke Poin {{ pemutuJenisLabel($mappableJenis) }}
                                    <span class="badge bg-muted-lt ms-2">{{ $item->mappedTo->count() }}</span>
                                </h2>
                            </div>
                            <div class="card-body pt-0">
                                {{-- Add mapping form --}}
                                <div class="row mb-3">
                                    <div class="col">
                                        <select id="select-mapping-target" class="form-select">
                                            <option value="">-- Pilih Poin {{ pemutuJenisLabel($mappableJenis) }} --</option>
                                            @foreach($mappableOptions as $opt)
                                                @if(!$item->mappedTo->contains('doksub_id', $opt->doksub_id))
                                                <option value="{{ $opt->encrypted_doksub_id }}">
                                                    {{ $opt->seq ? "#{$opt->seq} " : '' }}{{ $opt->judul }}
                                                    {{ $opt->kode ? "({$opt->kode})" : '' }}
                                                </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-primary" id="btn-add-mapping"
                                            data-doksub-id="{{ $item->encrypted_doksub_id }}"
                                            data-url="{{ route('pemutu.dokumen-spmi.mapping-sync') }}">
                                            <i class="ti ti-link"></i> Petakan
                                        </button>
                                    </div>
                                </div>
                                {{-- Mapping list --}}
                                @if($item->mappedTo->isNotEmpty())
                                    <div class="list-group list-group-flush">
                                        @foreach($item->mappedTo as $mapped)
                                        <div class="list-group-item d-flex justify-content-between align-items-center" id="mapping-row-{{ $mapped->encrypted_doksub_id }}">
                                            <div>
                                                <div class="fw-bold">{{ $mapped->judul }}</div>
                                                <small class="text-muted">
                                                    {{ pemutuJenisLabel($mapped->dokumen->jenis ?? '') }}
                                                    {{ $mapped->kode ? "· {$mapped->kode}" : '' }}
                                                </small>
                                            </div>
                                            <button class="btn btn-sm btn-outline-danger btn-remove-mapping"
                                                data-doksub-id="{{ $item->encrypted_doksub_id }}"
                                                data-mapped-id="{{ $mapped->encrypted_doksub_id }}"
                                                data-url="{{ route('pemutu.dokumen-spmi.mapping-sync') }}">
                                                <i class="ti ti-unlink"></i> Lepas
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-muted text-center py-2">
                                        <i class="ti ti-link-off"></i> Belum ada mapping ke poin {{ pemutuJenisLabel($mappableJenis) }}.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                
                {{-- ============================================= --}}
                {{-- RENOP POIN: Indikator Section --}}
                {{-- ============================================= --}}
                @if($isRenopPoint)
                    <div class="tab-pane active show" id="tab-indikator" role="tabpanel">
                        <div class="card bg-transparent shadow-none border mb-3">
                            <div class="card-header border-0 d-flex justify-content-between align-items-center">
                                <h2 class="card-title">
                                    Daftar Indikator Renop
                                    <span class="badge bg-muted-lt ms-2">{{ $item->indikators->count() }}</span>
                                </h2>
                                @if($item->is_hasilkan_indikator)
                                <x-tabler.button type="create" class="btn-success"
                                    href="{{ route('pemutu.indikator.create', ['parent_dok_id' => $item->encrypted_dok_id, 'parent_doksub_id' => $item->encrypted_doksub_id, 'type' => 'renop', 'is_renop_context' => 1, 'redirect_to' => url()->current()]) }}"
                                    text="Tambah Indikator" size="sm" />
                                @endif
                            </div>
                            <x-tabler.datatable
                                id="indikator-table"
                                :url="route('pemutu.dokumen-spmi.children-data', ['type' => 'poin_indikator', 'id' => $item->encrypted_doksub_id])"
                                :columns="$indikatorColumns"
                                ajax-load />
                        </div>
                    </div>
                @endif

                {{-- ============================================= --}}
                {{-- NON-KEBIJAKAN POIN: Standar indikator + child docs --}}
                {{-- ============================================= --}}
                @if(!$isKebijakan)
                    <div class="tab-pane active show" id="tab-subdokumen" role="tabpanel">
                        @if($item->is_hasilkan_indikator)
                            <div class="card bg-transparent shadow-none border mb-3">
                                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                                    <h2 class="card-title">
                                        Indikator Terlampir
                                        <span class="badge bg-muted-lt ms-2">{{ $item->indikators->count() }}</span>
                                    </h2>
                                    <x-tabler.button type="create"
                                        text="Tambah Indikator"
                                        href="{{ route('pemutu.indikator.create', ['parent_dok_id' => $item->encrypted_dok_id, 'parent_doksub_id' => $item->encrypted_doksub_id, 'type' => 'spmi', 'is_renop_context' => 0, 'redirect_to' => url()->current()]) }}"
                                        size="sm" />
                                </div>
                                <x-tabler.datatable
                                    id="indikators-table"
                                    :url="route('pemutu.dokumen-spmi.children-data', ['type' => 'poin_indikator', 'id' => $item->encrypted_doksub_id])"
                                    :columns="$indikatorColumns"
                                    ajax-load />
                            </div>
                        @endif

                        @if(!$item->is_hasilkan_indikator)
                            <div class="card bg-transparent shadow-none border">
                                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                                    <h2 class="card-title">Poin ini memiliki beberapa Dokumen Turunan:</h2>
                                    <x-tabler.button type="create" class="ajax-modal-btn"
                                        text="Tambah Dokumen Turunan"
                                        data-url="{{ route('pemutu.dokumen-spmi.create', ['type' => 'dokumen', 'parent_doksub_id' => $item->encrypted_doksub_id, 'parent_id' => $item->encrypted_dok_id]) }}"
                                        data-modal-title="Tambah Dokumen Turunan" size="sm" />
                                </div>
                                <x-tabler.datatable
                                    id="poin-children-table"
                                    :url="route('pemutu.dokumen-spmi.children-data', ['type' => 'poin_dokumen', 'id' => $item->encrypted_doksub_id])"
                                    :columns="$poinChildrenColumns"
                                    ajax-load />
                            </div>
                        @endif
                    </div>
                @endif
            @endif

            <div class="tab-pane" id="tab-informasi" role="tabpanel">
                @if($type === 'dokumen')
                    <x-tabler.approval-history :approvals="$item->approvals" />
                @else
                    <x-tabler.empty-state
                        icon="ti ti-info-circle"
                        title="Tidak Ada Riwayat"
                        description="Riwayat approval hanya tersedia pada level Dokumen utama." />
                @endif

            </div>

        </div>

    </div>
</div>

{{-- File Upload AJAX Script --}}
@if($type === 'dokumen')
<script>
(function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    // Initialize FilePond when modal opens
    const uploadModal = document.getElementById('modal-upload-file');
    let pondInitialized = false;
    if (uploadModal) {
        uploadModal.addEventListener('shown.bs.modal', function() {
            if (!pondInitialized && typeof window.initFilePond === 'function') {
                window.initFilePond();
                pondInitialized = true;
            }
        });
    }

    // Upload File
    document.getElementById('btn-upload-file')?.addEventListener('click', function() {
        const inputElement = document.querySelector('#file-upload-input');
        let pond = null;
        if (window.FilePond) {
            pond = window.FilePond.find(inputElement);
        }

        if (!pond) {
            if(typeof showErrorMessage === 'function') showErrorMessage('Error', 'Sistem upload file belum siap. Silakan ulangi.');
            else alert('Sistem upload file belum siap. Silakan ulangi.');
            return;
        }

        const files = pond.getFiles();
        if (files.length === 0) {
            if(typeof showErrorMessage === 'function') showErrorMessage('Perhatian', 'Pilih minimal satu file terlebih dahulu.');
            else alert('Pilih minimal satu file terlebih dahulu.');
            return;
        }
        
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i].file);
        }
        
        const btn = this;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i> Mengunggah...';
        
        if(typeof showLoadingMessage === 'function') showLoadingMessage('Mengunggah...', 'Mohon tunggu');
        
        axios.post(this.dataset.url, formData, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(response => {
            if (response.data.success !== false) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(uploadModal);
                if (modal) modal.hide();
                if(typeof showSuccessMessage === 'function') showSuccessMessage(response.data.message || 'File berhasil diunggah');
                // Reload page to show new files
                window.location.reload();
            } else {
                if(typeof showErrorMessage === 'function') showErrorMessage('Gagal', response.data.message || 'Gagal mengunggah file.');
                else alert(response.data.message || 'Gagal mengunggah file.');
            }
        })
        .catch(err => {
            if(typeof showErrorMessage === 'function') showErrorMessage('Kesalahan', err.response?.data?.message || 'Terjadi kesalahan saat mengunggah.');
            else alert('Terjadi kesalahan saat mengunggah.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    });

    // Delete File
    document.querySelectorAll('.btn-delete-file').forEach(btn => {
        btn.addEventListener('click', function() {
            const self = this;
            if(typeof showDeleteConfirmation === 'function') {
                showDeleteConfirmation('Hapus file ini?', 'File ini akan dihapus permanen dari sistem.')
                .then((result) => {
                    if (result.isConfirmed) {
                        showLoadingMessage('Menghapus...', 'Mohon tunggu');
                        executeDeleteFile(self.dataset.url, self);
                    }
                });
            } else {
                if (!confirm('Hapus file ini?')) return;
                executeDeleteFile(self.dataset.url, self);
            }
            
            function executeDeleteFile(url, btnElement) {
                axios.delete(url, {
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                })
                .then(response => {
                    if (response.data.success !== false) {
                        if(typeof showSuccessMessage === 'function') showSuccessMessage(response.data.message || 'File berhasil dihapus.');
                        btnElement.closest('tr').remove();
                    } else {
                        if(typeof showErrorMessage === 'function') showErrorMessage('Gagal', response.data.message || 'Gagal menghapus file.');
                        else alert('Gagal menghapus file.');
                    }
                })
                .catch(err => {
                    if(typeof showErrorMessage === 'function') showErrorMessage('Kesalahan', err.response?.data?.message || 'Terjadi kesalahan saat menghapus.');
                    else alert('Terjadi kesalahan saat menghapus.');
                });
            }
        });
    });
})();
</script>
@endif

{{-- Mapping AJAX Script --}}
@if($type === 'poin' && $isKebijakan && $mappableJenis)
<script>
(function() {
    const mappingSyncUrl = @json(route('pemutu.dokumen-spmi.mapping-sync'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // Add Mapping
    document.getElementById('btn-add-mapping')?.addEventListener('click', function() {
        const select = document.getElementById('select-mapping-target');
        const mappedId = select?.value;
        if (!mappedId) return;

        if (typeof showLoadingMessage === 'function') showLoadingMessage('Memetakan...', 'Mohon tunggu');

        axios.post(mappingSyncUrl, {
            doksub_id: this.dataset.doksubId,
            mapped_doksub_id: mappedId,
            action: 'attach'
        }, {
            headers: { 'X-CSRF-TOKEN': csrfToken }
        })
        .then(response => {
            if (response.data.success !== false) {
                if(typeof showSuccessMessage === 'function') showSuccessMessage(response.data.message || 'Berhasil menyimpan mapping');
                // Reload the detail panel
                const activeLink = document.querySelector('.tree-item-link.active');
                if (activeLink) activeLink.click();
            } else {
                if(typeof showErrorMessage === 'function') showErrorMessage('Gagal', response.data.message || 'Gagal menambah mapping.');
                else alert(response.data.message || 'Gagal menambah mapping.');
            }
        })
        .catch(err => {
            if(typeof showErrorMessage === 'function') showErrorMessage('Kesalahan', err.response?.data?.message || 'Terjadi kesalahan server.');
            else alert('Terjadi kesalahan server.');
        });
    });

    // Remove Mapping
    document.querySelectorAll('.btn-remove-mapping').forEach(btn => {
        btn.addEventListener('click', function() {
            const self = this;
            if(typeof showDeleteConfirmation === 'function') {
                showDeleteConfirmation('Lepaskan mapping ini?', 'Dokumen ini tidak akan terhubung lagi dengan poin tersebut.')
                .then((result) => {
                    if (result.isConfirmed) {
                        showLoadingMessage('Melepas...', 'Mohon tunggu');
                        executeRemoveMapping(self);
                    }
                });
            } else {
                if (!confirm('Lepaskan mapping poin ini?')) return;
                executeRemoveMapping(self);
            }

            function executeRemoveMapping(btnElement) {
                axios.post(btnElement.dataset.url || mappingSyncUrl, {
                    doksub_id: btnElement.dataset.doksubId,
                    mapped_doksub_id: btnElement.dataset.mappedId,
                    action: 'detach'
                }, {
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                })
                .then(response => {
                    if (response.data.success !== false) {
                        if(typeof showSuccessMessage === 'function') showSuccessMessage(response.data.message || 'Mapping berhasil dilepas');
                        const row = document.getElementById('mapping-row-' + btnElement.dataset.mappedId);
                        if (row) row.remove();
                    } else {
                        if(typeof showErrorMessage === 'function') showErrorMessage('Gagal', response.data.message || 'Gagal melepas mapping.');
                        else alert(response.data.message || 'Gagal melepas mapping.');
                    }
                })
                .catch(err => {
                    if(typeof showErrorMessage === 'function') showErrorMessage('Kesalahan', err.response?.data?.message || 'Terjadi kesalahan sistem.');
                    else alert('Terjadi kesalahan sistem.');
                });
            }
        });
    });
})();
</script>
@endif

<script>
(function() {
    const initWorkspaceTabs = function() {
        const pointId = '{{ $item->encrypted_doksub_id ?? ($item->encrypted_dok_id ?? "doc") }}';
        const storageKey = 'activeWorkspaceTab_' + pointId;
        
        // Restore tab on load
        const activeTabId = localStorage.getItem(storageKey);
        if (activeTabId) {
            const triggerEl = document.querySelector('.nav-tabs a[href="' + activeTabId + '"]');
            if (triggerEl && typeof bootstrap !== 'undefined') {
                const tab = new bootstrap.Tab(triggerEl);
                tab.show();
            }
        }
        
        // Save tab on click
        const tabLinks = document.querySelectorAll('.nav-tabs a[data-bs-toggle="tab"]');
        tabLinks.forEach(function(link) {
            link.addEventListener('shown.bs.tab', function(e) {
                localStorage.setItem(storageKey, e.target.getAttribute('href'));
            });
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWorkspaceTabs);
    } else {
        initWorkspaceTabs();
    }
})();
</script>
