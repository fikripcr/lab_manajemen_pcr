{{-- EXAM INTERFACE FOR CAMABA --}}
@php
    $user = auth()->user();
    $pendaftaran = App\Models\Pmb\Pendaftaran::with(['pesertaUjian.sesiUjian'])
        ->where('user_id', $user->id)
        ->where('status_terkini', 'Siap_Ujian')
        ->first();
    
    if (!$pendaftaran) {
        abort(403, 'Anda tidak memiliki akses ke ujian');
    }
    
    $pesertaUjian = $pendaftaran->pesertaUjian;
    $sesiUjian = $pesertaUjian->sesiUjian;
    $paketUjian = $sesiUjian->paket;
    
    // Get questions
    $questions = App\Models\Cbt\KomposisiPaket::with(['soal.opsiJawaban'])
        ->where('paket_id', $paketUjian->id)
        ->orderBy('urutan_tampil')
        ->get();
@endphp

@if(!$pendaftaran)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <img src="{{ asset('static/illustrations/undraw_access_denied_re_awnf.svg') }}" height="128" class="mb-n2" alt="">
                    <h3 class="mt-4">Akses Ditolak</h3>
                    <p class="text-muted">Anda tidak memiliki akses ke ujian saat ini.</p>
                    <a href="{{ route('pmb.camaba.dashboard') }}" class="btn btn-primary">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- EXAM INTERFACE --}}
    <div class="row">
        {{-- Exam Header --}}
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="ti ti-user text-primary" style="font-size: 2rem;"></i>
                                <div class="mt-2">
                                    <strong>{{ $user->name }}</strong>
                                    <div class="text-muted">{{ $pendaftaran->no_pendaftaran }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="ti ti-clock-hour-4 text-warning" style="font-size: 2rem;"></i>
                                <div class="mt-2">
                                    <strong id="exam-timer">00:00:00</strong>
                                    <div class="text-muted">Waktu Tersisa</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="ti ti-file-text text-info" style="font-size: 2rem;"></i>
                                <div class="mt-2">
                                    <strong id="question-number">Soal 1 dari {{ $questions->count() }}</strong>
                                    <div class="text-muted">{{ $paketUjian->nama_paket }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="ti ti-flag text-success" style="font-size: 2rem;"></i>
                                <div class="mt-2">
                                    <strong>{{ $sesiUjian->nama_kegiatan }}</strong>
                                    <div class="text-muted">{{ $sesiUjian->waktu_mulai->format('d M Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Questions --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    @foreach($questions as $index => $komposisi)
                        <div class="question-card p-4" data-soal-id="{{ $komposisi->soal_id }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h4 class="m-0">
                                    <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                    {!! $komposisi->soal->konten_pertanyaan !!}
                                </h4>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ragu-{{ $komposisi->soal_id }}" 
                                           onchange="CBT.saveAnswer({{ $komposisi->soal_id }}, document.querySelector('input[name=\"soal_{{ $komposisi->soal_id }}\"]:checked')?.value || null, this.checked)">
                                    <label class="form-check-label" for="ragu-{{ $komposisi->soal_id }}">Ragu</label>
                                </div>
                            </div>

                            @if($komposisi->soal->media_url)
                                <div class="text-center mb-3">
                                    <img src="{{ asset($komposisi->soal->media_url) }}" class="img-fluid" alt="Soal media">
                                </div>
                            @endif

                            {{-- Multiple Choice --}}
                            @if($komposisi->soal->tipe_soal === 'Pilihan_Ganda')
                                <div class="space-y">
                                    @foreach($komposisi->soal->opsiJawaban as $opsi)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" 
                                                   name="soal_{{ $komposisi->soal_id }}" 
                                                   id="opsi_{{ $opsi->opsi_id }}" 
                                                   value="{{ $opsi->opsi_id }}"
                                                   onchange="CBT.saveAnswer({{ $komposisi->soal_id }}, {{ $opsi->opsi_id }}, document.getElementById('ragu-{{ $komposisi->soal_id }}').checked)">
                                            <label class="form-check-label d-flex align-items-center" for="opsi_{{ $opsi->opsi_id }}">
                                                <span class="badge bg-secondary me-2">{{ $opsi->label }}</span>
                                                {!! $opsi->teks_jawaban !!}
                                                
                                                @if($opsi->media_url)
                                                    <img src="{{ asset($opsi->media_url) }}" class="img-fluid ms-2" style="max-height: 100px;" alt="Opsi media">
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Essay --}}
                            @if($komposisi->soal->tipe_soal === 'Esai')
                                <div class="form-group">
                                    <textarea class="form-control" 
                                              name="soal_{{ $komposisi->soal_id }}" 
                                              rows="5" 
                                              placeholder="Ketik jawaban Anda di sini..."
                                              oninput="CBT.saveAnswer({{ $komposisi->soal_id }}, this.value, document.getElementById('ragu-{{ $komposisi->soal_id }}').checked)"></textarea>
                                </div>
                            @endif

                            {{-- True/False --}}
                            @if($komposisi->soal->tipe_soal === 'Benar_Salah')
                                <div class="space-y">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" 
                                               name="soal_{{ $komposisi->soal_id }}" 
                                               id="benar_{{ $komposisi->soal_id }}" 
                                               value="true"
                                               onchange="CBT.saveAnswer({{ $komposisi->soal_id }}, 'true', document.getElementById('ragu-{{ $komposisi->soal_id }}').checked)">
                                        <label class="form-check-label" for="benar_{{ $komposisi->soal_id }}">
                                            <span class="badge bg-success me-2">Benar</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" 
                                               name="soal_{{ $komposisi->soal_id }}" 
                                               id="salah_{{ $komposisi->soal_id }}" 
                                               value="false"
                                               onchange="CBT.saveAnswer({{ $komposisi->soal_id }}, 'false', document.getElementById('ragu-{{ $komposisi->soal_id }}').checked)">
                                        <label class="form-check-label" for="salah_{{ $komposisi->soal_id }}">
                                            <span class="badge bg-danger me-2">Salah</span>
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Navigation --}}
                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-secondary" id="prev-question" onclick="CBT.navigateQuestion('prev')">
                                <i class="ti ti-arrow-left me-2"></i>
                                Sebelumnya
                            </button>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-primary" id="next-question" onclick="CBT.navigateQuestion('next')">
                                Selanjutnya
                                <i class="ti ti-arrow-right ms-2"></i>
                            </button>
                            <button type="button" class="btn btn-success ms-2" onclick="CBT.submitExam()">
                                <i class="ti ti-check me-2"></i>
                                Selesai Ujian
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Question Navigation Panel --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Navigasi Soal</h3>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach($questions as $index => $komposisi)
                            <div class="col-auto">
                                <button type="button" 
                                        class="btn question-nav-btn" 
                                        data-question-id="{{ $komposisi->soal_id }}"
                                        data-index="{{ $index }}"
                                        onclick="CBT.goToQuestion({{ $index }})">
                                    {{ $index + 1 }}
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
// Initialize exam
document.addEventListener('DOMContentLoaded', function() {
    @if($pendaftaran)
        // Start timer
        const duration = {{ $paketUjian->total_durasi_menit * 60 }}; // Convert to seconds
        CBT.initTimer(duration);
        
        // Mark first question as active
        document.querySelector('.question-card').classList.add('active');
        
        // Initialize navigation
        CBT.updateNavigation(0, {{ $questions->count() }});
        
        // Load saved answers from localStorage
        const savedAnswers = JSON.parse(localStorage.getItem('cbt_answers') || '{}');
        Object.keys(savedAnswers).forEach(soalId => {
            const answer = savedAnswers[soalId];
            const questionElement = document.querySelector(`[data-soal-id="${soalId}"]`);
            
            if (questionElement) {
                // Restore answer
                if (answer.jawaban) {
                    if (typeof answer.jawaban === 'string') {
                        // Essay
                        const textarea = questionElement.querySelector('textarea');
                        if (textarea) textarea.value = answer.jawaban;
                    } else {
                        // Radio button
                        const radio = questionElement.querySelector(`input[value="${answer.jawaban}"]`);
                        if (radio) radio.checked = true;
                    }
                }
                
                // Restore ragu status
                const raguCheckbox = questionElement.querySelector('.form-check-input[type="checkbox"]');
                if (raguCheckbox) raguCheckbox.checked = answer.is_ragu;
                
                // Mark as answered
                questionElement.classList.add('answered');
            }
        });
        
        // Add goToQuestion function
        CBT.goToQuestion = function(index) {
            const allQuestions = Array.from(document.querySelectorAll('.question-card'));
            const currentQuestion = document.querySelector('.question-card.active');
            
            // Hide current
            if (currentQuestion) {
                currentQuestion.classList.remove('active');
                currentQuestion.style.display = 'none';
            }
            
            // Show target
            const targetQuestion = allQuestions[index];
            targetQuestion.classList.add('active');
            targetQuestion.style.display = 'block';
            
            // Update navigation
            CBT.updateNavigation(index, allQuestions.length);
            
            // Scroll to top
            window.scrollTo(0, 0);
        };
        
        // Style question navigation buttons
        document.querySelectorAll('.question-nav-btn').forEach(btn => {
            const soalId = btn.dataset.questionId;
            const savedAnswer = savedAnswers[soalId];
            
            if (savedAnswer && savedAnswer.jawaban) {
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-success');
            }
        });
    @endif
});

// Add custom styles
const style = document.createElement('style');
style.textContent = `
    .question-card {
        border-bottom: 1px solid #e6e7e9;
    }
    
    .question-card.answered {
        background-color: #f8f9fa;
    }
    
    .question-nav-btn {
        width: 40px;
        height: 40px;
        padding: 0;
        border-radius: 50%;
    }
    
    .question-nav-btn.btn-success {
        background-color: #2f9e44;
        border-color: #2f9e44;
    }
    
    .space-y > div {
        margin-bottom: 1rem;
    }
    
    .form-check-label {
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 0.375rem;
        transition: background-color 0.15s;
    }
    
    .form-check-label:hover {
        background-color: #f8f9fa;
    }
`;
document.head.appendChild(style);
</script>
@endpush
