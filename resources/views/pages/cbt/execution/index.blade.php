@extends('layouts.exam.app')

@section('title', $jadwal->nama_kegiatan . ' — Ujian')

@push('css')
<style>
    /* ============================================================
       EXAM PAGE — Premium Full-Screen Design (Tabler-based)
    ============================================================ */
    :root {
        --exam-primary:      var(--tblr-primary, #206bc4);
        --exam-primary-dark: var(--tblr-primary-darker, #1a569d);
        --exam-success:      var(--tblr-success, #2fb344);
        --exam-warning:      var(--tblr-warning, #f76707);
        --exam-danger:       var(--tblr-danger, #d63939);
        --exam-bg:           var(--tblr-bg-surface-secondary, #f0f2f7);
        --exam-card:         var(--tblr-bg-surface, #ffffff);
        --exam-border:       var(--tblr-border-color, #e8ecf0);
        --exam-text:         var(--tblr-body-color, #1e293b);
        --exam-muted:        var(--tblr-secondary, #64748b);
        --exam-header-h: 62px;
    }

    /* ── Offline Banner ── */
    .offline-banner {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 9999;
        background: var(--exam-danger);
        color: #fff;
        text-align: center;
        padding: 8px 16px;
        font-weight: 600;
        font-size: .875rem;
        letter-spacing: .3px;
    }

    /* ── Sticky Exam Header ── */
    .exam-header {
        position: sticky;
        top: 0;
        z-index: 100;
        height: var(--exam-header-h);
        background: #fff;
        border-bottom: 1px solid var(--exam-border);
        box-shadow: 0 1px 8px rgba(0,0,0,.06);
        display: flex;
        align-items: center;
        padding: 0 24px;
        gap: 16px;
    }
    .exam-header-logo {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--exam-primary), var(--exam-primary-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .exam-header-info { flex: 1; min-width: 0; }
    .exam-header-pretitle {
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--exam-muted);
        line-height: 1;
    }
    .exam-header-title {
        font-size: .95rem;
        font-weight: 700;
        color: var(--exam-text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.2;
    }
    .exam-header-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }
    .exam-user-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 10px 4px 6px;
        background: var(--exam-bg);
        border-radius: 50px;
        font-size: .8rem;
        font-weight: 600;
        color: var(--exam-text);
    }
    .exam-user-avatar {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--exam-primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .7rem;
        font-weight: 700;
    }

    /* ── Timer ── */
    .exam-timer {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 50px;
        background: linear-gradient(135deg, var(--exam-primary), var(--exam-primary-dark));
        color: #fff;
        font-weight: 800;
        font-size: 1.05rem;
        font-family: 'JetBrains Mono', 'Fira Code', ui-monospace, monospace;
        letter-spacing: 2px;
        box-shadow: 0 4px 12px rgba(32, 107, 196, .25);
        transition: background .4s;
        white-space: nowrap;
    }
    .exam-timer.warn { background: linear-gradient(135deg, var(--exam-warning), #c75a00); }
    .exam-timer.danger {
        background: linear-gradient(135deg, var(--exam-danger), #a82c2c);
        animation: pulse-danger 1s infinite;
    }
    @keyframes pulse-danger {
        0%, 100% { transform: scale(1); }
        50%       { transform: scale(1.04); }
    }
    .exam-progress-bar {
        height: 3px;
        background: #e2e8f0;
        position: sticky;
        top: var(--exam-header-h);
        z-index: 99;
    }
    .exam-progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--exam-primary), #4da3ff);
        transition: width .4s ease;
        border-radius: 0 3px 3px 0;
    }

    /* ── Main Layout ── */
    .exam-body {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 20px;
        padding: 24px 24px 80px;
        max-width: 1280px;
        margin: 0 auto;
    }
    @media (max-width: 900px) {
        .exam-body { grid-template-columns: 1fr; }
        .exam-sidebar { order: -1; }
    }

    /* ── Question Card ── */
    .question-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--exam-border);
        box-shadow: 0 4px 20px rgba(0,0,0,.05);
        overflow: hidden;
        animation: slide-up .3s ease-out;
    }
    @keyframes slide-up {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .question-header {
        padding: 20px 28px 16px;
        border-bottom: 1px solid var(--exam-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .mata-uji-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        background: #eff6ff;
        color: var(--exam-primary);
        border-radius: 50px;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .3px;
    }
    .question-number-display {
        display: flex;
        align-items: baseline;
        gap: 4px;
    }
    .question-number-display .label { font-size: .75rem; color: var(--exam-muted); font-weight: 600; }
    .question-number-display .current { font-size: 2rem; font-weight: 800; color: var(--exam-primary); line-height: 1; }
    .question-number-display .total { font-size: .85rem; color: var(--exam-muted); }

    .question-body {
        padding: 28px 28px 20px;
        font-size: 1.1rem;
        line-height: 1.75;
        color: var(--exam-text);
        font-weight: 400;
        min-height: 160px;
    }

    /* ── Options ── */
    .options-list { padding: 0 28px 8px; display: flex; flex-direction: column; gap: 10px; }
    .option-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        border: 2px solid var(--exam-border);
        border-radius: 12px;
        cursor: pointer;
        transition: all .18s ease;
        background: #fff;
    }
    .option-item:hover { border-color: #93c5fd; background: #f8fbff; transform: translateX(3px); }
    .option-item.selected {
        border-color: var(--exam-primary);
        background: #eff6ff;
        box-shadow: 0 0 0 3px rgba(32,107,196,.1);
    }
    .option-letter {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #f1f5f9;
        color: var(--exam-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: .85rem;
        flex-shrink: 0;
        transition: all .18s;
    }
    .option-item.selected .option-letter {
        background: var(--exam-primary);
        color: #fff;
        box-shadow: 0 4px 10px rgba(32,107,196,.3);
    }
    .option-text { font-size: 1.02rem; color: var(--exam-text); flex: 1; }

    /* ── Essay ── */
    .essay-area {
        padding: 0 28px 20px;
    }
    .essay-area textarea {
        width: 100%;
        border: 2px solid var(--exam-border);
        border-radius: 12px;
        padding: 16px 18px;
        font-size: 1rem;
        line-height: 1.6;
        resize: vertical;
        min-height: 180px;
        transition: border .18s;
        font-family: inherit;
        color: var(--exam-text);
    }
    .essay-area textarea:focus {
        outline: none;
        border-color: var(--exam-primary);
        box-shadow: 0 0 0 3px rgba(32,107,196,.1);
    }

    /* ── Question Footer / Navigation ── */
    .question-footer {
        padding: 16px 28px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid var(--exam-border);
        gap: 12px;
        flex-wrap: wrap;
    }
    .doubt-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border: 2px solid #fdd9b5;
        border-radius: 50px;
        background: #fff8f3;
        cursor: pointer;
        font-weight: 700;
        font-size: .875rem;
        color: var(--exam-warning);
        transition: all .18s;
        user-select: none;
    }
    .doubt-toggle.active, .doubt-toggle:hover {
        background: var(--exam-warning);
        border-color: var(--exam-warning);
        color: #fff;
    }
    .doubt-toggle input { display: none; }
    .nav-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        border-radius: 50px;
        font-weight: 700;
        font-size: .9rem;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all .18s;
    }
    .nav-btn-prev {
        background: #fff;
        border-color: var(--exam-border);
        color: var(--exam-muted);
    }
    .nav-btn-prev:hover { border-color: #94a3b8; color: var(--exam-text); }
    .nav-btn-next { background: var(--exam-primary); color: #fff; }
    .nav-btn-next:hover { background: var(--exam-primary-dark); transform: translateX(2px); }
    .nav-btn-next.last { background: var(--exam-success); }
    .nav-btn-next.last:hover { background: #238a35; }
    .nav-btn:disabled { opacity: .4; cursor: not-allowed; transform: none !important; }

    /* ── Sidebar ── */
    .exam-sidebar { display: flex; flex-direction: column; gap: 16px; }
    .sidebar-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--exam-border);
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
        overflow: hidden;
    }
    .sidebar-card-header {
        padding: 12px 16px;
        border-bottom: 1px solid var(--exam-border);
        font-weight: 700;
        font-size: .8rem;
        text-transform: uppercase;
        letter-spacing: .7px;
        color: var(--exam-muted);
    }
    .sidebar-card-body {
        padding: 14px;
        max-height: calc(100vh - 320px);
        overflow-y: auto;
    }
    .sidebar-card-body::-webkit-scrollbar { width: 4px; }
    .sidebar-card-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    /* ── Navigator Grid ── */
    .nav-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 6px; }
    .nav-cell {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-weight: 700;
        font-size: .8rem;
        cursor: pointer;
        border: 2px solid var(--exam-border);
        background: #fff;
        color: var(--exam-muted);
        transition: all .15s;
    }
    .nav-cell:hover { border-color: var(--exam-primary); color: var(--exam-primary); transform: scale(1.05); }
    .nav-cell.answered { background: var(--exam-success); border-color: var(--exam-success); color: #fff; }
    .nav-cell.doubt   { background: var(--exam-warning); border-color: var(--exam-warning); color: #fff; }
    .nav-cell.active  { background: var(--exam-primary); border-color: var(--exam-primary); color: #fff; box-shadow: 0 4px 10px rgba(32,107,196,.3); }

    /* ── Legend ── */
    .legend-row { display: flex; flex-wrap: wrap; gap: 8px 16px; padding: 12px 16px; }
    .legend-item { display: flex; align-items: center; gap: 6px; font-size: .75rem; color: var(--exam-muted); font-weight: 600; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

    /* ── Finish Button ── */
    .finish-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--exam-success), #238a35);
        color: #fff;
        font-weight: 700;
        font-size: .95rem;
        border: none;
        cursor: pointer;
        transition: all .2s;
        box-shadow: 0 4px 14px rgba(47,179,68,.25);
    }
    .finish-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(47,179,68,.35); }

    /* ── Reset/Admin ── */
    .reset-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 8px;
        margin-top: 8px;
        border-radius: 10px;
        background: transparent;
        color: var(--exam-danger);
        font-weight: 600;
        font-size: .8rem;
        border: 1px dashed #fca5a5;
        cursor: pointer;
        transition: all .18s;
    }
    .reset-btn:hover { background: #fff5f5; border-color: var(--exam-danger); }
</style>
@endpush

{{-- ===== STICKY EXAM HEADER ===== --}}
@section('exam-header')
<div class="offline-banner" id="offline-banner">
    <i class="ti ti-wifi-off me-2"></i>
    <strong>Koneksi Terputus!</strong> Jawaban tetap aman, akan dikirim ulang otomatis saat online.
</div>

<header class="exam-header">
    <div class="exam-header-logo">
        <i class="ti ti-school"></i>
    </div>
    <div class="exam-header-info">
        <div class="exam-header-pretitle">Sesi Ujian Aktif</div>
        <div class="exam-header-title">{{ $jadwal->nama_kegiatan }}</div>
    </div>
    <div class="exam-header-right">
        <div class="exam-user-badge d-none d-sm-flex">
            <div class="exam-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            {{ auth()->user()->name }}
        </div>
        <div class="exam-timer" id="exam-timer">
            <i class="ti ti-clock-hour-4" style="font-size:1rem"></i>
            <span id="timer-display">--:--:--</span>
        </div>
    </div>
</header>
<div class="exam-progress-bar">
    <div class="exam-progress-bar-fill" id="progress-fill" style="width: 0%"></div>
</div>
@endsection

{{-- ===== MAIN CONTENT ===== --}}
@section('content')
<div class="exam-body">

    {{-- === QUESTION PANEL === --}}
    <div class="exam-main">
        <div class="question-card" id="question-card">

            {{-- Header --}}
            <div class="question-header">
                <div class="mata-uji-badge">
                    <i class="ti ti-list-details" style="font-size:.9rem"></i>
                    <span id="mata-uji-label">Mata Uji</span>
                </div>
                <div class="question-number-display">
                    <span class="label">No.</span>
                    <span class="current" id="qnum-current">1</span>
                    <span class="total">/ {{ count($paketSoal) }}</span>
                </div>
            </div>

            {{-- Question Text --}}
            <div class="question-body" id="question-text">
                {{-- Rendered via JS --}}
            </div>

            {{-- Options --}}
            <div id="options-wrapper">
                {{-- Rendered via JS --}}
            </div>

            {{-- Footer Navigation --}}
            <div class="question-footer">
                <button class="nav-btn nav-btn-prev" id="btn-prev" onclick="goPrev()">
                    <i class="ti ti-arrow-left"></i> Sebelumnya
                </button>

                <label class="doubt-toggle" id="doubt-label">
                    <input type="checkbox" id="doubt-check" onchange="toggleDoubt(this.checked)">
                    <i class="ti ti-flag-3"></i>
                    Ragu-Ragu
                </label>

                <button class="nav-btn nav-btn-next" id="btn-next" onclick="goNext()">
                    Selanjutnya <i class="ti ti-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- === SIDEBAR === --}}
    <div class="exam-sidebar">

        {{-- Navigator --}}
        <div class="sidebar-card">
            <div class="sidebar-card-header">Navigasi Soal</div>
            <div class="sidebar-card-body">
                <div class="nav-grid" id="nav-grid">
                    {{-- Rendered via JS --}}
                </div>
            </div>
            <div class="legend-row">
                <div class="legend-item">
                    <div class="legend-dot" style="background:var(--exam-success)"></div> Dijawab
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:var(--exam-warning)"></div> Ragu
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:#e2e8f0; border:1px solid #cbd5e1;"></div> Belum
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:var(--exam-primary)"></div> Aktif
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="sidebar-card" style="padding:14px;">
            <button class="finish-btn" onclick="finishExam()">
                <i class="ti ti-check"></i> Selesaikan Ujian
            </button>

            @if(auth()->user()->hasRole('admin'))
            <button class="reset-btn" onclick="resetAdminData()">
                <i class="ti ti-refresh"></i> Reset Data Testing
            </button>
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
// ══════════════════════════════════════════
//  1. DATA & STATE
// ══════════════════════════════════════════
const SOAL       = {!! json_encode($paketSoal) !!};
const RIWAYAT_ID = "{{ $riwayat->encrypted_riwayat_ujian_id }}";
const SAVE_URL   = "{{ route('cbt.execute.save', $riwayat->encrypted_riwayat_ujian_id) }}";
const SUBMIT_URL = "{{ route('cbt.execute.submit', $riwayat->encrypted_riwayat_ujian_id) }}";
const END_TIME   = new Date("{{ $jadwal->waktu_selesai->toIso8601String() }}").getTime();
@if(auth()->user()->hasRole('admin'))
const RESET_URL  = "{{ route('cbt.execute.reset-admin', $jadwal->encrypted_jadwal_ujian_id) }}";
@endif

let currentIdx = 0;
let answers    = JSON.parse(localStorage.getItem('cbt_' + RIWAYAT_ID) || '{}');

// ══════════════════════════════════════════
//  2. INIT
// ══════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    if (!SOAL || SOAL.length === 0) {
        document.getElementById('question-card').innerHTML = `
            <div style="padding:60px 28px; text-align:center; color:var(--exam-muted);">
                <i class="ti ti-file-off" style="font-size:3rem; display:block; margin-bottom:12px;"></i>
                <strong>Tidak ada soal tersedia</strong><br>
                <span style="font-size:.9rem;">Paket ujian ini belum memiliki soal. Hubungi pengawas.</span>
            </div>`;
        document.getElementById('question-card').style.opacity = '1';
        return;
    }
    renderNavigator();
    renderQuestion(0);
    startTimer();
    initOffline();
});

// ══════════════════════════════════════════
//  3. RENDER
// ══════════════════════════════════════════
function renderNavigator() {
    const grid = document.getElementById('nav-grid');
    grid.innerHTML = '';
    SOAL.forEach((soal, i) => {
        const ans  = answers[soal.soal_id] || answers[soal.id] || {};
        const done = ans.opsi_id || ans.jawaban_esai;
        let cls    = '';
        if (done)        cls = 'answered';
        if (ans.is_ragu) cls = 'doubt';
        if (i === currentIdx) cls += ' active';
        const cell = document.createElement('div');
        cell.className   = `nav-cell ${cls}`;
        cell.textContent = i + 1;
        cell.onclick     = () => renderQuestion(i);
        grid.appendChild(cell);
    });
    // Progress bar
    const answered = SOAL.filter((s, i) => {
        const ans = answers[s.soal_id] || answers[s.id] || {};
        return ans.opsi_id || ans.jawaban_esai;
    }).length;
    document.getElementById('progress-fill').style.width = ((answered / SOAL.length) * 100) + '%';
}

function renderQuestion(idx) {
    // Animate
    const card = document.getElementById('question-card');
    card.style.opacity = '0';
    card.style.transform = 'translateY(8px)';

    setTimeout(() => {
        currentIdx = idx;
        const soal = SOAL[idx];
        const ans  = answers[soal.soal_id] || answers[soal.id] || {};

        // Mata uji label
        document.getElementById('mata-uji-label').textContent =
            soal.mata_uji ? soal.mata_uji.nama_mata_uji : 'UMUM';

        // Number
        document.getElementById('qnum-current').textContent = idx + 1;

        // Text
        document.getElementById('question-text').innerHTML = soal.konten_pertanyaan || soal.pertanyaan || '';

        // Doubt checkbox
        const doubtCheck = document.getElementById('doubt-check');
        const doubtLabel = document.getElementById('doubt-label');
        doubtCheck.checked = !!ans.is_ragu;
        doubtLabel.classList.toggle('active', !!ans.is_ragu);

        // Options
        const wrapper = document.getElementById('options-wrapper');
        const soalId  = soal.soal_id || soal.id;

        if (soal.tipe_soal === 'Pilihan_Ganda' || soal.tipe_soal === 'Benar_Salah') {
            const opts = soal.tipe_soal === 'Benar_Salah'
                ? [{ opsi_jawaban_id: 'benar', label: 'A', teks_jawaban: 'BENAR' }, { opsi_jawaban_id: 'salah', label: 'B', teks_jawaban: 'SALAH' }]
                : (soal.opsi_jawaban || []);

            wrapper.innerHTML = '<div class="options-list">' + opts.map(opt => {
                const opsiId  = opt.opsi_jawaban_id || opt.id;
                const selected = ans.opsi_id == opsiId ? 'selected' : '';
                return `<div class="option-item ${selected}" onclick="pickOption(${soalId}, '${opsiId}', this)">
                    <div class="option-letter">${opt.label}</div>
                    <div class="option-text">${opt.teks_jawaban}</div>
                </div>`;
            }).join('') + '</div>';
        } else {
            // Essay
            wrapper.innerHTML = `<div class="essay-area">
                <textarea placeholder="Tuliskan jawaban lengkap Anda di sini..." 
                    oninput="saveEssay(${soalId}, this.value)">${ans.jawaban_esai || ''}</textarea>
            </div>`;
        }

        // Nav buttons
        document.getElementById('btn-prev').disabled = (idx === 0);
        const btnNext = document.getElementById('btn-next');
        if (idx === SOAL.length - 1) {
            btnNext.innerHTML = 'Selesai <i class="ti ti-check ms-1"></i>';
            btnNext.classList.add('last');
            btnNext.onclick = () => finishExam();
        } else {
            btnNext.innerHTML = 'Selanjutnya <i class="ti ti-arrow-right ms-1"></i>';
            btnNext.classList.remove('last');
            btnNext.onclick = () => goNext();
        }

        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
        card.style.transition = 'opacity .25s ease, transform .25s ease';

        renderNavigator();
    }, 100);
}

// ══════════════════════════════════════════
//  4. NAVIGATION
// ══════════════════════════════════════════
function goPrev() { if (currentIdx > 0) renderQuestion(currentIdx - 1); }
function goNext() { if (currentIdx < SOAL.length - 1) renderQuestion(currentIdx + 1); }

// ══════════════════════════════════════════
//  5. ANSWER HANDLING
// ══════════════════════════════════════════
window.pickOption = (soalId, opsiId, el) => {
    document.querySelectorAll('.option-item').forEach(i => i.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('.option-letter')?.classList.add('selected');
    saveAnswer(soalId, { opsi_id: opsiId });
};

window.saveEssay = (soalId, val) => saveAnswer(soalId, { jawaban_esai: val });

window.toggleDoubt = (checked) => {
    const soal   = SOAL[currentIdx];
    const soalId = soal.soal_id || soal.id;
    if (!answers[soalId]) answers[soalId] = {};
    answers[soalId].is_ragu = checked;
    document.getElementById('doubt-label').classList.toggle('active', checked);
    saveAnswer(soalId, answers[soalId]);
};

function saveAnswer(soalId, partial) {
    if (!answers[soalId]) answers[soalId] = {};
    answers[soalId] = { ...answers[soalId], ...partial };
    localStorage.setItem('cbt_' + RIWAYAT_ID, JSON.stringify(answers));
    syncToServer(soalId, answers[soalId]);
    renderNavigator();
}

function syncToServer(soalId, data) {
    fetch(SAVE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            soal_id:      soalId,
            opsi_id:      data.opsi_id      ?? null,
            jawaban_esai: data.jawaban_esai ?? null,
            is_ragu:      data.is_ragu      ?? false,
        })
    }).catch(() => {/* will retry on reconnect */});
}

// ══════════════════════════════════════════
//  6. FINISH & SUBMIT
// ══════════════════════════════════════════
window.finishExam = (isAuto = false) => {
    const unanswered = SOAL.filter(s => {
        const ans = answers[s.soal_id] || answers[s.id] || {};
        return !ans.opsi_id && !ans.jawaban_esai;
    }).length;

    let msg = isAuto
        ? 'WAKTU HABIS! Jawaban Anda akan dikirim otomatis.'
        : `Anda akan menyelesaikan ujian.${unanswered > 0 ? ` Masih ada ${unanswered} soal belum dijawab.` : ' Semua soal sudah dijawab.'} Lanjutkan?`;

    if (isAuto || confirm(msg)) {
        fetch(SUBMIT_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({})
        })
        .then(r => r.json())
        .then(res => {
            if (res.success || res.status === 'success') {
                localStorage.removeItem('cbt_' + RIWAYAT_ID);
                window.location.href = res.redirect;
            } else {
                alert('Gagal mengirim jawaban: ' + (res.message || 'Coba lagi.'));
            }
        })
        .catch(() => alert('Terjadi kesalahan jaringan. Coba lagi.'));
    }
};

// ══════════════════════════════════════════
//  7. TIMER
// ══════════════════════════════════════════
function startTimer() {
    const timerEl   = document.getElementById('exam-timer');
    const displayEl = document.getElementById('timer-display');

    const tick = () => {
        const now  = Date.now();
        const diff = END_TIME - now;

        if (diff <= 0) {
            displayEl.textContent = 'SELESAI';
            timerEl.classList.add('danger');
            finishExam(true);
            return;
        }

        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        displayEl.textContent = [h, m, s].map(v => String(v).padStart(2, '0')).join(':');

        timerEl.classList.toggle('warn',   diff < 300000 && diff >= 60000);
        timerEl.classList.toggle('danger', diff < 60000);
    };

    tick();
    setInterval(tick, 1000);
}

// ══════════════════════════════════════════
//  8. OFFLINE DETECTION
// ══════════════════════════════════════════
function initOffline() {
    const banner = document.getElementById('offline-banner');
    window.addEventListener('offline', () => banner.style.display = 'block');
    window.addEventListener('online',  () => banner.style.display = 'none');
}

// ══════════════════════════════════════════
//  9. ADMIN RESET
// ══════════════════════════════════════════
@if(auth()->user()->hasRole('admin'))
window.resetAdminData = () => {
    if (!confirm('Reset data testing? Semua jawaban dan log pelanggaran akan dihapus.')) return;
    fetch(RESET_URL, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(res => {
        if (res.success || res.status === 'success') {
            localStorage.removeItem('cbt_' + RIWAYAT_ID);
            window.location.href = res.redirect;
        }
    });
};
@endif
</script>
@endpush
