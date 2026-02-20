@extends('layouts.tabler.app')

@push('css')
<style>
    :root {
        --tabler-font-family: 'Inter', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }
    
    body {
        background-color: #f6f8fb;
        font-family: var(--tabler-font-family);
    }

    .exam-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .ques-navigator {
        height: calc(100vh - 350px);
        overflow-y: auto;
        padding-right: 5px;
    }
    
    .ques-navigator::-webkit-scrollbar {
        width: 4px;
    }
    
    .ques-navigator::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 10px;
    }

    .ques-number {
        width: 100%;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #e6e7e9;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        color: #626976;
    }
    
    .ques-number:hover { 
        border-color: #206bc4;
        color: #206bc4;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .ques-number.active { 
        background-color: #206bc4; 
        color: white; 
        border-color: #206bc4;
        box-shadow: 0 4px 10px rgba(32, 107, 196, 0.3);
    }
    
    .ques-number.answered { 
        background-color: #2fb344; 
        color: white; 
        border-color: #2fb344; 
    }
    
    .ques-number.ragu { 
        background-color: #f76707; 
        color: white; 
        border-color: #f76707; 
    }
    
    .floating-timer {
        background: linear-gradient(135deg, #206bc4 0%, #1a569d 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(32, 107, 196, 0.2);
        letter-spacing: 1px;
    }
    
    .floating-timer.warning {
        background: linear-gradient(135deg, #f76707 0%, #ca5406 100%);
    }
    
    .floating-timer.danger {
        background: linear-gradient(135deg, #d63939 0%, #b02e2e 100%);
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
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
        padding: 12px 24px;
        border-radius: 50px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        font-weight: 600;
    }

    .question-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
        transition: opacity 0.3s ease;
    }
    
    .question-fade {
        animation: fadeIn 0.4s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .option-item {
        border-radius: 10px;
        border: 2px solid #f1f5f9;
        padding: 1rem 1.25rem;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        background: white;
        display: flex;
        align-items: center;
    }

    .option-item:hover {
        border-color: #cbd5e1;
        background-color: #f8fafc;
    }

    .option-item.selected {
        border-color: #206bc4;
        background-color: #f0f7ff;
    }

    .option-label {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f1f5f9;
        border-radius: 50%;
        margin-right: 1rem;
        font-weight: 700;
        color: #475569;
        flex-shrink: 0;
    }

    .option-item.selected .option-label {
        background-color: #206bc4;
        color: white;
    }

    .option-text {
        font-size: 1.05rem;
        color: #1e293b;
    }

    .sticky-header {
        position: sticky;
        top: 0;
        z-index: 100;
        background-color: transparent;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="offline-indicator" id="offline-alert">
    <strong>Koneksi Terputus!</strong> Jawaban Anda tetap aman di browser.
</div>

<div class="page-body">
    <div class="container-xl exam-container">
        <div class="row g-4">
            {{-- Main Content --}}
            <div class="col-lg-9">
                <div class="sticky-header">
                    <div class="card card-sm mb-0 shadow-sm border-0">
                        <div class="card-body py-2 px-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary-lt p-2 rounded-circle d-none d-md-block">
                                            <i class="ti ti-school text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Sesi Ujian Aktif</div>
                                            <div class="h4 mb-0 fw-bold">{{ $jadwal->nama_kegiatan }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="text-end d-none d-sm-block">
                                            <div class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem;">Sisa Waktu</div>
                                            <div class="fw-bold text-dark h3 mb-0" id="countdown-text">00:00:00</div>
                                        </div>
                                        <div class="floating-timer d-flex align-items-center" id="countdown">
                                            <i class="ti ti-clock-hour-4 me-2"></i>
                                            <span id="countdown-compact">00:00:00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="progress progress-sm card-progress" id="exam-progress-bar">
                            <div class="progress-bar bg-primary" style="width: 0%" role="progressbar"></div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3 shadow-sm border-0 question-card" id="main-card" style="min-height: 500px;">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pt-4 pb-0">
                        <div>
                            <span class="badge bg-blue-lt px-3 py-2 rounded-pill fw-bold">
                                <i class="ti ti-list-details me-1"></i> <span id="current-mata-uji">Mata Uji</span>
                            </span>
                        </div>
                        <div class="text-muted fw-bold d-flex align-items-baseline gap-2">
                            <span class="text-muted h4 mb-0">No.</span>
                            <span id="display-number" class="text-primary h1 mb-0 lh-1">1</span>
                            <span class="text-muted small">/ {{ count($paketSoal) }}</span>
                        </div>
                    </div>
                    
                    <div class="card-body px-4 py-4">
                        <div id="question-content" class="mb-5" style="font-size: 1.15rem; line-height: 1.7; color: #334155; font-weight: 450;">
                            {{-- Pertanyaan di-render via JS --}}
                        </div>

                        <div id="options-container" class="row">
                            {{-- Opsi di-render via JS --}}
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0 pb-5 pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <x-tabler.button class="btn-outline-secondary btn-lg px-4 rounded-pill" id="btn-prev" icon="ti ti-arrow-left" text="Sebelumnya" />
                            
                            <div class="d-none d-md-block">
                                <label class="btn btn-outline-warning border-2 btn-lg px-4 rounded-pill transition-all cursor-pointer d-flex align-items-center gap-2">
                                    <input type="checkbox" id="check-ragu" class="form-check-input m-0" style="width: 1.2rem; height: 1.2rem;">
                                    <span class="fw-bold">RAGU-RAGU</span>
                                </label>
                            </div>

                            <x-tabler.button class="btn-primary btn-lg px-5 rounded-pill shadow-sm" id="btn-next" text="Selanjutnya" icon="ti ti-arrow-right" trailing-icon="true" />
                        </div>
                        <div class="text-center mt-4 d-md-none">
                            <label class="btn btn-outline-warning border-2 btn-sm px-4 rounded-pill cursor-pointer d-flex align-items-center gap-2 justify-content-center">
                                <input type="checkbox" id="check-ragu-mobile" class="form-check-input m-0">
                                <span class="fw-bold">RAGU-RAGU</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-3">
                <div class="card card-stacked mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Navigasi Soal</h3>
                    </div>
                    <div class="card-body ques-navigator">
                        <div class="row g-2" id="navigator-grid">
                            {{-- Navigator cells di-render via JS --}}
                        </div>
                    </div>
                    <div class="card-footer">
                        <x-tabler.button class="btn-success w-100 btn-lg" id="btn-finish" icon="ti ti-check" text="Selesaikan Ujian" />

                        @if(auth()->user()->hasRole('admin'))
                        <x-tabler.button type="button" class="btn-outline-danger w-100 mt-3" onclick="resetAdminData()" icon="ti ti-refresh" text="Reset Data Testing" />
                        @endif
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-sm rounded-circle me-2 bg-blue-lt">
                                <i class="ti ti-user"></i>
                            </span>
                            <div>
                                <div class="font-weight-bold">{{ auth()->user()->name }}</div>
                                <div class="small text-muted">{{ auth()->user()->username }}</div>
                            </div>
                        </div>
                        
                        <div class="hr-text mt-2 mb-3">Legenda</div>
                        <div class="row g-2 small">
                            <div class="col-6 d-flex align-items-center gap-2">
                                <span class="badge bg-green p-1 rounded-circle" style="width:10px; height:10px"></span> Dijawab
                            </div>
                            <div class="col-6 d-flex align-items-center gap-2">
                                <span class="badge bg-orange p-1 rounded-circle" style="width:10px; height:10px"></span> Ragu
                            </div>
                            <div class="col-6 d-flex align-items-center gap-2">
                                <span class="badge bg-white border p-1 rounded-circle" style="width:10px; height:10px"></span> Belum
                            </div>
                            <div class="col-6 d-flex align-items-center gap-2">
                                <span class="badge bg-blue p-1 rounded-circle" style="width:10px; height:10px"></span> Aktif
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->hasRole('admin'))
                <div class="card mt-3 bg-red-lt border-red">
                    <div class="card-body p-3 text-center">
                        <div class="mb-2 text-red font-weight-bold"><i class="ti ti-shield-lock me-1"></i> Admin Panel</div>
                        <p class="small mb-3">Hapus history Anda sendiri untuk jadwal ini dan mulai ulang testing.</p>
                        <x-tabler.button class="btn-red w-100" id="btn-reset-admin" icon="ti ti-refresh" text="Reset My Progress" />
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // --- 1. DATA & STATE ---
    const soalData = {!! $paketSoal->toJson() !!};
    const riwayatId = "{{ $riwayat->encrypted_riwayat_ujian_id }}";
    const saveUrl = "{{ route('cbt.execute.save', $riwayat->encrypted_riwayat_ujian_id) }}";
    const submitUrl = "{{ route('cbt.execute.submit', $riwayat->encrypted_riwayat_ujian_id) }}";
    @if(auth()->user()->hasRole('admin'))
    const resetUrl = "{{ route('cbt.execute.reset-admin', $jadwal->encrypted_jadwal_ujian_id) }}";
    @endif
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
            const isAnswered = answers[soal.id] && (answers[soal.id].opsi_id || answers[soal.id].jawaban_esai);
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
        const mainCard = document.getElementById('main-card');
        mainCard.classList.remove('question-fade');
        void mainCard.offsetWidth; // Trigger reflow
        mainCard.classList.add('question-fade');

        currentIndex = index;
        const soal = soalData[index];
        const ans = answers[soal.id] || {};

        document.getElementById('display-number').innerText = index + 1;
        document.getElementById('current-mata-uji').innerText = soal.mata_uji ? soal.mata_uji.nama_mata_uji : "UMUM";
        document.getElementById('question-content').innerHTML = soal.konten_pertanyaan;
        
        const isRagu = ans.is_ragu || false;
        document.getElementById('check-ragu').checked = isRagu;
        const checkMobile = document.getElementById('check-ragu-mobile');
        if (checkMobile) checkMobile.checked = isRagu;

        const optionsHtml = document.getElementById('options-container');
        optionsHtml.innerHTML = '';

        if (soal.tipe_soal === 'Pilihan_Ganda') {
            soal.opsi_jawaban.forEach(opsi => {
                const isSelected = ans.opsi_id === opsi.id;
                optionsHtml.innerHTML += `
                    <div class="col-md-12 mb-2">
                        <div class="option-item ${isSelected ? 'selected' : ''}" onclick="pickOption(${soal.id}, ${opsi.id}, this)">
                            <div class="option-label shadow-sm">${opsi.label}</div>
                            <div class="option-text">${opsi.teks_jawaban}</div>
                        </div>
                    </div>`;
            });
        } else if (soal.tipe_soal === 'Benar_Salah') {
             const options = [
                 { id: 'benar', label: 'A', text: 'BENAR' },
                 { id: 'salah', label: 'B', text: 'SALAH' }
             ];
             options.forEach(opt => {
                 const isSelected = ans.opsi_id == opt.id;
                 optionsHtml.innerHTML += `
                    <div class="col-md-6 mb-2">
                        <div class="option-item ${isSelected ? 'selected' : ''}" onclick="pickOption(${soal.id}, '${opt.id}', this)">
                            <div class="option-label shadow-sm">${opt.label}</div>
                            <div class="option-text fw-bold">${opt.text}</div>
                        </div>
                    </div>`;
             });
        } else {
            // Essay
            const val = ans.jawaban_esai || '';
            optionsHtml.innerHTML = `<div class="col-12"><textarea class="form-control form-control-lg border-2" rows="10" oninput="saveEssay(${soal.id}, this.value)" placeholder="Tuliskan jawaban lengkap Anda di sini..." style="border-radius: 12px;">${val}</textarea></div>`;
        }
        
        // Update Progress Bar
        const progress = ((index + 1) / soalData.length) * 100;
        document.querySelector('#exam-progress-bar .progress-bar').style.width = progress + '%';

        renderNavigator();
        updateNavButtons();
    }

    function updateNavButtons() {
        document.getElementById('btn-prev').disabled = (currentIndex === 0);
        const nextBtn = document.getElementById('btn-next');
        if (currentIndex === soalData.length - 1) {
            nextBtn.innerHTML = 'Selesai <i class="ti ti-check ms-2"></i>';
            nextBtn.classList.replace('btn-primary', 'btn-success');
            nextBtn.onclick = () => finishExam();
        } else {
            nextBtn.innerHTML = 'Selanjutnya <i class="ti ti-chevron-right ms-2"></i>';
            nextBtn.classList.replace('btn-success', 'btn-primary');
            nextBtn.onclick = () => renderQuestion(currentIndex + 1);
        }
    }

    // --- 4. ACTIONS ---
    window.goToQuestion = (index) => renderQuestion(index);
    document.getElementById('btn-prev').onclick = () => { if (currentIndex > 0) renderQuestion(currentIndex - 1); };
    
    window.pickOption = (soalId, opsiId, el) => {
        // UI feedback
        document.querySelectorAll('.option-item').forEach(i => i.classList.remove('selected'));
        el.classList.add('selected');
        
        saveAnswer(soalId, { opsi_id: opsiId });
    };

    window.saveEssay = (soalId, val) => {
        saveAnswer(soalId, { jawaban_esai: val });
    };

    document.getElementById('check-ragu').onchange = (e) => syncRagu(e.target.checked);
    const checkMobile = document.getElementById('check-ragu-mobile');
    if (checkMobile) checkMobile.onchange = (e) => syncRagu(e.target.checked);

    function syncRagu(checked) {
        const soalId = soalData[currentIndex].id;
        if (!answers[soalId]) answers[soalId] = {};
        answers[soalId].is_ragu = checked;
        
        // Sync UI
        document.getElementById('check-ragu').checked = checked;
        const cm = document.getElementById('check-ragu-mobile');
        if (cm) cm.checked = checked;

        saveAnswer(soalId, answers[soalId]);
    }

    // --- 5. SYNC & PERSISTENCE ---
    function saveAnswer(soalId, partialData) {
        // Merge partial data
        if (!answers[soalId]) answers[soalId] = {};
        answers[soalId] = { ...answers[soalId], ...partialData };
        
        // Local Save
        localStorage.setItem('cbt_ans_' + riwayatId, JSON.stringify(answers));
        
        // Background Sync
        syncToBackend(soalId, answers[soalId]);
        renderNavigator();
    }

    function syncToBackend(soalId, data) {
        $.post(saveUrl, {
            _token: "{{ csrf_token() }}",
            soal_id: soalId,
            opsi_id: data.opsi_id,
            jawaban_esai: data.jawaban_esai,
            is_ragu: data.is_ragu
        }).fail(() => {
            syncQueue.push({ soalId, ...data });
        });
    }

    // Admin reset functionality
    @if(auth()->user()->hasRole('admin'))
    document.getElementById('btn-reset-admin').onclick = function() {
        if (confirm('Aksi ini akan menghapus seluruh progres testing Anda (history jawaban & rekam jejak). Lanjutkan?')) {
            $.post(resetUrl, { _token: "{{ csrf_token() }}" }, function(res) {
                if (res.status === 'success') {
                    localStorage.removeItem('cbt_ans_' + riwayatId);
                    window.location.reload();
                } else {
                    alert(res.message);
                }
            });
        }
    };
    @endif

    // --- 6. TIMER & UTILS ---
    function startTimer() {
        const timerEl = document.getElementById('countdown');
        const textEl = document.getElementById('countdown-text');
        const compactEl = document.getElementById('countdown-compact');
        
        const interval = setInterval(() => {
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance < 0) {
                clearInterval(interval);
                const msg = "WAKTU HABIS";
                if (textEl) textEl.innerHTML = msg;
                if (compactEl) compactEl.innerHTML = msg;
                timerEl.classList.add('danger');
                finishExam(true);
                return;
            }

            const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((distance % (1000 * 60)) / 1000);
            
            const timeStr = (h < 10 ? "0"+h : h) + ":" + (m < 10 ? "0"+m : m) + ":" + (s < 10 ? "0"+s : s);
            
            if (textEl) textEl.innerHTML = timeStr;
            if (compactEl) compactEl.innerHTML = timeStr;
            
            if (distance < 300000) timerEl.classList.add('warning');
            if (distance < 60000) timerEl.classList.add('danger');
        }, 1000);
    }

    function checkOnlineStatus() {
        window.addEventListener('online', () => document.getElementById('offline-alert').style.display = 'none');
        window.addEventListener('offline', () => document.getElementById('offline-alert').style.display = 'block');
    }

    function finishExam(isAuto = false) {
        let msg = "Apakah Anda yakin ingin mengakhiri ujian sekarang?";
        if (isAuto) msg = "WAKTU HABIS! Pekerjaan Anda akan dikirimkan otomatis sekarang.";

        if (isAuto || confirm(msg)) {
            $.post(submitUrl, { _token: "{{ csrf_token() }}" }, function(res) {
                if (res.status === 'success') {
                    localStorage.removeItem('cbt_ans_' + riwayatId);
                    window.location.href = res.redirect;
                }
            });
        }
    }

    @if(auth()->user()->hasRole('admin'))
    window.resetAdminData = function() {
        Swal.fire({
            title: 'Reset Data Testing?',
            text: "Data jawaban dan log pelanggaran Anda akan dihapus secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('cbt.execute.reset-admin', $jadwal->encrypted_jadwal_ujian_id) }}",
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        if (res.status === 'success') {
                            toastr.success(res.message);
                            localStorage.clear(); 
                            window.location.href = res.redirect;
                        } else {
                            toastr.error(res.message);
                        }
                    }
                });
            }
        });
    }
    @endif

    $(document).ready(init);
</script>
@endpush
