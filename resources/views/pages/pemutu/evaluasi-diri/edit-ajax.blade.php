<x-tabler.form-modal
    id="edit-ed-modal"
    :title="'Isi Evaluasi Diri'"
    :route="route('pemutu.evaluasi-diri.update', $indikator->encrypted_indikator_id)"
    size="modal-lg" 
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
                <span class="text-muted text-uppercase fw-bold fs-5">Pernyataan Standar / Indikator</span>
                <p class="mt-1 mb-0 fs-3">{{ $indikator->indikator }}</p>
            </div>

            <div class="mb-3 bg-blue-lt p-3 rounded">
                <span class="text-uppercase fw-bold fs-5 text-blue">Target Capaian</span>
                <div class="fs-2 fw-bold text-blue mt-1">{{ $pivot->target ?? '(Belum ditetapkan)' }}</div>
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

        {{-- Kanan: Form Pengisian ED --}}
        <div class="col-md-8 ps-4">
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
                        <div class="card mb-0 skala-card cursor-pointer {{ $isChosen ? 'border-primary bg-primary-lt border-2' : 'border' }}"
                             data-level="{{ $level }}" role="button">
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-auto pe-3 border-end">
                                        <div class="fs-1 fw-bold {{ $isChosen ? 'text-primary' : 'text-muted' }} mb-0">{{ $level }}</div>
                                    </div>
                                    <div class="col ps-3">
                                        <div class="skala-desc {{ $isChosen ? 'text-primary fw-semibold' : 'text-muted' }}">
                                            {!! $desc !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif


            <hr class="my-4">
            <h3 class="mb-3">Bukti & Dokumen Pendukung</h3>

            <div class="mb-4">
                <x-tabler.form-input 
                    name="ed_attachment" 
                    label="Unggah File (Opsional)" 
                    type="file" 
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png"
                    helper="Maksimal 5MB. Format: PDF, Excel, Word, Gambar."
                />
                @if(isset($pivot) && $pivot->ed_attachment)
                    <div class="mt-2 p-2 border rounded bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block">File Pendukung saat ini:</span>
                            <span class="fw-bold fs-5"><i class="ti ti-file-check text-success me-1"></i> File Tersedia</span>
                          <a href="{{ route('pemutu.evaluasi-diri.download', encryptId($pivot->indikorgunit_id)) }}" target="_blank" class="btn btn-primary btn-sm">
                            <i class="ti ti-download fs-3 me-2"></i> Unduh File Saat Ini
                        </a>
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Link Pendukung Eksternal (URL)</label>
                <div class="text-muted mb-2"><small>Tambahkan tautan ke Google Drive, Sharepoint, Website, dll.</small></div>
                
                <div id="ed-links-container">
                    @forelse($edLinks as $link)
                        <div class="ed-link-item row gap-2 mb-2 g-0 align-items-center">
                            <div class="col-5">
                                <input type="text" name="ed_links_name[]" class="form-control" placeholder="Nama Dokumen (Contoh: Laporan PKM)" value="{{ $link['name'] }}">
                            </div>
                            <div class="col">
                                <input type="url" name="ed_links_url[]" class="form-control" placeholder="https://..." value="{{ $link['url'] }}">
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
                        </div>
                    @endforelse
                </div>
                
                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-link-btn">
                    <i class="ti ti-plus me-2"></i> Tambah Link
                </button>
            </div>
        </div>
    </div>
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
    }, 100);
</script>

