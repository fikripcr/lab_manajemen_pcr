@extends('layouts.tabler.app')

@section('title', 'Ujian CBT')

@section('header')
<x-tabler.page-header title="Ujian CBT" pretitle="Computer Based Test">
    <x-slot:actions>
        @if(auth()->user()->hasRole('admin'))
            <div class="btn-list">
                <a href="{{ route('cbt.jadwal.index') }}" class="btn btn-secondary">
                    <i class="ti ti-calendar me-2"></i>
                    Kelola Jadwal
                </a>
                <a href="{{ route('cbt.mata-uji.index') }}" class="btn btn-primary">
                    <i class="ti ti-file-text me-2"></i>
                    Bank Soal
                </a>
            </div>
        @endif
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        @if(auth()->user()->hasRole('camaba'))
            {{-- CAMABA VIEW --}}
            @include('pages.cbt.partials.exam-interface')
        @else
            {{-- ADMIN VIEW --}}
            @include('pages.cbt.partials.monitoring-dashboard')
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global CBT functionality
const CBT = {
    // Timer functionality
    timer: null,
    startTime: null,
    duration: null,
    
    initTimer(duration) {
        this.duration = duration;
        this.startTime = Date.now();
        
        this.timer = setInterval(() => {
            const elapsed = Math.floor((Date.now() - this.startTime) / 1000);
            const remaining = this.duration - elapsed;
            
            if (remaining <= 0) {
                this.submitExam();
                return;
            }
            
            const hours = Math.floor(remaining / 3600);
            const minutes = Math.floor((remaining % 3600) / 60);
            const seconds = remaining % 60;
            
            const display = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            const timerElement = document.getElementById('exam-timer');
            if (timerElement) {
                timerElement.textContent = display;
                
                // Change color when time is running out
                if (remaining < 300) { // 5 minutes
                    timerElement.classList.add('text-danger');
                } else if (remaining < 600) { // 10 minutes
                    timerElement.classList.add('text-warning');
                }
            }
        }, 1000);
    },
    
    // Answer saving
    saveAnswer(soalId, answer, isRagu = false) {
        const data = {
            soal_id: soalId,
            jawaban: answer,
            is_ragu: isRagu,
            timestamp: Date.now()
        };
        
        // Save to localStorage first (instant feedback)
        let answers = JSON.parse(localStorage.getItem('cbt_answers') || '{}');
        answers[soalId] = data;
        localStorage.setItem('cbt_answers', JSON.stringify(answers));
        
        // Mark question as answered
        const questionElement = document.querySelector(`[data-soal-id="${soalId}"]`);
        if (questionElement) {
            questionElement.classList.add('answered');
        }
        
        // Send to server (background)
        this.syncAnswer(data);
    },
    
    // Sync with server
    syncAnswer(data) {
        fetch('/cbt/api/save-answer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (!result.success) {
                console.error('Failed to sync answer:', result.message);
            }
        })
        .catch(error => {
            console.error('Sync error:', error);
        });
    },
    
    // Submit exam
    submitExam() {
        if (confirm('Apakah Anda yakin ingin menyelesaikan ujian?')) {
            clearInterval(this.timer);
            
            // Get all answers from localStorage
            const answers = JSON.parse(localStorage.getItem('cbt_answers') || '{}');
            
            // Submit to server
            fetch('/cbt/api/submit-exam', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    answers: answers,
                    end_time: Date.now()
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    window.location.href = result.redirect;
                } else {
                    alert('Gagal menyelesaikan ujian: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Submit error:', error);
                alert('Terjadi kesalahan saat menyelesaikan ujian');
            });
        }
    },
    
    // Navigation
    navigateQuestion(direction) {
        const currentQuestion = document.querySelector('.question-card.active');
        const allQuestions = Array.from(document.querySelectorAll('.question-card'));
        const currentIndex = allQuestions.indexOf(currentQuestion);
        
        let nextIndex;
        if (direction === 'next') {
            nextIndex = Math.min(currentIndex + 1, allQuestions.length - 1);
        } else {
            nextIndex = Math.max(currentIndex - 1, 0);
        }
        
        // Hide current
        currentQuestion.classList.remove('active');
        currentQuestion.style.display = 'none';
        
        // Show next
        const nextQuestion = allQuestions[nextIndex];
        nextQuestion.classList.add('active');
        nextQuestion.style.display = 'block';
        
        // Update navigation
        this.updateNavigation(nextIndex, allQuestions.length);
        
        // Scroll to top
        window.scrollTo(0, 0);
    },
    
    updateNavigation(currentIndex, totalQuestions) {
        const prevBtn = document.getElementById('prev-question');
        const nextBtn = document.getElementById('next-question');
        const questionNumber = document.getElementById('question-number');
        
        if (prevBtn) {
            prevBtn.disabled = currentIndex === 0;
        }
        
        if (nextBtn) {
            nextBtn.disabled = currentIndex === totalQuestions - 1;
            nextBtn.textContent = currentIndex === totalQuestions - 1 ? 'Selesai' : 'Selanjutnya';
        }
        
        if (questionNumber) {
            questionNumber.textContent = `Soal ${currentIndex + 1} dari ${totalQuestions}`;
        }
    },
    
    // Security features
    initSecurity() {
        // Prevent right click
        document.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            return false;
        });
        
        // Prevent copy paste
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && (e.key === 'c' || e.key === 'v' || e.key === 'x')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Detect tab switching
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.logViolation('Pindah_Tab');
            }
        });
        
        // Fullscreen detection
        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
                this.logViolation('Keluar_Fullscreen');
            }
        });
    },
    
    logViolation(type) {
        fetch('/cbt/api/log-violation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                type: type,
                timestamp: Date.now()
            })
        });
    }
};

// Initialize security features
document.addEventListener('DOMContentLoaded', () => {
    CBT.initSecurity();
});
</script>
@endpush
