<x-tabler.form-modal
    :title="'Isi Evaluasi KPI'"
    :route="route('pemutu.evaluasi-kpi.update', $indikatorPegawai->encrypted_indikator_pegawai_id)"
    size="modal-fullscreen-md-down modal-xl" style="max-width: 1200px;"
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
                    @foreach($indikator->labels as $label)
                        <span class="badge text-bg-{{ $label->color ?? 'secondary' }}">{{ $label->name }}</span>
                    @endforeach
                    @if($indikator->labels->isEmpty())
                        <span class="text-muted smaller">Tidak ada label</span>
                    @endif
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
                <x-tabler.form-input 
                    name="attachment" 
                    label="Unggah File (Opsional)" 
                    type="file" 
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png"
                    helper="Maksimal 5MB. Format: PDF, Excel, Word, Gambar."
                />
                @if($indikatorPegawai->attachment)
                    <div class="mt-2 p-2 border rounded bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block">File Pendukung saat ini:</span>
                            <span class="fw-bold fs-5"><i class="ti ti-file-check text-success me-1"></i> File Tersedia</span>
                        </div>
                        <a href="{{ route('pemutu.evaluasi-kpi.download', $indikatorPegawai->encrypted_indikator_pegawai_id) }}" target="_blank" class="btn btn-primary btn-sm">
                            <i class="ti ti-download fs-3 me-2"></i> Unduh File Saat Ini
                        </a>
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
                                <x-tabler.button type="button" class="btn-outline-danger remove-link-btn" title="Hapus baris ini" tabindex="-1" iconOnly="true" icon="ti ti-trash" />
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
                                <x-tabler.button type="button" class="btn-outline-danger remove-link-btn" title="Hapus baris ini" tabindex="-1" iconOnly="true" icon="ti ti-trash" />
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <x-tabler.button type="button" id="add-kpi-link-btn" class="btn-outline-primary btn-sm mt-2" icon="ti ti-plus" text="Tambah Link" />
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
                toolbar: 'bold italic underline | bullist numlist | link table'
            });
        }
    }, 100);
</script>
