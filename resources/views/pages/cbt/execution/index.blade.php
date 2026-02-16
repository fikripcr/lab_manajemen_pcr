@extends('layouts.admin.app')

@push('css')
<style>
    .ques-navigator {
        height: calc(100vh - 250px);
        overflow-y: auto;
    }
    .ques-number {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.2s;
    }
    .ques-number:hover { background-color: #f0f0f0; }
    .ques-number.active { background-color: #206bc4; color: white; border-color: #206bc4; }
    .ques-number.answered { background-color: #2fb344; color: white; border-color: #2fb344; }
    .ques-number.ragu { background-color: #f76707; color: white; border-color: #f76707; }
    
    .floating-timer {
        background: #206bc4;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: bold;
        font-family: monospace;
        font-size: 1.2rem;
    }
    .offline-indicator {
        display: none;
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        background: #d63939;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="offline-indicator" id="offline-alert">
    <strong>Koneksi Terputus!</strong> Jawaban disimpan sementara di browser Anda. Jangan tutup halaman ini.
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row g-4">
            {{-- Main Content --}}
            <div class="col-md-9">
                <div class="card mb-3" style="min-height: 500px;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-blue-lt">Mata Uji: <span id="current-mata-uji">...</span></span>
                            <span class="ms-2 fw-bold">Soal Nomor <span id="display-number">1</span></span>
                        </div>
                        <div class="floating-timer" id="countdown">00:00:00</div>
                    </div>
                    <div class="card-body">
                        <div id="question-content" class="mb-4" style="font-size: 1.1rem;">
                            {{-- Pertanyaan di-render via JS --}}
                        </div>

                        <div id="options-container" class="space-y">
                            {{-- Opsi di-render via JS --}}
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-outline-secondary" id="btn-prev">
                            <i class="ti ti-chevron-left"></i> Sebelumnya
                        </button>
                        
                        <label class="form-check form-check-inline m-0 align-self-center">
                            <input class="form-check-input" type="checkbox" id="check-ragu">
                            <span class="form-check-label text-warning fw-bold">RAGU-RAGU</span>
                        </label>

                        <button class="btn btn-primary" id="btn-next">
                            Selanjutnya <i class="ti ti-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Sidebar Navigator --}}
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Navigasi Soal</h3>
                    </div>
                    <div class="card-body ques-navigator">
                        <div class="row g-2" id="navigator-grid">
                            {{-- Navigator cells di-render via JS --}}
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-danger w-100" id="btn-finish">
                            <i class="ti ti-check"></i> Selesaikan Ujian
                        </button>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-body p-2 text-center small text-muted">
                        <div class="d-flex justify-content-center gap-2 mb-2">
                             <div class="d-flex align-items-center gap-1"><span class="ques-number answered" style="width:15px; height:15px"></span> Dijawab</div>
                             <div class="d-flex align-items-center gap-1"><span class="ques-number ragu" style="width:15px; height:15px"></span> Ragu</div>
                             <div class="d-flex align-items-center gap-1"><span class="ques-number" style="width:15px; height:15px"></span> Belum</div>
                        </div>
                        ID Peserta: {{ auth()->user()->username }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // --- 1. DATA & STATE ---
    const soalData = {!! $paketSoal->toJson() !!};
    const riwayatId = "{{ $riwayat->encrypted_id }}";
    const saveUrl = "{{ route('cbt.execute.save', $riwayat->encrypted_id) }}";
    const submitUrl = "{{ route('cbt.execute.submit', $riwayat->encrypted_id) }}";
    const endTime = new Date("{{ $jadwal->waktu_selesai }}").getTime();
    
    let currentIndex = 0;
    let answers = JSON.parse(localStorage.getItem('cbt_ans_' + riwayatId)) || {};
    let syncQueue = [];

    // --- 2. INITIALIZATION ---
    function init() {
        renderNavigator();
        renderQuestion(0);
        startTimer();
        checkOnlineStatus();
    }

    // --- 3. RENDERING ---
    function renderNavigator() {
        const grid = document.getElementById('navigator-grid');
        grid.innerHTML = '';
        soalData.forEach((soal, index) => {
            const isAnswered = answers[soal.id] && answers[soal.id].opsi_id;
            const isRagu = answers[soal.id] && answers[soal.id].is_ragu;
            
            let statusClass = '';
            if (isAnswered) statusClass = ' answered';
            if (isRagu) statusClass = ' ragu';
            if (index === currentIndex) statusClass += ' active';

            grid.innerHTML += `
                <div class="col-3">
                    <div class="ques-number${statusClass}" onclick="goToQuestion(${index})">${index + 1}</div>
                </div>`;
        });
    }

    function renderQuestion(index) {
        currentIndex = index;
        const soal = soalData[index];
        const ans = answers[soal.id] || {};

        document.getElementById('display-number').innerText = index + 1;
        document.getElementById('current-mata-uji').innerText = soal.mata_uji_id; // Need to map to name
        document.getElementById('question-content').innerHTML = soal.konten_pertanyaan;
        document.getElementById('check-ragu').checked = ans.is_ragu || false;

        const optionsHtml = document.getElementById('options-container');
        optionsHtml.innerHTML = '';

        if (soal.tipe_soal === 'Pilihan_Ganda') {
            soal.opsi_jawaban.forEach(opsi => {
                const isChecked = ans.opsi_id === opsi.id ? 'checked' : '';
                optionsHtml.innerHTML += `
                    <label class="form-check mb-2 p-3 border rounded">
                        <input class="form-check-input" type="radio" name="answer" value="${opsi.id}" ${isChecked} onchange="saveAnswer(${soal.id}, ${opsi.id})">
                        <span class="form-check-label">
                            <strong>${opsi.label}.</strong> ${opsi.teks_jawaban}
                        </span>
                    </label>`;
            });
        }
        
        renderNavigator();
    }

    // --- 4. NAVIGATION ---
    window.goToQuestion = (index) => renderQuestion(index);
    document.getElementById('btn-prev').onclick = () => { if (currentIndex > 0) renderQuestion(currentIndex - 1); };
    document.getElementById('btn-next').onclick = () => { if (currentIndex < soalData.length - 1) renderQuestion(currentIndex + 1); };
    document.getElementById('check-ragu').onchange = (e) => {
        const soalId = soalData[currentIndex].id;
        if (!answers[soalId]) answers[soalId] = {};
        answers[soalId].is_ragu = e.target.checked;
        saveAnswer(soalId, answers[soalId].opsi_id);
    };

    // --- 5. SYNC & PERSISTENCE ---
    window.saveAnswer = (soalId, opsiId) => {
        const isRagu = document.getElementById('check-ragu').checked;
        
        // Update Local State & Storage
        answers[soalId] = { opsi_id: opsiId, is_ragu: isRagu };
        localStorage.setItem('cbt_ans_' + riwayatId, JSON.stringify(answers));
        
        // Add to Sync Queue
        syncToBackend(soalId, opsiId, isRagu);
        renderNavigator();
    };

    function syncToBackend(soalId, opsiId, isRagu) {
        $.post(saveUrl, {
            _token: "{{ csrf_token() }}",
            soal_id: soalId, // Will handle encryption server-side if needed, but passing raw ID here for simplicity in JS logic
            opsi_id: opsiId,
            is_ragu: isRagu
        }).fail(() => {
            syncQueue.push({ soalId, opsiId, is_ragu });
        });
    }

    // Background Sync Loop
    setInterval(() => {
        if (navigator.onLine && syncQueue.length > 0) {
            const item = syncQueue.shift();
            syncToBackend(item.soalId, item.opsiId, item.is_ragu);
        }
    }, 5000);

    // --- 6. UTILS & EXTRAS ---
    function startTimer() {
        const countdown = document.getElementById('countdown');
        setInterval(() => {
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance < 0) {
                countdown.innerHTML = "WAKTU HABIS";
                finishExam(true);
                return;
            }

            const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((distance % (1000 * 60)) / 1000);
            
            countdown.innerHTML = (h < 10 ? "0"+h : h) + ":" + (m < 10 ? "0"+m : m) + ":" + (s < 10 ? "0"+s : s);
        }, 1000);
    }

    function checkOnlineStatus() {
        window.addEventListener('online', () => document.getElementById('offline-alert').style.display = 'none');
        window.addEventListener('offline', () => document.getElementById('offline-alert').style.display = 'block');
    }

    document.getElementById('btn-finish').onclick = () => finishExam();

    function finishExam(isAuto = false) {
        let msg = "Apakah Anda yakin ingin menyelesaikan ujian?";
        if (isAuto) msg = "Waktu habis! Ujian akan diserahkan secara otomatis.";

        if (isAuto || confirm(msg)) {
            $.post(submitUrl, { _token: "{{ csrf_token() }}" }, function(res) {
                if (res.status === 'success') {
                    localStorage.removeItem('cbt_ans_' + riwayatId);
                    window.location.href = res.redirect;
                }
            });
        }
    }

    $(document).ready(init);
</script>
@endpush
