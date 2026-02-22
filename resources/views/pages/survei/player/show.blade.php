@extends('layouts.assessment.app')

@section('title', $survei->judul)

@section('content')
<div class="container py-4" style="max-width: 720px;">
    @if(isset($isPreview) && $isPreview)
    <div class="alert alert-azure text-center mb-4">
        <strong><i class="ti ti-eye me-1"></i> MODE PREVIEW</strong> — Tampilan ini hanya simulasi. Jawaban tidak akan disimpan.
    </div>
    <div class="mb-4 text-end">
        <x-tabler.button type="back" href="{{ route('survei.builder', $survei->encrypted_survei_id) }}" text="Kembali ke Builder" />
    </div>
    @endif

    {{-- Survey Header --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body py-3 px-4">
            <h3 class="mb-1">{{ $survei->judul }}</h3>
            @if($survei->deskripsi)
                <p class="text-muted mb-0 small">{{ $survei->deskripsi }}</p>
            @endif
        </div>
        {{-- Progress Bar --}}
        @php $totalHalaman = $survei->halaman->count(); @endphp
        @if($totalHalaman > 1)
        <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-4">
            <div class="progress progress-sm mb-1">
                <div class="progress-bar bg-primary" id="survei-progress" style="width: {{ round(1 / $totalHalaman * 100) }}%"></div>
            </div>
            <small class="text-muted">Halaman <span id="current-page-num">1</span> dari {{ $totalHalaman }}</small>
        </div>
        @endif
    </div>

    {{-- Form --}}
    <form action="{{ route('survei.public.store', $survei->slug) }}" method="POST" id="survei-form">
        @csrf

        @foreach($survei->halaman as $hIndex => $halaman)
        <div class="survei-halaman {{ $hIndex > 0 ? 'd-none' : '' }}"
             id="halaman-{{ $halaman->id }}"
             data-index="{{ $hIndex }}"
             data-halaman-id="{{ $halaman->id }}">

            @if($halaman->judul_halaman && $totalHalaman > 1)
            <h5 class="text-primary mb-3 fw-semibold">{{ $halaman->judul_halaman }}</h5>
            @endif
            @if($halaman->deskripsi_halaman)
            <p class="text-muted small mb-3">{!! $halaman->deskripsi_halaman !!}</p>
            @endif

            @foreach($halaman->pertanyaan as $qIndex => $pertanyaan)
            @php $isLinear = ($survei->mode ?? 'Linear') !== 'Bercabang'; @endphp
                <div class="card mb-1 p-2 my-3 border-0 shadow-sm pertanyaan-item"
                     data-wajib="{{ $pertanyaan->wajib_diisi ? '1' : '0' }}"
                     @if(!$isLinear) data-next="{{ $pertanyaan->next_pertanyaan_id }}" @endif
                     id="pertanyaan-{{ $pertanyaan->id }}">
                     <div class="card-body px-3 py-2">
                    <label class="form-label fw-semibold mb-1">
                        <span class="text-muted small me-1">{{ $loop->iteration }}.</span>
                        {{ $pertanyaan->teks_pertanyaan }}
                        @if($pertanyaan->wajib_diisi)
                            <span class="text-danger ms-1">*</span>
                        @endif
                    </label>


                    @if($pertanyaan->bantuan_teks)
                        <p class="text-muted small mb-2">{{ $pertanyaan->bantuan_teks }}</p>
                    @endif

                    {{-- Render input by type --}}
                    @if($pertanyaan->tipe === 'Teks_Singkat')
                        <input type="text" id="jawaban-{{ $pertanyaan->pertanyaan_id }}" name="jawaban[{{ $pertanyaan->pertanyaan_id }}]"
                               class="form-control pertanyaan-input"
                               data-id="{{ $pertanyaan->pertanyaan_id }}"
                               placeholder="Jawaban singkat...">

                    @elseif($pertanyaan->tipe === 'Esai')
                        <textarea id="jawaban-{{ $pertanyaan->pertanyaan_id }}" name="jawaban[{{ $pertanyaan->pertanyaan_id }}]"
                                  class="form-control pertanyaan-input"
                                  data-id="{{ $pertanyaan->pertanyaan_id }}"
                                  rows="4" placeholder="Tulis jawaban..."></textarea>

                    @elseif($pertanyaan->tipe === 'Angka')
                        <input type="number" id="jawaban-{{ $pertanyaan->pertanyaan_id }}" name="jawaban[{{ $pertanyaan->pertanyaan_id }}]"
                               class="form-control pertanyaan-input"
                               data-id="{{ $pertanyaan->pertanyaan_id }}"
                               placeholder="0">

                    @elseif($pertanyaan->tipe === 'Tanggal')
                        <input type="date" id="jawaban-{{ $pertanyaan->pertanyaan_id }}" name="jawaban[{{ $pertanyaan->pertanyaan_id }}]"
                               class="form-control pertanyaan-input"
                               data-id="{{ $pertanyaan->pertanyaan_id }}">

                    @elseif($pertanyaan->tipe === 'Pilihan_Ganda')
                        <div>
                            @foreach($pertanyaan->opsi as $opsi)
                            @php $radioId = 'q' . $pertanyaan->pertanyaan_id . '_opsi_' . $opsi->opsi_id; @endphp
                            <label for="{{ $radioId }}" class="form-check form-check-lg border rounded px-3 py-2 mb-2 ms-3 d-flex align-items-center cursor-pointer hover-check">
                                <input type="radio"
                                       id="{{ $radioId }}"
                                       class="form-check-input pertanyaan-input flex-shrink-0"
                                       name="jawaban[{{ $pertanyaan->pertanyaan_id }}]"
                                       data-id="{{ $pertanyaan->pertanyaan_id }}"
                                       data-next-opsi="{{ $opsi->next_pertanyaan_id }}"
                                       value="{{ $opsi->opsi_id }}">
                                <span class="form-check-label ms-2">{{ $opsi->label }}</span>
                            </label>
                            @endforeach
                        </div>

                    @elseif($pertanyaan->tipe === 'Kotak_Centang')
                    <small>(Silahkan pilih minimal 1 opsi)</small>
                        <div>
                            @foreach($pertanyaan->opsi as $opsi)
                            @php $checkId = 'q' . $pertanyaan->pertanyaan_id . '_check_' . $loop->index; @endphp
                            <label for="{{ $checkId }}" class="form-check form-check-lg border rounded px-3 py-2 mb-2 ms-3 d-flex align-items-center cursor-pointer hover-check">
                                <input type="checkbox"
                                       id="{{ $checkId }}"
                                       class="form-check-input pertanyaan-checkbox flex-shrink-0"
                                       name="jawaban[{{ $pertanyaan->pertanyaan_id }}][]"
                                       data-id="{{ $pertanyaan->pertanyaan_id }}"
                                       value="{{ $opsi->opsi_id }}">
                                <span class="form-check-label ms-2">{{ $opsi->label }}</span>
                            </label>
                            @endforeach
                        </div>

                    @elseif($pertanyaan->tipe === 'Dropdown')
                        <select name="jawaban[{{ $pertanyaan->pertanyaan_id }}]"
                                class="form-select pertanyaan-input"
                                data-id="{{ $pertanyaan->pertanyaan_id }}">
                            <option value="">Pilih jawaban...</option>
                            @foreach($pertanyaan->opsi as $opsi)
                            <option value="{{ $opsi->opsi_id }}">{{ $opsi->label }}</option>
                            @endforeach
                        </select>
                    @elseif($pertanyaan->tipe === 'Skala_Linear')

                        @php $config = $pertanyaan->config_json ?? ['min' => 1, 'max' => 5, 'label_min' => '', 'label_max' => '']; @endphp
                        <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                            @if($config['label_min'] ?? false)
                                <small class="text-muted">{{ $config['label_min'] }}</small>
                            @endif
                            <div class="d-flex gap-2 flex-wrap">
                                @for($i = ($config['min'] ?? 1); $i <= ($config['max'] ?? 5); $i++)
                                @php $scaleId = 'q' . $pertanyaan->pertanyaan_id . '_scale_' . $i; @endphp
                                <label class="form-selectgroup-item mb-0" for="{{ $scaleId }}">
                                    <input type="radio"
                                           id="{{ $scaleId }}"
                                           class="form-selectgroup-input pertanyaan-input"
                                           name="jawaban[{{ $pertanyaan->pertanyaan_id }}]"
                                           data-id="{{ $pertanyaan->pertanyaan_id }}"
                                           value="{{ $i }}">
                                    <span class="form-selectgroup-label">{{ $i }}</span>
                                </label>
                                @endfor
                            </div>
                            @if($config['label_max'] ?? false)
                                <small class="text-muted">{{ $config['label_max'] }}</small>
                            @endif
                        </div>

                    @elseif($pertanyaan->tipe === 'Rating_Bintang')
                        <div class="d-flex gap-2 mt-2" id="rating-{{ $pertanyaan->pertanyaan_id }}">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" class="d-none pertanyaan-input" style="display:none"
                                       name="jawaban[{{ $pertanyaan->pertanyaan_id }}]"
                                       data-id="{{ $pertanyaan->pertanyaan_id }}" value="{{ $i }}">
                                <i class="ti ti-star fs-2 text-muted rating-star" data-value="{{ $i }}"></i>
                            </label>
                            @endfor
                        </div>
                    @else

                        <p class="text-muted small">Tipe pertanyaan belum didukung.</p>
                    @endif
                </div>
            </div>
            @endforeach

            {{-- Page Navigation --}}
            <div class="d-flex justify-content-between mt-4 mb-2">
                @if($hIndex > 0)
                <button type="button" class="btn btn-outline-secondary btn-prev-halaman" data-current="{{ $hIndex }}">
                    <i class="ti ti-chevron-left me-1"></i> Sebelumnya
                </button>
                @else
                <div></div>
                @endif

                @if($hIndex < $totalHalaman - 1)
                <button type="button" class="btn btn-primary btn-next-halaman" data-current="{{ $hIndex }}">
                    Lanjutkan <i class="ti ti-chevron-right ms-1"></i>
                </button>
                @else
                {{-- Last page: submit button --}}
                @if(!isset($isPreview) || !$isPreview)
                <button type="submit" class="btn btn-success" id="btn-submit">
                    <i class="ti ti-send me-1"></i> Kirim Jawaban
                </button>
                @else
                <button type="button" class="btn btn-success" disabled>
                    <i class="ti ti-send me-1"></i> Kirim Jawaban (Preview)
                </button>
                @endif
                @endif
            </div>
        </div>
        @endforeach
    </form>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .hover-check { transition: background 0.15s; }
    .hover-check:hover { background: var(--tblr-primary-lt); }
    .form-check-lg { font-size: 1rem; }
    .pertanyaan-item { transition: box-shadow 0.2s; }
    .pertanyaan-item.answered { border-left: 3px solid var(--tblr-green) !important; }
    .pertanyaan-item.unanswered-required { border-left: 3px solid var(--tblr-red) !important; }
    .rating-star { cursor: pointer; transition: color 0.15s; }
    .rating-star.active { color: var(--tblr-yellow) !important; }
</style>

@endsection

@push('scripts')
<script>
const SURVEY_SLUG = '{{ $survei->slug }}';
const LS_KEY = 'survei_draft_' + SURVEY_SLUG;
const TOTAL_HALAMAN = {{ $survei->halaman->count() }};
const SURVEY_MODE = '{{ $survei->mode ?? "Linear" }}';

let allJawaban = {};

// ---- LocalStorage Save/Restore ----
function saveDraft() {
    try { localStorage.setItem(LS_KEY, JSON.stringify(allJawaban)); } catch(e) {}
}

function loadDraft() {
    try {
        const saved = localStorage.getItem(LS_KEY);
        if (saved) { allJawaban = JSON.parse(saved); }
    } catch(e) {}
}

function applyDraftToInputs() {
    Object.entries(allJawaban).forEach(([id, val]) => {
        const inputs = document.querySelectorAll(`[name="jawaban[${id}]"], [name="jawaban[${id}][]"]`);
        if (!inputs.length) return;
        const tipe = document.querySelector(`.pertanyaan-item[data-id="${id}"]`)?.dataset.tipe;

        if (tipe === 'Kotak_Centang') {
            const vals = Array.isArray(val) ? val : [val];
            inputs.forEach(cb => { cb.checked = vals.includes(cb.value); });
        } else if (tipe === 'Pilihan_Ganda' || tipe === 'Skala_Linear') {
            inputs.forEach(r => { r.checked = (r.value == val); });
        } else if (tipe === 'Rating_Bintang') {
            inputs.forEach(r => { r.checked = (r.value == val); });
            updateStars(id, val);
        } else {
            if (inputs[0]) inputs[0].value = val;
        }

        markAnswered(id);
    });
}

function markAnswered(id) {
    const card = document.querySelector(`.pertanyaan-item[data-id="${id}"]`);
    if (card) {
        card.classList.add('answered');
        card.classList.remove('unanswered-required');
    }
}

// ---- Track inputs on change ----
function attachInputListeners() {
    document.querySelectorAll('.pertanyaan-input').forEach(el => {
        el.addEventListener('change', function() {
            const id = this.dataset.id;
            const tipe = document.querySelector(`.pertanyaan-item[data-id="${id}"]`)?.dataset.tipe;

            if (tipe === 'Kotak_Centang') {
                const checked = [...document.querySelectorAll(`[name="jawaban[${id}][]"]:checked`)].map(c => c.value);
                allJawaban[id] = checked;
            } else {
                allJawaban[id] = this.value;
            }

            markAnswered(id);
            saveDraft();
        });
    });

    // Checkbox separately
    document.querySelectorAll('.pertanyaan-checkbox').forEach(el => {
        el.addEventListener('change', function() {
            const id = this.dataset.id;
            const checked = [...document.querySelectorAll(`[name="jawaban[${id}][]"]:checked`)].map(c => c.value);
            allJawaban[id] = checked;
            markAnswered(id);
            saveDraft();
        });
    });

    // Text/textarea live
    document.querySelectorAll('input[type="text"].pertanyaan-input, textarea.pertanyaan-input, input[type="number"].pertanyaan-input, input[type="date"].pertanyaan-input').forEach(el => {
        el.addEventListener('input', function() {
            const id = this.dataset.id;
            allJawaban[id] = this.value;
            if (this.value) markAnswered(id);
            saveDraft();
        });
    });
}

// ---- Rating Stars ----
function initRating() {
    document.querySelectorAll('.rating-star').forEach(star => {
        star.addEventListener('click', function() {
            const val = this.dataset.value;
            const container = this.closest('[id^="rating-"]');
            const id = container.id.replace('rating-', '');

            // Mark radio
            const radio = document.querySelector(`input[name="jawaban[${id}]"][value="${val}"]`);
            if (radio) radio.checked = true;

            allJawaban[id] = val;
            updateStars(id, val);
            markAnswered(id);
            saveDraft();
        });
    });
}

function updateStars(id, val) {
    document.querySelectorAll(`#rating-${id} .rating-star`).forEach(s => {
        s.classList.toggle('active', parseInt(s.dataset.value) <= parseInt(val));
    });
}

// ---- Validate current halaman ----
function validateHalaman(index) {
    const halaman = document.querySelector(`.survei-halaman[data-index="${index}"]`);
    if (!halaman) return true;

    let valid = true;
    let firstInvalid = null;

    halaman.querySelectorAll('.pertanyaan-item').forEach(card => {
        const id = card.dataset.id;
        const wajib = card.dataset.wajib;

        if (wajib !== '1') return;

        const tipe = card.dataset.tipe;
        let hasValue = false;

        if (tipe === 'Kotak_Centang') {
            hasValue = card.querySelectorAll('input:checked').length > 0;
        } else if (['Pilihan_Ganda', 'Skala_Linear', 'Rating_Bintang'].includes(tipe)) {
            hasValue = card.querySelector('input:checked') !== null;
        } else {
            const inp = card.querySelector('input, textarea, select');
            hasValue = inp && inp.value.trim() !== '';
        }

        if (!hasValue) {
            card.classList.add('unanswered-required');
            valid = false;
            if (!firstInvalid) firstInvalid = card;
        } else {
            card.classList.remove('unanswered-required');
        }
    });

    if (!valid && firstInvalid) {
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    return valid;
}

// ---- Page Navigation ----
function goToPage(index) {
    document.querySelectorAll('.survei-halaman').forEach(p => p.classList.add('d-none'));
    const target = document.querySelector(`.survei-halaman[data-index="${index}"]`);
    if (target) {
        target.classList.remove('d-none');
        updateProgress(index + 1);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function updateProgress(pageNum) {
    const prog = document.getElementById('survei-progress');
    const num = document.getElementById('current-page-num');
    if (prog) prog.style.width = Math.round(pageNum / TOTAL_HALAMAN * 100) + '%';
    if (num) num.textContent = pageNum;
}

// ---- Submit ----
function handleSubmit(e) {
    e.preventDefault();

    const lastIndex = TOTAL_HALAMAN - 1;
    if (!validateHalaman(lastIndex)) return;

    const form = document.getElementById('survei-form');
    const submitBtn = document.getElementById('btn-submit');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...';
    }

    const formData = new FormData(form);

    axios.post(form.action, formData, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
        if (res.data.redirect || res.data.success) {
            localStorage.removeItem(LS_KEY); // clear draft on success
            window.location.href = res.data.redirect || '{{ route('survei.public.thankyou', $survei->slug) }}';
        }
    })
    .catch(err => {
        const msg = err.response?.data?.message || 'Terjadi kesalahan. Silakan coba lagi.';
        if (typeof Swal !== 'undefined') {
            Swal.fire('Gagal', msg, 'error');
        } else {
            alert('Error: ' + msg);
        }
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ti ti-send me-1"></i> Kirim Jawaban';
        }
    });
}

// ---- Init ----
document.addEventListener('DOMContentLoaded', function() {
    loadDraft();
    attachInputListeners();
    initRating();
    applyDraftToInputs();

    // Next page buttons
    document.querySelectorAll('.btn-next-halaman').forEach(btn => {
        btn.addEventListener('click', function() {
            const current = parseInt(this.dataset.current);
            if (validateHalaman(current)) {
                goToPage(current + 1);
            }
        });
    });

    // Prev page buttons
    document.querySelectorAll('.btn-prev-halaman').forEach(btn => {
        btn.addEventListener('click', function() {
            const current = parseInt(this.dataset.current);
            goToPage(current - 1);
        });
    });

    // Form submit
    const form = document.getElementById('survei-form');
    if (form && !('{{ isset($isPreview) && $isPreview ? "true" : "" }}')) {
        form.addEventListener('submit', handleSubmit);
    }
});
</script>
@endpush
