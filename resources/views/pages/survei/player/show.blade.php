@extends('layouts.guest.app')

@section('content')
    @section('title', $survei->judul)

    @if(isset($isPreview) && $isPreview)
    <div class="bg-azure-lt text-center py-2 mb-3 rounded shadow-sm">
        <strong><i class="ti ti-eye me-1"></i> MODE PREVIEW</strong> — Tampilan ini hanya simulasi. Jawaban tidak akan disimpan.
    </div>
    @endif

    @if(isset($isPreview) && $isPreview)
    <div class="mb-3 text-end">
        <x-tabler.button href="{{ route('survei.builder', $survei->id) }}" class="btn-secondary btn-sm" icon="ti ti-arrow-left" text="Kembali ke Builder" />
    </div>
    @endif

    {{-- Survey Header Card --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body text-center py-4">
            <h2 class="mb-1">{{ $survei->judul }}</h2>
            @if($survei->deskripsi)
                <p class="text-secondary mb-0" style="max-width: 480px; margin: 0 auto;">{{ $survei->deskripsi }}</p>
            @endif
        </div>
        {{-- Progress Bar --}}
        @if($survei->halaman->count() > 1)
        <div class="card-footer bg-transparent border-0 pt-0">
            <div class="progress progress-sm">
                <div class="progress-bar bg-primary" id="survei-progress" style="width: {{ round(1 / $survei->halaman->count() * 100) }}%"></div>
            </div>
            <small class="text-muted d-block text-center mt-1">
                Halaman <span id="current-page-num">1</span> dari {{ $survei->halaman->count() }}
            </small>
        </div>
        @endif
    </div>

    {{-- Halaman Navigation --}}
    @if($survei->halaman->count() > 1)
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-outline-secondary btn-prev-halaman" disabled>
                <i class="ti ti-chevron-left"></i> Sebelumnya
            </button>
            <span class="text-muted">Halaman <span id="page-indicator">1</span> / {{ $survei->halaman->count() }}</span>
            <button type="button" class="btn btn-primary btn-next-halaman">
                Lanjutkan <i class="ti ti-chevron-right"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- Daftar Pertanyaan per Halaman --}}
    @foreach($survei->halaman as $index => $halaman)
    <div class="survei-halaman {{ $loop->first ? '' : 'd-none' }}" id="halaman-{{ $halaman->id }}" data-urutan="{{ $loop->iteration }}">
        @if($halaman->judul_halaman && $survei->halaman->count() > 1)
            <h4 class="mb-3 text-primary">{{ $halaman->judul_halaman }}</h4>
        @endif

        <div class="row g-3">
            @foreach($halaman->pertanyaan as $pertanyaan)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm question-card" 
                     data-id="{{ $pertanyaan->id }}"
                     data-tipe="{{ $pertanyaan->tipe }}"
                     data-wajib="{{ $pertanyaan->wajib_diisi ? '1' : '0' }}"
                     data-opsi='@json($pertanyaan->opsi->map(fn($o) => ['id' => $o->id, 'label' => $o->label]))'
                     data-config='@json($pertanyaan->config_json ?? [])'
                     style="cursor: pointer; transition: all 0.2s;"
                     onclick="openQuestionModal({{ $pertanyaan->id }})">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-{{ $pertanyaan->tipe === 'Pilihan_Ganda' ? 'primary' : ($pertanyaan->tipe === 'Esai' ? 'success' : 'azure') }}-lt">
                                {{ str_replace('_', ' ', $pertanyaan->tipe) }}
                            </span>
                            @if($pertanyaan->wajib_diisi)
                                <span class="badge bg-red-lt">Wajib</span>
                            @endif
                        </div>
                        <h6 class="card-title mb-2">{{ Str::limit($pertanyaan->teks_pertanyaan, 80) }}</h6>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="ti ti-click me-1"></i>
                            <span>Klik untuk menjawab</span>
                        </div>
                        {{-- Preview jawaban --}}
                        <div id="preview-jawaban-{{ $pertanyaan->id }}" class="mt-2 p-2 bg-light rounded small text-truncate" style="max-width: 100%;"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Submit Button --}}
    <div class="mt-4 text-center">
        <form action="{{ route('survei.public.store', $survei->slug) }}" method="POST" id="survei-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jawaban" id="hidden-jawaban">
            <x-tabler.button type="submit" class="btn-success btn-lg" icon="ti ti-send" text="Kirim Jawaban" />
        </form>
    </div>

    {{-- Global Question Modal --}}
    <div class="modal modal-blur fade" id="questionModal" tabindex="-1" aria-hidden="true" style="z-index: 99999;">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPertanyaanTitle">Pertanyaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalPertanyaanBody">
                    <!-- Form will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-save-jawaban" onclick="saveJawaban()">Simpan Jawaban</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currentQuestionId = null;
    let allJawaban = {};

    // Modal instance
    let questionModal = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap modal
        if (typeof window.bootstrap !== 'undefined') {
            questionModal = new window.bootstrap.Modal(document.getElementById('questionModal'));
        }

        // Initialize Select2 for dropdown in modal (if jQuery is available)
        if (typeof window.jQuery !== 'undefined') {
            $(document).on('select2:open', function (e) { 
                document.querySelector('.select2-container--open .select2-search__field').focus(); 
            });
        }

        // Navigation between halaman
        document.querySelectorAll('.btn-next-halaman').forEach(btn => {
            btn.addEventListener('click', function() {
                let currentHalaman = document.querySelector('.survei-halaman:not(.d-none)');
                let nextHalaman = currentHalaman.nextElementSibling;
                if (nextHalaman && nextHalaman.classList.contains('survei-halaman')) {
                    currentHalaman.classList.add('d-none');
                    nextHalaman.classList.remove('d-none');
                    updatePageIndicator(nextHalaman.dataset.urutan);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });

        document.querySelectorAll('.btn-prev-halaman').forEach(btn => {
            btn.addEventListener('click', function() {
                let currentHalaman = document.querySelector('.survei-halaman:not(.d-none)');
                let prevHalaman = currentHalaman.previousElementSibling;
                if (prevHalaman && prevHalaman.classList.contains('survei-halaman')) {
                    currentHalaman.classList.add('d-none');
                    prevHalaman.classList.remove('d-none');
                    updatePageIndicator(prevHalaman.dataset.urutan);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });

        // Form submission with Axios
        const form = document.getElementById('survei-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate all required questions
                let isValid = validateAllQuestions();
                if (!isValid) {
                    return;
                }

                // Convert allJawaban object to form data
                const formData = new FormData();
                formData.append('_token', form.querySelector('input[name="_token"]').value);
                
                // Append each jawaban
                Object.keys(allJawaban).forEach(key => {
                    formData.append(`jawaban[${key}]`, allJawaban[key]);
                });

                // Submit via Axios
                axios.post(form.action, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(response) {
                    if (response.data.success || response.data.redirect) {
                        window.location.href = response.data.redirect || '{{ route('survei.public.thankyou', $survei->slug) }}';
                    } else {
                        alert('Terima kasih! Jawaban Anda berhasil disimpan.');
                        window.location.href = '{{ route('survei.public.thankyou', $survei->slug) }}';
                    }
                })
                .catch(function(error) {
                    let errorMsg = 'Terjadi kesalahan. Silakan coba lagi.';
                    if (error.response && error.response.data && error.response.data.message) {
                        errorMsg = error.response.data.message;
                    }
                    alert('Error: ' + errorMsg);
                });
            });
        }
    });

    function updatePageIndicator(pageNum) {
        const pageIndicator = document.getElementById('page-indicator');
        const currentPageNum = document.getElementById('current-page-num');
        if (pageIndicator) pageIndicator.textContent = pageNum;
        if (currentPageNum) currentPageNum.textContent = pageNum;
        
        let totalPages = {{ $survei->halaman->count() }};
        let pct = Math.round(pageNum / totalPages * 100);
        const progress = document.getElementById('survei-progress');
        if (progress) progress.style.width = pct + '%';

        // Update button states
        let currentHalaman = document.querySelector('.survei-halaman:not(.d-none)');
        let prevBtn = document.querySelector('.btn-prev-halaman');
        let nextBtn = document.querySelector('.btn-next-halaman');

        if (prevBtn) prevBtn.disabled = !currentHalaman.previousElementSibling || !currentHalaman.previousElementSibling.classList.contains('survei-halaman');
        if (nextBtn) nextBtn.disabled = !currentHalaman.nextElementSibling || !currentHalaman.nextElementSibling.classList.contains('survei-halaman');
    }

    function openQuestionModal(pertanyaanId) {
        currentQuestionId = pertanyaanId;
        const card = document.querySelector(`.question-card[data-id="${pertanyaanId}"]`);
        if (!card) return;
        
        const tipe = card.dataset.tipe;
        const wajib = card.dataset.wajib;
        const label = card.querySelector('h6').textContent;
        const existingJawaban = allJawaban[pertanyaanId] || '';

        const modalTitle = document.getElementById('modalPertanyaanTitle');
        modalTitle.innerHTML = label + (wajib === '1' ? ' <span class="badge bg-red-lt ms-2">Wajib Diisi</span>' : '');

        // Get opsi data from data attribute
        let opsiData = [];
        try {
            opsiData = JSON.parse(card.dataset.opsi || '[]');
        } catch(e) { console.error('Error parsing opsi data', e); }

        let formHtml = '';

        switch(tipe) {
            case 'Teks_Singkat':
                formHtml = `<input type="text" id="modal-jawaban-input" class="form-control form-control-lg" placeholder="Masukkan jawaban singkat…" value="${existingJawaban}">`;
                break;

            case 'Esai':
                formHtml = `<textarea id="modal-jawaban-input" class="form-control form-control-lg" rows="5" placeholder="Tulis jawaban lengkap…">${existingJawaban}</textarea>`;
                break;

            case 'Angka':
                formHtml = `<input type="number" id="modal-jawaban-input" class="form-control form-control-lg" placeholder="0" value="${existingJawaban}">`;
                break;

            case 'Pilihan_Ganda':
                formHtml = `<div class="space-y-2">`;
                opsiData.forEach(opsi => {
                    formHtml += `
                        <label class="form-check form-check-lg p-3 border rounded mb-2 cursor-pointer hover-azure">
                            <input type="radio" class="form-check-input" name="modal-jawaban-radio" value="${opsi.id}" ${existingJawaban == opsi.id ? 'checked' : ''}>
                            <span class="form-check-label ms-2">${opsi.label}</span>
                        </label>
                    `;
                });
                formHtml += `</div>`;
                break;

            case 'Kotak_Centang':
                formHtml = `<div class="space-y-2">`;
                const checkedValues = existingJawaban ? existingJawaban.split(', ').map(s => s.trim()) : [];
                opsiData.forEach(opsi => {
                    const isChecked = checkedValues.includes(opsi.label) ? 'checked' : '';
                    formHtml += `
                        <label class="form-check form-check-lg p-3 border rounded mb-2 cursor-pointer hover-azure">
                            <input type="checkbox" class="form-check-input" name="modal-jawaban-checkbox" value="${opsi.label}" ${isChecked}>
                            <span class="form-check-label ms-2">${opsi.label}</span>
                        </label>
                    `;
                });
                formHtml += `</div>`;
                break;

            case 'Dropdown':
                formHtml = `<select id="modal-jawaban-input" class="form-select form-select-lg">`;
                formHtml += `<option value="">Pilih jawaban…</option>`;
                opsiData.forEach(opsi => {
                    const isSelected = existingJawaban == opsi.id ? 'selected' : '';
                    formHtml += `<option value="${opsi.id}" ${isSelected}>${opsi.label}</option>`;
                });
                formHtml += `</select>`;
                break;

            case 'Skala_Linear':
                let config = { min: 1, max: 5 };
                try {
                    config = JSON.parse(card.dataset.config || '{}');
                } catch(e) { console.error('Error parsing config', e); }
                
                formHtml = `<div class="d-flex justify-content-center gap-2 flex-wrap">`;
                for(let i = config.min; i <= config.max; i++) {
                    const isChecked = existingJawaban == i.toString() ? 'checked' : '';
                    formHtml += `
                        <label class="form-selectgroup-item">
                            <input type="radio" class="form-selectgroup-input" name="modal-jawaban-radio" value="${i}" ${isChecked}>
                            <span class="form-selectgroup-label btn btn-outline-primary btn-lg">${i}</span>
                        </label>
                    `;
                }
                formHtml += `</div>`;
                break;

            case 'Tanggal':
                formHtml = `<input type="date" id="modal-jawaban-input" class="form-control form-control-lg" value="${existingJawaban}">`;
                break;

            case 'Upload_File':
                formHtml = `<input type="file" id="modal-jawaban-input" class="form-control form-control-lg" accept=".pdf,.jpg,.jpeg,.png">`;
                formHtml += `<small class="text-muted">Maks 2MB. Format: PDF, JPG, PNG</small>`;
                break;

            default:
                formHtml = `<p class="text-danger">Tipe pertanyaan tidak didukung.</p>`;
        }

        document.getElementById('modalPertanyaanBody').innerHTML = formHtml;

        // Initialize Select2 for dropdown in modal
        // Use the same pattern as the rest of the project
        setTimeout(function() {
            if (typeof window.initOfflineSelect2 === 'function') {
                window.initOfflineSelect2('#modal-jawaban-input');
            } else if (typeof window.jQuery !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
                const $select = window.jQuery('#modal-jawaban-input');
                if ($select.hasClass('form-select')) {
                    $select.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        dropdownParent: window.jQuery('#questionModal'),
                        placeholder: $select.attr('placeholder') || 'Pilih jawaban…',
                        allowClear: true
                    });
                }
            }
        }, 100);

        // Show modal
        if (questionModal) {
            questionModal.show();
        }
    }

    function saveJawaban() {
        if (!currentQuestionId) return;

        let jawaban = '';
        const card = document.querySelector(`.question-card[data-id="${currentQuestionId}"]`);
        if (!card) return;
        
        const tipe = card.dataset.tipe;

        // Get jawaban based on tipe
        if (tipe === 'Pilihan_Ganda' || tipe === 'Skala_Linear') {
            const checked = document.querySelector('input[name="modal-jawaban-radio"]:checked');
            jawaban = checked ? checked.value : '';
        } else if (tipe === 'Kotak_Centang') {
            let checked = [];
            document.querySelectorAll('input[name="modal-jawaban-checkbox"]:checked').forEach(cb => {
                checked.push(cb.value);
            });
            jawaban = checked.join(', ');
        } else {
            const input = document.getElementById('modal-jawaban-input');
            jawaban = input ? input.value : '';
        }

        // Validate required
        if (card.dataset.wajib === '1' && !jawaban) {
            alert('Pertanyaan ini wajib diisi!');
            return;
        }

        // Save to global object
        allJawaban[currentQuestionId] = jawaban;

        // Update preview
        const preview = document.getElementById(`preview-jawaban-${currentQuestionId}`);
        if (preview) preview.textContent = jawaban || 'Belum dijawab';

        // Update card style
        if (jawaban) {
            card.classList.add('border-primary');
            const badge = card.querySelector('.badge');
            if (badge) badge.classList.remove('bg-azure-lt');
            if (badge) badge.classList.add('bg-green-lt');
        } else {
            card.classList.remove('border-primary');
        }

        // Close modal
        if (questionModal) {
            questionModal.hide();
        }
    }

    function validateAllQuestions() {
        let isValid = true;
        let missingQuestions = [];

        document.querySelectorAll('.question-card').forEach(card => {
            const id = card.dataset.id;
            const wajib = card.dataset.wajib;
            const jawaban = allJawaban[id] || '';

            if (wajib === '1' && !jawaban) {
                isValid = false;
                const title = card.querySelector('h6');
                if (title) missingQuestions.push(title.textContent);
            }
        });

        if (!isValid) {
            alert('Masih ada ' + missingQuestions.length + ' pertanyaan wajib yang belum dijawab:\n\n- ' + missingQuestions.slice(0, 5).join('\n- ') + (missingQuestions.length > 5 ? '\n...dan ' + (missingQuestions.length - 5) + ' lainnya' : '') + '\n\nSilakan lengkapi terlebih dahulu.');
        }

        return isValid;
    }
</script>

<script>
    // Add hover effect for question cards (vanilla JS)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.question-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('shadow');
                this.style.transform = 'translateY(-2px)';
            });
            card.addEventListener('mouseleave', function() {
                this.classList.remove('shadow');
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>

<style>
    .cursor-pointer {
        cursor: pointer;
    }
    .hover-azure:hover {
        background-color: var(--tblr-azure-lt) !important;
    }
    .question-card:hover {
        border-color: var(--tblr-primary) !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .question-card.answered {
        border-color: var(--tblr-green) !important;
    }
    .form-check-lg {
        transition: all 0.2s;
    }
    .form-check-lg:hover {
        background-color: var(--tblr-azure-lt);
    }
</style>
@endpush
