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
        <div class="col-md-4 border-end pe-4" style="max-height: 70vh; overflow-y: auto;">
            <h3 class="mb-3">Informasi Indikator</h3>

            <div class="mb-3">
                <span class="text-muted text-uppercase fw-bold fs-5">Kode</span>
                <p class="mt-1 mb-0 fs-3 fw-bold">{{ $indikator->no_indikator }}</p>
            </div>

            <div class="mb-3">
                <span class="text-muted text-uppercase fw-bold fs-5">Pernyataan Standar / Indikator</span>
                <p class="mt-1 mb-0 fs-3">{{ $indikator->indikator }}</p>
                @if($indikator->keterangan)
                    <div class="mt-2 text-muted bg-light p-2 rounded small border-start border-3 border-info">
                        {!! $indikator->keterangan !!}
                    </div>
                @endif
            </div>

            <div class="mb-3 bg-blue-lt p-3 rounded">
                <span class="text-uppercase fw-bold fs-5 text-blue">Target Capaian</span>
                <div class="fs-2 fw-bold text-blue mt-1">{{ $pivot->target ?? '(Belum ditetapkan)' }}</div>
            </div>

            <div class="mb-4">
                <span class="text-muted text-uppercase fw-bold fs-5">Label</span>
                <div class="mt-1">
                    {!! pemutuDtColLabelsList($indikator) !!}
                </div>
            </div>

            <hr>

            <div class="mb-3">
                <span class="text-muted text-uppercase fw-bold fs-5">Hierarki Indikator</span>
                <ol class="breadcrumb breadcrumb-arrows fst-italic mt-1 mb-0" aria-label="breadcrumbs">
                    @foreach($breadcrumbs as $b)
                        <li class="breadcrumb-item {{ $loop->last ? 'active fw-bold' : '' }}">
                            {{ $b['current']->no_indikator ?? '-' }}
                        </li>
                    @endforeach
                </ol>
            </div>

            @if(!empty($indukDokumenTree))
            <div class="mb-3 mt-3">
                <span class="text-muted text-uppercase fw-bold fs-5">Induk Dokumen Terkait</span>
                <ol class="breadcrumb breadcrumb-arrows fst-italic mt-1 mb-0" aria-label="breadcrumbs">
                    @foreach($indukDokumenTree as $docNode)
                        <li class="breadcrumb-item {{ $loop->last ? 'active fw-bold' : '' }}">
                            {{ !empty($docNode['kode']) ? $docNode['kode'] . ' - ' : '' }}{{ $docNode['judul'] }}
                        </li>
                    @endforeach
                </ol>
            </div>
            @endif
        </div>

        {{-- Kanan: Form Pengisian ED --}}
        <div class="col-md-8 ps-4 pe-2" style="max-height: 70vh; overflow-y: auto; overflow-x: hidden;">
            <h3 class="mb-3">Pengisian Capaian</h3>
            <input type="hidden" name="target_unit_id" value="{{ $targetUnitId }}">

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

            {{-- Penilaian Skala (jika indikator punya skala) --}}
            @php $skalaData = $indikator->skala ?? []; @endphp
            @if(!empty($skalaData))
            <div class="mb-3">
                <label class="form-label fw-semibold">Penilaian Skala Capaian</label>
                <p class="text-muted small mb-2">Pilih level yang paling sesuai dengan capaian unit Anda.</p>
                <input type="hidden" name="ed_skala" id="ed-skala-value" value="{{ $pivot->ed_skala ?? '' }}">
                <div class="row g-2">
                    @foreach($skalaData as $level => $desc)
                    @php $isChosen = (isset($pivot->ed_skala) && (int)$pivot->ed_skala === (int)$level); @endphp
                    <div class="col-12">
                        <x-tabler.card class="mb-0 skala-card border cursor-pointer {{ $isChosen ? 'border-primary bg-primary-lt border-2' : 'border' }}"
                             data-level="{{ $level }}" role="button">
                            <x-tabler.card-body class="p-2">
                                <div class="row align-items-center">
                                    <div class="col-auto pe-3 border-end">
                                        <div class="fs-2 fw-bold {{ $isChosen ? 'text-primary' : 'text-muted' }} mb-0">{{ $level }}</div>
                                    </div>
                                    <div class="col ps-3">
                                        <div class="skala-desc small {{ $isChosen ? 'text-primary fw-semibold' : 'text-muted' }}">
                                            {!! $desc !!}
                                        </div>
                                    </div>
                                </div>
                            </x-tabler.card-body>
                        </x-tabler.card>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif


            <hr class="my-4">
            <h3 class="mb-3">Bukti & Dokumen Pendukung</h3>

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
                                        <x-tabler.button type="delete" class="btn-sm btn-outline-danger btn-delete-file-ed py-1 px-2" iconOnly="true" 
                                            data-url="{{ route('pemutu.evaluasi-diri.delete-file', ['id' => encryptId($pivot->indikorgunit_id), 'mediaId' => $media->id]) }}" />
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
                                        <x-tabler.button type="delete" class="btn-outline-danger remove-link-btn" title="Hapus baris ini" tabindex="-1" iconOnly="true" />
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
                                        <x-tabler.button type="delete" class="btn-outline-danger remove-link-btn" title="Hapus baris ini" tabindex="-1" iconOnly="true" />
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
        const skalaCards = document.querySelectorAll('.skala-card');
        const skalaInput = document.getElementById('ed-skala-value');
        if (skalaCards.length && skalaInput) {
            skalaCards.forEach(card => {
                card.addEventListener('click', () => {
                    const level = card.dataset.level;
                    skalaInput.value = level;

                    // Reset all cards
                    skalaCards.forEach(c => {
                        c.classList.remove('border-primary', 'bg-primary-lt', 'border-2');
                        c.classList.add('border');
                        const num = c.querySelector('.fs-1');
                        const lbl = c.querySelector('.skala-desc');
                        if (num) { num.classList.remove('text-primary'); num.classList.add('text-muted'); }
                        if (lbl) { lbl.classList.remove('text-primary', 'fw-semibold'); lbl.classList.add('text-muted'); }
                    });

                    // Highlight selected
                    card.classList.add('border-primary', 'bg-primary-lt', 'border-2');
                    card.classList.remove('border');
                    const num = card.querySelector('.fs-1');
                    const lbl = card.querySelector('.skala-desc');
                    if (num) { num.classList.add('text-primary'); num.classList.remove('text-muted'); }
                    if (lbl) { lbl.classList.add('text-primary', 'fw-semibold'); lbl.classList.remove('text-muted'); }
                });
            });
        }

        if (window.loadHugeRTE) {
            window.loadHugeRTE('#ed_analisis', {
                height: 250, menubar: false, statusbar: false,
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

