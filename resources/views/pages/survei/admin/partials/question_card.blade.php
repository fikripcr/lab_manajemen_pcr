<div class="card m-3 card-pertanyaan bg-light border shadow-sm" data-id="{{ $pertanyaan->id }}">
    <div class="card-body py-3">
        <div class="row">
            <div class="col-auto drag-handle cursor-move text-muted" title="Drag untuk mengatur urutan">
                <i class="ti ti-grip-vertical"></i>
            </div>
            <div class="col">
                <div class="mb-2">
                    <input type="text" class="form-control form-control-flush fw-bold pertanyaan-teks-input" 
                           value="{{ $pertanyaan->teks_pertanyaan }}" 
                           onchange="debounceSave({{ $pertanyaan->id }})"
                           onkeyup="debounceSave({{ $pertanyaan->id }})"
                           placeholder="Tulis Pertanyaan Disini">
                </div>
                
                <div class="row align-items-center">
                    <div class="col-auto">
                        <select class="form-select form-select-sm pertanyaan-tipe-select" 
                                onchange="onTypeChange({{ $pertanyaan->id }}, this)">
                            @foreach(['Teks_Singkat', 'Esai', 'Angka', 'Pilihan_Ganda', 'Kotak_Centang', 'Dropdown', 'Skala_Linear', 'Tanggal', 'Upload_File'] as $tipe)
                                <option value="{{ $tipe }}" {{ $pertanyaan->tipe == $tipe ? 'selected' : '' }}>
                                    {{ str_replace('_', ' ', $tipe) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="form-check form-switch m-0">
                            <input class="form-check-input pertanyaan-wajib-check" type="checkbox" 
                                   {{ $pertanyaan->wajib_diisi ? 'checked' : '' }}
                                   onchange="debounceSave({{ $pertanyaan->id }})">
                            <span class="form-check-label text-muted small">Wajib Diisi</span>
                        </label>
                    </div>
                    <div class="col-auto ms-auto">
                        <button class="btn btn-icon btn-sm btn-ghost-danger" onclick="deletePertanyaan({{ $pertanyaan->id }})">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>

                <!-- Option Area (Rendered conditionally based on type) -->
                @if(in_array($pertanyaan->tipe, ['Pilihan_Ganda', 'Kotak_Centang', 'Dropdown']))
                <div class="mt-3 ps-3 border-start">
                    <small class="text-muted d-block mb-2">Opsi Jawaban:</small>
                    <div class="option-list-{{ $pertanyaan->id }}">
                        @foreach($pertanyaan->opsi as $opsi)
                            <div class="input-group input-group-sm mb-1">
                                <span class="input-group-text"><i class="ti ti-circle"></i></span>
                                <input type="text" class="form-control" value="{{ $opsi->label }}" onchange="debounceSave({{ $pertanyaan->id }})">
                                <button class="btn btn-icon btn-ghost-danger" onclick="$(this).parent().remove(); debounceSave({{ $pertanyaan->id }});">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button class="btn btn-sm btn-ghost-primary mt-1" onclick="addOpsi({{ $pertanyaan->id }})">
                        <i class="ti ti-plus me-1"></i>Tambah Opsi
                    </button>
                </div>
                @endif

                <!-- Scale Config (for Skala_Linear) -->
                @if($pertanyaan->tipe === 'Skala_Linear')
                <div class="mt-3 ps-3 border-start">
                    <small class="text-muted d-block mb-2">Konfigurasi Skala:</small>
                    <div class="row g-2">
                        <div class="col-3">
                            <label class="form-label small">Min</label>
                            <input type="number" class="form-control form-control-sm" value="{{ $pertanyaan->config_json['min'] ?? 1 }}" readonly>
                        </div>
                        <div class="col-3">
                            <label class="form-label small">Max</label>
                            <input type="number" class="form-control form-control-sm" value="{{ $pertanyaan->config_json['max'] ?? 5 }}" readonly>
                        </div>
                        <div class="col-3">
                            <label class="form-label small">Label Min</label>
                            <input type="text" class="form-control form-control-sm" value="{{ $pertanyaan->config_json['label_min'] ?? '' }}" readonly>
                        </div>
                        <div class="col-3">
                            <label class="form-label small">Label Max</label>
                            <input type="text" class="form-control form-control-sm" value="{{ $pertanyaan->config_json['label_max'] ?? '' }}" readonly>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
