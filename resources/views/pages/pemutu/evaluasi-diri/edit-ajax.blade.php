@php $isReadonly = request('readonly') == 1; @endphp
<x-tabler.form-modal
    :title="$isReadonly ? 'Detail Evaluasi Diri' : 'Isi Evaluasi Diri'"
    :route="route('pemutu.evaluasi-diri.update', $indikator->encrypted_indikator_id)"
    size="modal-xl"
    :method="$isReadonly ? 'none' : 'POST'"
    data-redirect="false"
>
<fieldset {{ $isReadonly ? 'disabled' : '' }}>
    <div class="row">
        {{-- Kiri: Detail Indikator & Info --}}
        <div class="col-md-4 border-end pe-4 d-flex flex-column" style="max-height: 70vh; overflow-y: auto;">
            <div class="mb-3">
                <span class="text-muted text-uppercase fw-bold fs-5">Pernyataan Standar / Indikator</span>
                <p class="mt-1 mb-0 fs-3">{{ !empty($indikator->no_indikator) ? $indikator->no_indikator . ' - ' : '' }}{{ $indikator->indikator }}</p>
                @if($indikator->keterangan)
                    <div class="mt-2 text-muted bg-light p-2 rounded small border-start border-3 border-info">
                        {!! $indikator->keterangan !!}
                    </div>
                @endif
            </div>


            <div class="mb-3 bg-blue-lt p-3 rounded">
                <span class="text-uppercase fw-bold fs-5 text-blue">Target Capaian</span>
                <div class="fs-2 fw-bold text-blue mt-1">{{ $pivot->target ?? '(Belum ditetapkan)' }}</div>
                
                @if(isset($orgUnit))
                <div class="mt-3 border-top border-blue pt-2 border-opacity-25">
                    <span class="text-uppercase fw-bold fs-5 text-blue">Target Unit Divisi</span>
                    <div class="fs-4 fw-medium text-blue">{{ $orgUnit->nama_unit }}</div>
                </div>
                @endif
            </div>

            @if(!empty($indukDokumenTree))
            <div class="mb-4">
                <span class="text-muted text-uppercase fw-bold fs-5">Dokumen Standar</span>
                <div class="mt-1 border p-0 rounded overflow-hidden">
                    <div class="list-group list-group-flush small">
                        @foreach($indukDokumenTree as $docNode)
                            <div class="list-group-item px-3 py-2 {{ $loop->last ? 'bg-light fw-medium list-group-item-light text-dark' : 'text-muted' }}">
                                @if(isset($docNode['dok_id']) && $docNode['type'] === 'dokumen')
                                    <a href="{{ route('pemutu.dokumen-spmi.show', ['type' => 'standar', 'id' => encryptId($docNode['dok_id'])]) }}" target="_blank" class="text-decoration-none d-flex align-items-center">
                                        {{ !empty($docNode['kode']) ? $docNode['kode'] . ' - ' : '' }}{{ $docNode['judul'] }}
                                        <i class="ti ti-external-link ms-auto text-muted" style="width:14px;height:14px;"></i>
                                    </a>
                                @else
                                    <span class="d-flex align-items-start">
                                        <i class="ti ti-arrow-forward me-1 mt-1 opacity-50"></i>
                                        <span>{{ !empty($docNode['kode']) ? $docNode['kode'] . ' - ' : '' }}{{ $docNode['judul'] }}</span>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            
            @if(isset($renstraPoin) && $renstraPoin)
            <div class="mb-3">
                <span class="text-muted text-uppercase fw-bold fs-5">Dokumen Terkait</span>
                <div class="mt-1 bg-light p-2 rounded small border">
                    <a href="{{ route('pemutu.dokumen-spmi.show', ['type' => 'renstra', 'id' => encryptId($renstraPoin->dok_id)]) }}" target="_blank" class="text-decoration-none fw-bold d-flex align-items-center">
                        <i class="ti ti-external-link me-1"></i>
                        {{ $renstraPoin->dokumen->judul ?? 'Renstra' }}
                    </a>
                    <div class="mt-1 text-dark">
                        {{ $renstraPoin->judul }}
                    </div>
                </div>
            </div>
            @endif

            
        </div>

        {{-- Kanan: Form Pengisian ED --}}
        <div class="col-md-8 ps-4 pe-2 d-flex flex-column" style="max-height: 70vh;">
            <input type="hidden" name="target_unit_id" value="{{ $targetUnitId }}">

            {{-- Tabs Navigation --}}
            <ul class="nav nav-tabs mb-3 flex-shrink-0" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-capaian" data-bs-toggle="tab" data-bs-target="#panel-capaian" type="button" role="tab">
                        <i class="ti ti-clipboard-check me-1"></i>Pengisian Capaian
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-bukti" data-bs-toggle="tab" data-bs-target="#panel-bukti" type="button" role="tab">
                        <i class="ti ti-files me-1"></i>Bukti & Dokumen Pendukung
                    </button>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content flex-grow-1 overflow-auto pe-2" style="margin-right: -0.5rem;">
                {{-- Tab 1: Pengisian Capaian --}}
                <div class="tab-pane fade show active" id="panel-capaian" role="tabpanel">
                    <div class="mb-3">
                        <x-tabler.form-input
                            name="ed_capaian"
                            label="Capaian saat ini"
                            placeholder="Contoh: 100%, 5 Dokumen, Selesai, dsb."
                            :value="$pivot->ed_capaian ?? ''"
                            required="true"
                        />
                    </div>

                    <div class="mb-3">
                        <x-tabler.form-textarea
                            name="ed_analisis"
                            label="Analisis Capaian & Tindak Lanjut"
                            placeholder="Jelaskan analisis capaian, kendala yang dihadapi, atau upaya tindak lanjut."
                            :value="$pivot->ed_analisis ?? ''"
                            rows="4"
                            required="true"
                        />
                    </div>

                    @php $skalaData = $indikator->skala ?? []; @endphp
                    @if(!empty($skalaData))
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-2 d-flex align-items-center">
                            <i class="ti ti-stairs-up me-2 text-primary fs-3"></i>
                            Penilaian Skala Capaian
                        </label>
                        
                        <input type="hidden" name="ed_skala" id="ed-skala-value" value="{{ $pivot->ed_skala ?? '' }}">
                        
                        <div class="d-flex flex-column gap-1">
                            @foreach($skalaData as $level => $desc)
                                @php 
                                    $isChosen = (isset($pivot->ed_skala) && (int)$pivot->ed_skala === (int)$level); 
                                    $color = match((int)$level) {
                                        4 => 'success',
                                        3 => 'blue',
                                        2 => 'warning',
                                        1 => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <div class="skala-choice-card cursor-pointer px-3 py-2 rounded-2 border transition-all d-flex align-items-start gap-2 {{ $isChosen ? 'border-'.$color.' bg-'.$color.'-lt shadow-sm active' : 'border-light bg-white opacity-75' }}"
                                     data-level="{{ $level }}" data-color="{{ $color }}" role="button">
                                    <div class="skala-number fs-3 fw-bold {{ $isChosen ? 'text-'.$color : 'text-muted' }}" style="min-width: 28px; text-align: center; line-height: 1.4;">
                                        {{ $level }}
                                    </div>
                                    <div class="skala-desc-text flex-grow-1 small lh-sm {{ $isChosen ? 'text-dark fw-medium' : 'text-muted' }}" style="padding-top: 2px;">
                                        {!! $desc !!}
                                    </div>
                                    <div class="skala-check-icon ms-auto {{ $isChosen ? '' : 'd-none' }}" style="padding-top: 2px;">
                                        <i class="ti ti-circle-check-filled text-{{ $color }} fs-3"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Tab 2: Bukti & Dokumen Pendukung --}}
                <div class="tab-pane fade" id="panel-bukti" role="tabpanel">
                    <div class="row">
                        <div class="col-md-5 border-end pe-4">
                            <div class="mb-4">
                                <h4 class="m-0 text-muted fw-medium mb-3">File Pendukung</h4>
                                
                                @if(isset($pivot) && $pivot->exists && $pivot->getMedia('ed_attachments')->count() > 0)
                                    <div class="list-group list-group-flush border mb-3 rounded">
                                        @foreach($pivot->getMedia('ed_attachments') as $media)
                                        <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                            <div class="text-truncate me-2" title="{{ $media->file_name }}">
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="text-reset d-flex align-items-center text-truncate">
                                                    <i class="ti ti-file icon me-2 fs-3 text-muted"></i>
                                                    <span class="text-truncate">{{ $media->file_name }}</span>
                                                </a>
                                                <div class="text-muted small mt-1 ms-4">{{ $media->human_readable_size }}</div>
                                            </div>
                                            <div class="text-nowrap ms-auto">
                                                <button type="button" class="btn btn-icon btn-sm btn-outline-danger btn-delete-file-ed" title="Hapus File" 
                                                    data-url="{{ route('pemutu.evaluasi-diri.delete-file', ['id' => encryptId($pivot->indikorgunit_id), 'mediaId' => $media->id]) }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if(isset($pivot) && $pivot->exists)
                                    <div class="mt-2">
                                        <input type="file" id="file-upload-input-ed" class="filepond-input" name="filepond[]" multiple>
                                    </div>
                                @else
                                    <div class="text-center text-muted p-3 border border-dashed rounded mt-2">
                                        <i class="ti ti-device-floppy mb-2 fs-2 d-block"></i>
                                        Simpan data awal terlebih dahulu untuk mengunggah file.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-7 ps-4">
                            <div class="mb-3">
                                <label class="form-label">Link Pendukung Eksternal (URL)</label>
                                <div class="text-muted mb-2"><small>Tambahkan tautan ke Google Drive, Sharepoint, Website, dll.</small></div>

                                <div id="ed-links-container">
                                    @forelse($edLinks as $link)
                                        <div class="ed-link-item row gap-2 mb-2 g-0 align-items-center">
                                            <div class="col-5">
                                                <x-tabler.form-input type="text" name="ed_links_name[]" placeholder="Nama Dokumen (Contoh: Laporan PKM)" value="{{ $link['name'] }}" />
                                            </div>
                                            <div class="col">
                                                <x-tabler.form-input type="url" name="ed_links_url[]" placeholder="https://..." value="{{ $link['url'] }}" />
                                            </div>
                                            <div class="col-auto">
                                                <button type="button" class="btn btn-icon btn-outline-danger remove-link-btn" title="Hapus baris ini" tabindex="-1">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="ed-link-item row gap-2 mb-2 g-0 align-items-center">
                                            <div class="col-5">
                                                <x-tabler.form-input type="text" name="ed_links_name[]" placeholder="Nama Dokumen (Contoh: Laporan PKM)" />
                                            </div>
                                            <div class="col">
                                                <x-tabler.form-input type="url" name="ed_links_url[]" placeholder="https://..." />
                                            </div>
                                            <div class="col-auto">
                                                <button type="button" class="btn btn-icon btn-outline-danger remove-link-btn" title="Hapus baris ini" tabindex="-1">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>

                                <x-tabler.button type="create" id="add-link-btn" class="btn-outline-primary btn-sm mt-2" text="Tambah Link" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>
</x-tabler.form-modal>



<script>
    setTimeout(() => {
        const container = document.getElementById('ed-links-container');
        const addBtn = document.getElementById('add-link-btn');

        if(container && addBtn) {
            addBtn.addEventListener('click', () => {
                const newItem = document.createElement('div');
                newItem.className = 'ed-link-item row gap-2 mb-2 g-0 align-items-center';
                newItem.innerHTML = `
                    <div class="col-5">
                        <input type="text" name="ed_links_name[]" class="form-control" placeholder="Nama Dokumen (Contoh: Laporan PKM)">
                    </div>
                    <div class="col">
                        <input type="url" name="ed_links_url[]" class="form-control" placeholder="https://...">
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-icon btn-outline-danger remove-link-btn" title="Hapus baris ini" tabindex="-1">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                `;
                container.appendChild(newItem);
            });

            container.addEventListener('click', (e) => {
                if (e.target.closest('.remove-link-btn')) {
                    e.target.closest('.ed-link-item').remove();
                }
            });
        }

        // Skala card interactive selection
        const isReadonly = {{ $isReadonly ? 'true' : 'false' }};
        const skalaCards = document.querySelectorAll('.skala-choice-card');
        const skalaInput = document.getElementById('ed-skala-value');
        
        if (skalaCards.length && skalaInput && !isReadonly) {
            skalaCards.forEach(card => {
                card.addEventListener('click', () => {
                    const level = card.dataset.level;
                    const color = card.dataset.color;
                    skalaInput.value = level;

                    // Reset all cards
                    skalaCards.forEach(c => {
                        c.className = 'skala-choice-card cursor-pointer px-3 py-2 rounded-2 border transition-all d-flex align-items-start gap-2 border-light bg-white opacity-75';
                        const num = c.querySelector('.skala-number');
                        if (num) num.className = 'skala-number fs-3 fw-bold text-muted';
                        const txt = c.querySelector('.skala-desc-text');
                        if (txt) txt.className = 'skala-desc-text flex-grow-1 small lh-sm text-muted';
                        const icon = c.querySelector('.skala-check-icon');
                        if (icon) icon.classList.add('d-none');
                    });

                    // Set active card
                    card.className = `skala-choice-card cursor-pointer px-3 py-2 rounded-2 border transition-all d-flex align-items-start gap-2 border-${color} bg-${color}-lt shadow-sm active`;
                    const activeNum = card.querySelector('.skala-number');
                    if (activeNum) activeNum.className = `skala-number fs-3 fw-bold text-${color}`;
                    const activeTxt = card.querySelector('.skala-desc-text');
                    if (activeTxt) activeTxt.className = 'skala-desc-text flex-grow-1 small lh-sm text-dark fw-medium';
                    const activeIcon = card.querySelector('.skala-check-icon');
                    if (activeIcon) activeIcon.classList.remove('d-none');
                });
            });
        }

        if (window.loadHugeRTE) {
            window.loadHugeRTE('#ed_analisis', {
                height: 200, menubar: false, statusbar: false,
                plugins: 'lists link table',
                toolbar: 'bold italic underline | bullist numlist | link table',
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                    });
                }
            });
        }


        // FilePond initialization & actions
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (typeof window.initFilePond === 'function') {
            window.initFilePond();
        }

        document.querySelectorAll('.btn-delete-file-ed').forEach(btn => {
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
                    axios.delete(url, { headers: { 'X-CSRF-TOKEN': csrfToken } })
                    .then(response => {
                        if (response.data.success !== false) {
                            if(typeof showSuccessMessage === 'function') showSuccessMessage(response.data.message || 'File berhasil dihapus.');
                            btnElement.closest('.list-group-item').remove();
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

    }, 100);
</script>

