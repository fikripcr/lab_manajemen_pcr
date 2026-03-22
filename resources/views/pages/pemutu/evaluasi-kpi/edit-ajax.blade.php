<x-tabler.form-modal
    :title="'Isi Evaluasi KPI'"
    :route="route('pemutu.evaluasi-kpi.update', $indikatorPegawai->encrypted_indikator_pegawai_id)"
    size="modal-xl"
    method="POST"
    data-redirect="false"
>
    <div class="row">
        {{-- Kiri: Detail Indikator & Info --}}
        <div class="col-md-4 border-end pe-4">
            <h3 class="mb-3">Informasi Indikator</h3>
            
            <div class="mb-3">
                <span class="text-muted text-uppercase fw-bold fs-5">Kode</span>
                <p class="mt-1 mb-0 fs-3 fw-bold">{{ $indikator->no_indikator }}</p>
            </div>

            <div class="mb-3">
                <span class="text-muted text-uppercase fw-bold fs-5">Pegawai</span>
                <p class="mt-1 mb-0 fs-4 fw-semibold">{{ $indikatorPegawai->pegawai->nama ?? '—' }}</p>
            </div>

            <div class="mb-3">
                <span class="text-muted text-uppercase fw-bold fs-5">Sasaran / Indikator Kinerja</span>
                <p class="mt-1 mb-0 fs-3">{{ $indikator->indikator }}</p>
            </div>

            <div class="mb-3 bg-blue-lt p-3 rounded">
                <span class="text-uppercase fw-bold fs-5 text-blue">Target Capaian</span>
                <div class="fs-2 fw-bold text-blue mt-1">{{ $indikatorPegawai->target_value ?? $indikator->target ?? '(Belum ditetapkan)' }}</div>
                @if($indikatorPegawai->unit_ukuran)
                    <div class="text-muted small">Satuan: {{ $indikatorPegawai->unit_ukuran }}</div>
                @endif
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

        {{-- Kanan: Form Pengisian KPI --}}
        <div class="col-md-8 ps-4">
            <h3 class="mb-3">Pengisian Capaian KPI</h3>

            <div class="mb-3">
                <x-tabler.form-input 
                    name="realization" 
                    label="Capaian / Realisasi saat ini" 
                    placeholder="Contoh: 100%, 5 Artikel, Selesai, dsb." 
                    :value="$indikatorPegawai->realization ?? ''" 
                    required="true" 
                />
            </div>

            <div class="mb-3">
                <x-tabler.form-textarea 
                    name="kpi_analisis" 
                    label="Analisis Capaian & Tindak Lanjut" 
                    placeholder="Jelaskan analisis capaian, kendala yang dihadapi, atau upaya tindak lanjut." 
                    :value="$indikatorPegawai->kpi_analisis ?? ''" 
                    rows="4" 
                    required="true" 
                    type="editor"
                />
            </div>

            <hr class="my-4">
            <h3 class="mb-3">Bukti & Dokumen Pendukung</h3>

            <div class="mb-4">
                <h4 class="m-0 text-muted fw-medium mb-3">File Pendukung</h4>
                
                @if(isset($indikatorPegawai) && $indikatorPegawai->exists && $indikatorPegawai->getMedia('kpi_attachments')->count() > 0)
                    <div class="list-group list-group-flush border mb-3 rounded">
                        @foreach($indikatorPegawai->getMedia('kpi_attachments') as $media)
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                            <div class="text-truncate me-2" title="{{ $media->file_name }}">
                                <a href="{{ $media->getUrl() }}" target="_blank" class="text-reset d-flex align-items-center text-truncate">
                                    <i class="ti ti-file icon me-2 fs-3 text-muted"></i>
                                    <span class="text-truncate">{{ $media->file_name }}</span>
                                </a>
                                <div class="text-muted small mt-1 ms-4">{{ $media->human_readable_size }}</div>
                            </div>
                            <div class="text-nowrap ms-auto">
                                <x-tabler.button type="delete" class="btn-sm btn-outline-danger btn-delete-file-kpi py-1 px-2" iconOnly="true" 
                                    data-url="{{ route('pemutu.evaluasi-kpi.delete-file', ['indikatorPegawai' => $indikatorPegawai->encrypted_indikator_pegawai_id, 'mediaId' => $media->id]) }}" />
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                @if(isset($indikatorPegawai) && $indikatorPegawai->exists)
                <div class="mt-2">
                    <input type="file" id="file-upload-input-kpi" class="filepond-input" name="filepond[]" multiple>
                </div>
                @else
                <div class="text-center text-muted p-3 border border-dashed rounded mt-2">
                    <i class="ti ti-device-floppy mb-2 fs-2 d-block"></i>
                    Simpan data awal terlebih dahulu untuk mengunggah file.
                </div>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Link Pendukung Eksternal (URL)</label>
                <div class="text-muted mb-2"><small>Tambahkan tautan ke Google Drive, Sharepoint, Website, dll.</small></div>
                
                <div id="kpi-links-container">
                    @forelse($edLinks as $link)
                        <div class="kpi-link-item row gap-2 mb-2 g-0 align-items-center">
                            <div class="col-5">
                                <x-tabler.form-input type="text" name="kpi_links_name[]" placeholder="Nama Dokumen (Contoh: Laporan PKM)" value="{{ $link['name'] }}" />
                            </div>
                            <div class="col">
                                <x-tabler.form-input type="url" name="kpi_links_url[]" placeholder="https://..." value="{{ $link['url'] }}" />
                            </div>
                            <div class="col-auto">
                                <x-tabler.button type="delete" class="btn-outline-danger remove-link-btn" title="Hapus baris ini" tabindex="-1" iconOnly="true" />
                            </div>
                        </div>
                    @empty
                        <div class="kpi-link-item row gap-2 mb-2 g-0 align-items-center">
                            <div class="col-5">
                                <x-tabler.form-input type="text" name="kpi_links_name[]" placeholder="Nama Dokumen (Contoh: Laporan PKM)" />
                            </div>
                            <div class="col">
                                <x-tabler.form-input type="url" name="kpi_links_url[]" placeholder="https://..." />
                            </div>
                            <div class="col-auto">
                                <x-tabler.button type="delete" class="btn-outline-danger remove-link-btn" title="Hapus baris ini" tabindex="-1" iconOnly="true" />
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <x-tabler.button type="create" id="add-kpi-link-btn" class="btn-outline-primary btn-sm mt-2" text="Tambah Link" />
            </div>
        </div>
    </div>
</x-tabler.form-modal>



<script>
    setTimeout(() => {
        const container = document.getElementById('kpi-links-container');
        const addBtn = document.getElementById('add-kpi-link-btn');

        if(container && addBtn) {
            addBtn.addEventListener('click', () => {
                const newItem = document.createElement('div');
                newItem.className = 'kpi-link-item row gap-2 mb-2 g-0 align-items-center';
                newItem.innerHTML = `
                    <div class="col-5">
                        <input type="text" name="kpi_links_name[]" class="form-control" placeholder="Nama Dokumen (Contoh: Laporan PKM)">
                    </div>
                    <div class="col">
                        <input type="url" name="kpi_links_url[]" class="form-control" placeholder="https://...">
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
                    e.target.closest('.kpi-link-item').remove();
                }
            });
        }

        if (window.loadHugeRTE) {
            window.loadHugeRTE('#kpi_analisis', {
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

        document.querySelectorAll('.btn-delete-file-kpi').forEach(btn => {
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
