@extends('layouts.exam.app')

@section('title', $jadwal->nama_kegiatan . ' — Ujian')

@section('exam-header')
<header class="navbar navbar-expand-md d-print-none border-bottom sticky-top bg-white py-2 shadow-sm">
    <div class="container-xl">
        {{-- Left: Event Info --}}
        <div class="d-flex align-items-center gap-3">
            <div class="avatar avatar-sm bg-primary-lt text-primary rounded">
                <i class="ti ti-device-laptop fs-2"></i>
            </div>
            <div class="d-none d-md-block">
                <div class="text-muted small text-uppercase fw-bold" style="letter-spacing: .5px; font-size: .65rem;">Sesi Ujian Aktif</div>
                <div class="fw-bold text-dark">{{ $jadwal->nama_kegiatan }}</div>
            </div>
        </div>

        {{-- Center: Prominent Timer --}}
        <div class="mx-auto">
            <div class="bg-dark text-white px-4 py-2 rounded-pill shadow-lg d-flex align-items-center gap-3 border border-secondary" id="timer-wrapper" style="min-width: 200px; transition: all 0.5s ease;">
                <div class="d-flex align-items-center gap-2">
                    <i class="ti ti-clock-filled fs-3 text-primary" id="timer-icon"></i>
                    <span class="text-muted small text-uppercase fw-bold d-none d-lg-inline">Sisa Waktu</span>
                </div>
                <div class="h2 mb-0 font-monospace fw-bold text-center flex-fill" id="timer-display" style="letter-spacing: 1px;">--:--:--</div>
            </div>
        </div>

        {{-- Right: User Info --}}
        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-sm-block">
                <div class="fw-bold mb-0" style="line-height: 1.1;">{{ auth()->user()->name }}</div>
                <div class="text-muted small">Peserta Ujian</div>
            </div>
            <span class="avatar avatar-md rounded shadow-sm bg-blue-lt">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="page-wrapper bg-light">
    <div class="page-body">
        <div class="container-xl">
            <div class="row g-3 justify-content-center">

                {{-- Left: Question Area --}}
                <div class="col-lg-8">
                    
                    {{-- Card 1: Question Text & Header --}}
                    <div class="card border-0 shadow-sm mb-3 rounded-3" id="question-header-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-blue-lt px-3 py-2 fw-bold fs-4" id="mata-uji-label">Mata Uji</span>
                                <div class="text-end">
                                    <span class="text-muted small fw-bold text-uppercase">Pertanyaan</span>
                                    <div class="d-flex align-items-baseline justify-content-end gap-1">
                                        <span class="h1 mb-0 text-primary fw-black" id="qnum-current">1</span>
                                        <span class="text-muted h3 mb-0">/ {{ count($paketSoal) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-dark fs-3 lh-base" id="question-text">
                                {{-- Rendered via JS --}}
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Options / Essay Container --}}
                    <div id="options-container" class="mb-3">
                        {{-- Rendered via JS --}}
                    </div>

                    {{-- Card 3: Main Navigation Buttons --}}
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <button class="btn btn-white px-md-4 border shadow-sm" id="btn-prev" onclick="goPrev()">
                                    <i class="ti ti-chevron-left me-1"></i><span class="d-none d-sm-inline">Sebelumnya</span>
                                </button>

                                <button type="button" id="doubt-btn" class="btn btn-outline-warning px-md-4 shadow-sm fw-bold border-2" onclick="toggleDoubt()">
                                    <i class="ti ti-flag shadow-sm me-1"></i>Ragu-Ragu
                                </button>

                                <button class="btn btn-primary px-md-4 shadow-sm border-0" id="btn-next" onclick="goNext()">
                                    <span class="d-none d-sm-inline">Selanjutnya</span><i class="ti ti-chevron-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Sidebar --}}
                <div class="col-lg-4">
                    
                    {{-- Navigator Card with Scroll --}}
                    <div class="card border-0 shadow-sm mb-3 rounded-3 sticky-top" style="top: 96px; z-index: 10;">
                        <div class="card-header bg-transparent border-bottom-0 pb-0">
                            <h3 class="card-title fw-bold">Navigasi Soal</h3>
                            <div class="card-actions">
                                <span class="badge bg-blue-lt" id="navigator-status">0 / {{ count($paketSoal) }} Terjawab</span>
                            </div>
                        </div>
                        <div class="card-body p-4" style="max-height: 400px; overflow-y: auto;">
                            <div class="d-flex flex-wrap gap-2 justify-content-start" id="nav-grid">
                                {{-- Rendered via JS --}}
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0 py-3">
                            <div class="d-flex gap-3 justify-content-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success rounded-pill p-1"></span>
                                    <span class="small text-muted">Dijawab</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-warning rounded-pill p-1"></span>
                                    <span class="small text-muted">Ragu</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-secondary-lt border rounded-pill p-1"></span>
                                    <span class="small text-muted">Belum</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Card --}}
                    <div class="card border-0 shadow-sm rounded-3 sticky-top" style="top: 550px; z-index: 10;">
                        <div class="card-body p-3">
                            <button class="btn btn-success w-100 btn-lg py-3 fw-bold shadow-sm" onclick="finishExam()">
                                <i class="ti ti-circle-check me-2"></i>Selesaikan Ujian
                            </button>
                        </div>
                    </div>

                </div>
            </div>
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
const VIOLATION_URL = "{{ route('cbt.api.log-violation') }}";
const END_TIME   = new Date("{{ $jadwal->waktu_selesai->toIso8601String() }}").getTime();
@if(auth()->user()->hasRole('admin'))
const RESET_URL  = "{{ route('cbt.execute.reset-admin', $jadwal->encrypted_jadwal_ujian_id) }}";
@endif

let currentIdx = 0;
let answers = JSON.parse(localStorage.getItem('cbt_ans_' + RIWAYAT_ID) || '{}');

// ══════════════════════════════════════════
//  2. INITIALIZATION
// ══════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    if (!SOAL || SOAL.length === 0) {
        document.getElementById('question-header-card').innerHTML = `
            <div class="card-body text-center py-6">
                <div class="avatar avatar-xl bg-red-lt mb-4 rounded-circle">
                    <i class="ti ti-circle-x fs-huge"></i>
                </div>
                <h3>Tidak Ada Soal Tersedia</h3>
            </div>`;
        return;
    }

    renderNavigator();
    renderQuestion(0);
    startTimer();
    initAntiCheat();
    initKeyboardShortcuts();

    window.addEventListener('offline', () => {
        document.getElementById('timer-wrapper').classList.replace('bg-dark', 'bg-danger');
    });
    window.addEventListener('online', () => {
        document.getElementById('timer-wrapper').classList.replace('bg-danger', 'bg-dark');
    });
});

// ══════════════════════════════════════════
//  3. NAVIGATOR RENDERING
// ══════════════════════════════════════════
function renderNavigator() {
    const grid = document.getElementById('nav-grid');
    grid.innerHTML = '';
    
    let completedCount = 0;
    
    SOAL.forEach((soal, i) => {
        const ans = answers[soal.soal_id] || {};
        const isAnswered = ans.opsi_id || ans.jawaban_esai;
        const isActive = (i === currentIdx);
        
        if (isAnswered) completedCount++;

        let cls = 'btn btn-md ';
        if (ans.is_ragu) cls += 'btn-warning';
        else if (isAnswered) cls += 'btn-success';
        else cls += 'btn-outline-secondary opacity-50';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.id = 'nav-cell-' + i;
        btn.className = cls + ' fw-bold ' + (isActive ? 'border-primary border-4 shadow-sm' : '');
        btn.style.width = '48px';
        btn.style.height = '48px';
        btn.style.borderRadius = '12px';
        btn.textContent = i + 1;
        btn.onclick = () => renderQuestion(i);
        
        grid.appendChild(btn);
    });

    document.getElementById('navigator-status').textContent = `${completedCount} / ${SOAL.length} Terjawab`;
}

// ══════════════════════════════════════════
//  4. QUESTION RENDERING
// ══════════════════════════════════════════
function renderQuestion(idx) {
    if (idx < 0 || idx >= SOAL.length) return;
    
    currentIdx = idx;
    const soal = SOAL[idx];
    const soalId = soal.soal_id;
    const ans = answers[soalId] || {};

    // Header labels
    document.getElementById('mata-uji-label').textContent = soal.mata_uji?.nama_mata_uji || 'MATA UJI';
    document.getElementById('qnum-current').textContent = idx + 1;

    // Transition effect
    document.getElementById('question-header-card').classList.add('opacity-50');
    
    setTimeout(() => {
        document.getElementById('question-text').innerHTML = soal.konten_pertanyaan || soal.pertanyaan || '';

        // Doubt State
        const doubtBtn = document.getElementById('doubt-btn');
        if (doubtBtn) {
            doubtBtn.className = ans.is_ragu 
                ? 'btn btn-warning px-md-4 shadow-sm fw-bold border-2' 
                : 'btn btn-outline-warning px-md-4 shadow-sm fw-bold border-2';
        }

        // Render Options
        const wrapper = document.getElementById('options-container');
        wrapper.innerHTML = '';

        if (soal.tipe_soal === 'Pilihan_Ganda' || soal.tipe_soal === 'Benar_Salah') {
            const listGroup = document.createElement('div');
            listGroup.className = 'd-flex flex-column gap-2';
            
            const options = soal.opsi_jawaban || [];
            options.forEach((opt, i) => {
                const label = ['A', 'B', 'C', 'D', 'E'][i] || i + 1;
                const active = ans.opsi_id == opt.opsi_jawaban_id;
                
                const optEl = document.createElement('div');
                optEl.className = `card card-sm border-0 shadow-sm rounded-3 transition-all cursor-pointer mb-0 ${active ? 'bg-primary text-white scale-up' : 'bg-white text-dark hover-shadow'}`;
                optEl.style.cursor = 'pointer';
                optEl.onclick = () => pickOption(soalId, opt.opsi_jawaban_id, optEl);
                
                optEl.innerHTML = `
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="avatar avatar-sm ${active ? 'bg-white text-primary' : 'bg-light text-muted border'} fw-bold rounded-circle shadow-sm">
                            ${label}
                        </div>
                        <div class="fs-3 ${active ? 'fw-bold text-white' : 'text-secondary'}">${opt.teks_jawaban}</div>
                        ${active ? '<div class="ms-auto"><i class="ti ti-circle-check-filled text-white fs-2 animate-bounce"></i></div>' : ''}
                    </div>
                `;
                listGroup.appendChild(optEl);
            });
            wrapper.appendChild(listGroup);
        } else {
            wrapper.innerHTML = `
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <textarea class="form-control border-0 p-4 fs-3 h-auto" placeholder="Tulis jawaban esai Anda di sini..." id="essay-input" rows="8"
                        oninput="saveEssay(${soalId}, this.value)">${ans.jawaban_esai || ''}</textarea>
                </div>`;
        }

        // Button States
        document.getElementById('btn-prev').disabled = (idx === 0);
        const btnNext = document.getElementById('btn-next');
        if (idx === SOAL.length - 1) {
            btnNext.innerHTML = '<i class="ti ti-check me-1"></i>Selesaikan';
            btnNext.className = 'btn btn-success px-md-4 shadow-sm fw-bold';
        } else {
            btnNext.innerHTML = '<span class="d-none d-sm-inline">Selanjutnya</span><i class="ti ti-chevron-right ms-1"></i>';
            btnNext.className = 'btn btn-primary px-md-4 shadow-sm';
        }

        document.getElementById('question-header-card').classList.remove('opacity-50');
    }, 100);
}

// ══════════════════════════════════════════
//  5. ACTION HANDLERS
// ══════════════════════════════════════════
function goPrev() { if (currentIdx > 0) renderQuestion(currentIdx - 1); }
function goNext() { (currentIdx === SOAL.length - 1) ? finishExam() : renderQuestion(currentIdx + 1); }

window.pickOption = (soalId, opsiId, el) => {
    if (!answers[soalId]) answers[soalId] = {};
    answers[soalId] = { ...answers[soalId], opsi_id: opsiId };
    saveState(soalId, answers[soalId]);
    renderQuestion(currentIdx); // Re-render to update UI consistency
};

window.saveEssay = (soalId, val) => {
    if (!answers[soalId]) answers[soalId] = {};
    answers[soalId] = { ...answers[soalId], jawaban_esai: val };
    saveState(soalId, answers[soalId]);
};

window.toggleDoubt = () => {
    const soalId = SOAL[currentIdx]?.soal_id;
    if (!soalId) return;
    if (!answers[soalId]) answers[soalId] = {};
    answers[soalId].is_ragu = !answers[soalId].is_ragu;
    saveState(soalId, answers[soalId]);
    renderQuestion(currentIdx);
};

// ══════════════════════════════════════════
//  6. DATA PERSISTENCE
// ══════════════════════════════════════════
function saveState(soalId, data) {
    localStorage.setItem('cbt_ans_' + RIWAYAT_ID, JSON.stringify(answers));
    updateNavUI();
    
    fetch(SAVE_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({
            soal_id: soalId,
            opsi_id: data.opsi_id || null,
            jawaban_esai: data.jawaban_esai || null,
            is_ragu: !!data.is_ragu
        })
    }).catch(e => console.warn('Background sync failed.', e));
}

function updateNavUI() {
    let completed = 0;
    SOAL.forEach((s, i) => {
        const a = answers[s.soal_id] || {};
        const isAnswered = a.opsi_id || a.jawaban_esai;
        const isActive = (i === currentIdx);
        if (isAnswered) completed++;
        
        const cell = document.getElementById('nav-cell-' + i);
        if (cell) {
            cell.className = 'btn btn-md fw-bold ' + 
                            (a.is_ragu ? 'btn-warning' : (isAnswered ? 'btn-success' : 'btn-outline-secondary opacity-50')) +
                            (isActive ? ' border-primary border-4 shadow-sm scale-up' : '');
        }
    });
    document.getElementById('navigator-status').textContent = `${completed} / ${SOAL.length} Terjawab`;
}

// ══════════════════════════════════════════
//  7. SUBMIT & TIMER
// ══════════════════════════════════════════
window.finishExam = (isAuto = false) => {
    const unanswered = SOAL.filter(s => { const a = answers[s.soal_id] || {}; return !a.opsi_id && !a.jawaban_esai; }).length;
    
    if (isAuto) {
        performSubmit();
    } else {
        Swal.fire({
            title: 'Selesaikan Ujian?',
            text: unanswered > 0 
                ? `Masih ada ${unanswered} soal yang belum dijawab. Apakah Anda yakin ingin mengakhiri sesi ujian ini?` 
                : 'Apakah Anda yakin ingin menyelesaikan ujian ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2fb344',
            cancelButtonColor: '#d63939',
            confirmButtonText: '<i class="ti ti-check me-1"></i>Ya, Selesaikan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                performSubmit();
            }
        });
    }

    function performSubmit() {
        const btn = document.querySelector('.btn-success.btn-lg');
        if (btn) btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

        fetch(SUBMIT_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({})
        })
        .then(r => r.json()).then(res => {
            if (res.success || res.status === 'success') {
                localStorage.removeItem('cbt_ans_' + RIWAYAT_ID);
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Jawaban Anda telah berhasil dikirim.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = res.redirect || res.url || '/cbt';
                });
            } else {
                Swal.fire('Gagal!', res.message || 'Terjadi kesalahan saat mengirim jawaban.', 'error');
            }
        }).catch(err => {
            Swal.fire('Terputus!', 'Koneksi gagal. Pastikan internet aktif dan coba lagi.', 'error');
        });
    }
};

function startTimer() {
    const display = document.getElementById('timer-display');
    const wrapper = document.getElementById('timer-wrapper');
    const icon    = document.getElementById('timer-icon');
    
    setInterval(() => {
        const diff = END_TIME - Date.now();
        if (diff <= 0) { finishExam(true); return; }

        const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
        const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
        const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        
        if(display) display.textContent = `${h}:${m}:${s}`;

        if (diff < 300000) { // < 5 min
            wrapper.classList.replace('text-white', 'text-warning');
            icon.classList.replace('text-primary', 'text-warning');
        }
        if (diff < 60000) { // < 1 min
            wrapper.classList.replace('text-warning', 'text-danger');
            icon.classList.replace('text-warning', 'text-danger');
            wrapper.classList.add('animate-pulse');
        }
    }, 1000);
}
// ══════════════════════════════════════════
//  10. ANTI-CHEAT LOGIC
// ══════════════════════════════════════════
function initAntiCheat() {
    // 1. Block Context Menu & Clipboard
    document.addEventListener('contextmenu', e => {
        e.preventDefault();
        showWarning('Klik kanan dinonaktifkan demi keamanan ujian.');
    });
    document.addEventListener('copy', e => {
        e.preventDefault();
        showWarning('Dilarang menyalin teks soal/jawaban.');
    });
    document.addEventListener('cut', e => e.preventDefault());
    document.addEventListener('paste', e => {
        e.preventDefault();
        showWarning('Dilarang menempelkan teks dari luar.');
    });

    // 2. Tab/Window Switch Detection
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            logViolation('PINDAH_TAB', 'Peserta meninggalkan tab ujian.');
        }
    });

    window.addEventListener('blur', () => {
        logViolation('PINDAH_WINDOW', 'Peserta berpindah jendela/aplikasi.');
    });

    window.onbeforeunload = function() {
        return "Apakah Anda yakin ingin meninggalkan halaman? Perubahan yang belum tersimpan mungkin hilang.";
    };
}


function logViolation(type, keterangan) {
    if (typeof finishExamCalled !== 'undefined' && finishExamCalled) return;

    fetch(VIOLATION_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type, keterangan })
    }).catch(e => console.warn('Violation logging failed.', e));

    showWarning(`PERINGATAN! Aktivitas mencurigakan terdeteksi: ${keterangan} Kejadian ini telah dicatat.`);
}

function showWarning(msg) {
    Swal.fire({
        title: 'Anti-Cheat System',
        text: msg,
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Saya Mengerti'
    });
}

// Global flag to prevent warning on normal submit
let finishExamCalled = false;
const originalFinishExam = window.finishExam;
window.finishExam = function(isAuto) {
    finishExamCalled = true;
    window.onbeforeunload = null;
    originalFinishExam(isAuto);
};
// ══════════════════════════════════════════
//  11. KEYBOARD SHORTCUTS
// ══════════════════════════════════════════
function initKeyboardShortcuts() {
    document.addEventListener('keydown', (e) => {
        // Only trigger if not typing in essay
        if (document.activeElement.tagName === 'TEXTAREA') return;

        // Navigation
        if (e.key === 'ArrowLeft') goPrev();
        if (e.key === 'ArrowRight') goNext();

        // Answer selection (A=65, B=66, C=67, etc.)
        const key = e.key.toUpperCase();
        if (['A', 'B', 'C', 'D', 'E'].includes(key)) {
            const idx = ['A', 'B', 'C', 'D', 'E'].indexOf(key);
            const soal = SOAL[currentIdx];
            if (soal && soal.opsi_jawaban && soal.opsi_jawaban[idx]) {
                pickOption(soal.soal_id, soal.opsi_jawaban[idx].opsi_jawaban_id);
            }
        }
    });
}

</script>

<style>
body { user-select: none; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; }
body.modal-open, body.swal2-shown { overflow: hidden !important; }
.transition-all { transition: all 0.25s ease; }
.cursor-pointer { cursor: pointer; }
.hover-shadow:hover { box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important; transform: translateY(-2px); }
.scale-up { transform: scale(1.01); }
.animate-pulse { animation: pulse 1.5s infinite; }
@keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.7; } 100% { opacity: 1; } }
.animate-bounce { animation: bounce 0.5s; }
@keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }
#nav-grid button { transition: all 0.2s ease; }
#nav-grid button:hover { transform: scale(1.1); }
</style>
@endpush
