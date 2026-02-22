<div class="card mb-1 py-2 px-2 m-2 card-pertanyaan border-0 shadow-sm" data-id="{{ $pertanyaan->encrypted_pertanyaan_id }}"
     style="border-left: 4px solid var(--tblr-primary) !important;">
    <div class="card-body py-1 px-3">
        <div class="row align-items-start g-2">
            <div class="col-auto drag-handle cursor-move text-muted pt-1" title="Drag untuk mengatur urutan">
                <i class="ti ti-grip-vertical"></i>
            </div>

            <div class="col">
                {{-- Static View (Label + Text) --}}
                <div class="static-view-{{ $pertanyaan->encrypted_pertanyaan_id }}">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary-lt question-number-badge">Soal #{{ $pertanyaan->urutan }}</span>
                            <span class="badge bg-secondary-lt text-uppercase" style="font-size: 0.65rem;">
                                {{ str_replace('_', ' ', $pertanyaan->tipe) }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-primary border-0" title="Edit"
                                    onclick="$('.static-view-{{ $pertanyaan->encrypted_pertanyaan_id }}').addClass('d-none'); $('.edit-view-{{ $pertanyaan->encrypted_pertanyaan_id }}').removeClass('d-none');">
                                <i class="ti ti-pencil icon"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-danger border-0" title="Hapus" onclick="deletePertanyaan('{{ $pertanyaan->encrypted_pertanyaan_id }}')">
                                <i class="ti ti-trash icon"></i>
                            </button>
                        </div>

                    </div>
                    <div class="fw-bold text-dark fs-3 mb-1">{{ $pertanyaan->teks_pertanyaan }}</div>
                    @if($pertanyaan->bantuan_teks)
                        <div class="text-muted small">{{ $pertanyaan->bantuan_teks }}</div>
                    @endif

                    {{-- Quick Preview of Options (if any) --}}
                    @if($pertanyaan->opsi->count() > 0)
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach($pertanyaan->opsi as $o)
                                <span class="badge badge-outline text-muted fw-normal">{{ $o->label }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Edit View (Inputs) - Hidden by default --}}
                <div class="edit-view-{{ $pertanyaan->encrypted_pertanyaan_id }} d-none">
                    <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                         <div class="fw-bold text-primary edit-view-title">Edit Soal #{{ $pertanyaan->urutan }}</div>
                         <x-tabler.button class="btn-primary" onclick="window.savePertanyaan('{{ $pertanyaan->encrypted_pertanyaan_id }}')" icon="ti ti-check" text="Selesai" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Teks Pertanyaan</label>
                        <input type="text" class="form-control pertanyaan-teks-input shadow-none" 
                               value="{{ $pertanyaan->teks_pertanyaan }}" 
                               onchange="debounceSave('{{ $pertanyaan->encrypted_pertanyaan_id }}')"
                               placeholder="Tulis Pertanyaan Disini">
                    </div>
                    
                    <div class="row g-3 align-items-end mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Tipe Soal</label>
                            <select class="form-select pertanyaan-tipe-select shadow-none" 
                                    onchange="onTypeChange('{{ $pertanyaan->encrypted_pertanyaan_id }}', this)">
                                @foreach(['Teks_Singkat', 'Esai', 'Angka', 'Pilihan_Ganda', 'Kotak_Centang', 'Dropdown', 'Skala_Linear', 'Tanggal', 'Upload_File'] as $tipe)
                                    <option value="{{ $tipe }}" {{ $pertanyaan->tipe == $tipe ? 'selected' : '' }}>
                                        {{ str_replace('_', ' ', $tipe) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label small fw-bold text-muted text-uppercase">Pengaturan</div>
                            <label class="form-check form-switch m-0 py-1">
                                <input class="form-check-input pertanyaan-wajib-check" type="checkbox" 
                                       {{ $pertanyaan->wajib_diisi ? 'checked' : '' }}
                                       onchange="debounceSave('{{ $pertanyaan->encrypted_pertanyaan_id }}')">
                                <span class="form-check-label text-muted">Wajib Diisi</span>
                            </label>
                        </div>
                    </div>

                    <!-- Branching Logic for Question (Default Next) -->
                    @if($pertanyaan->survei->mode === 'Bercabang')
                    <div class="mb-3 bg-light p-2 rounded">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-1">Setelah soal ini selesai, lanjut ke:</label>
                        <select class="form-select form-select-sm pertanyaan-next-select shadow-none" onchange="debounceSave('{{ $pertanyaan->encrypted_pertanyaan_id }}')">
                            <option value="">(Ikuti Urutan / Selesai)</option>
                            @foreach($allPertanyaan->where('pertanyaan_id', '!=', $pertanyaan->pertanyaan_id) as $p)
                                <option value="{{ $p->pertanyaan_id }}" {{ $pertanyaan->next_pertanyaan_id == $p->pertanyaan_id ? 'selected' : '' }}>
                                    Soal {{ $p->urutan }}: {{ Str::limit($p->teks_pertanyaan, 40) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif


                    <!-- Option Area (Rendered conditionally based on type) -->
                    @if(in_array($pertanyaan->tipe, ['Pilihan_Ganda', 'Kotak_Centang', 'Dropdown']))
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Opsi Jawaban</label>
                        <div class="option-list-{{ $pertanyaan->encrypted_pertanyaan_id }} mb-2">
                            @foreach($pertanyaan->opsi as $opsi)
                                <div class="input-group input-group-sm mb-1 opsi-item border-0" data-id="{{ $opsi->encrypted_opsi_id }}">
                                    <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-circle"></i></span>
                                    <input type="text" class="form-control opsi-label shadow-none border-start-0"
                                           value="{{ $opsi->label }}" onchange="debounceSave('{{ $pertanyaan->encrypted_pertanyaan_id }}')">
                                    @if($pertanyaan->survei->mode === 'Bercabang' && in_array($pertanyaan->tipe, ['Pilihan_Ganda', 'Dropdown']))
                                        <select class="form-select flex-grow-0 opsi-next-select shadow-none" style="width: 130px;" onchange="debounceSave('{{ $pertanyaan->encrypted_pertanyaan_id }}')">
                                            <option value="">Lanjut...</option>
                                            @foreach($allPertanyaan->where('pertanyaan_id', '!=', $pertanyaan->pertanyaan_id) as $p)
                                                <option value="{{ $p->encrypted_pertanyaan_id }}" {{ $opsi->next_pertanyaan_id == $p->pertanyaan_id ? 'selected' : '' }}>
                                                    Lompat: {{ $p->urutan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif

                                    <button type="button" class="btn btn-sm btn-icon btn-ghost-danger" title="Hapus Opsi"
                                            onclick="$(this).closest('.opsi-item').remove(); debounceSave('{{ $pertanyaan->encrypted_pertanyaan_id }}');">
                                        <i class="ti ti-x icon"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <x-tabler.button class="btn-ghost-primary" onclick="addOpsi('{{ $pertanyaan->encrypted_pertanyaan_id }}')" icon="ti ti-plus" text="Tambah Opsi" />
                    </div>
                    @endif

                    <!-- Scale Config (for Skala_Linear) -->
                    @if($pertanyaan->tipe === 'Skala_Linear')
                    <div class="mb-3 bg-light p-2 rounded">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">Konfigurasi Skala</label>
                        <div class="row g-2">
                            <div class="col-3">
                                <label class="form-label small mb-1">Min</label>
                                <input type="number" class="form-control form-control-sm" value="{{ $pertanyaan->config_json['min'] ?? 1 }}" readonly>
                            </div>
                            <div class="col-3">
                                <label class="form-label small mb-1">Max</label>
                                <input type="number" class="form-control form-control-sm" value="{{ $pertanyaan->config_json['max'] ?? 5 }}" readonly>
                            </div>
                            <div class="col-3">
                                <label class="form-label small mb-1">Label Min</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $pertanyaan->config_json['label_min'] ?? '' }}" readonly>
                            </div>
                            <div class="col-3">
                                <label class="form-label small mb-1">Label Max</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $pertanyaan->config_json['label_max'] ?? '' }}" readonly>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
