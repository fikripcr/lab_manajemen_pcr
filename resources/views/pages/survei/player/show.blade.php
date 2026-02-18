@extends(auth()->check() ? 'layouts.tabler.app' : 'layouts.public.app')

@section('header')
@if(isset($isPreview) && $isPreview)
<div class="bg-azure-lt text-center py-2">
    <strong><i class="ti ti-eye me-1"></i> MODE PREVIEW</strong> — Tampilan ini hanya simulasi. Jawaban tidak akan disimpan.
</div>
@endif
@endsection

@section('content')
<div class="page-body">
    <div class="container-tight py-4" style="max-width: 720px;">

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

        {{-- Form --}}
        <form action="{{ route('survei.public.store', $survei->slug) }}" method="POST" id="survei-form" enctype="multipart/form-data">
            @csrf
            
            @foreach($survei->halaman as $index => $halaman)
            <div class="survei-halaman {{ $loop->first ? '' : 'd-none' }}" id="halaman-{{ $halaman->id }}" data-urutan="{{ $loop->iteration }}">
                @if($halaman->judul_halaman && $survei->halaman->count() > 1)
                    <h4 class="mb-3 text-primary">{{ $halaman->judul_halaman }}</h4>
                @endif

                @foreach($halaman->pertanyaan as $pertanyaan)
                <div class="card mb-3 border-0 shadow-sm question-item" 
                     data-id="{{ $pertanyaan->id }}" 
                     data-tipe="{{ $pertanyaan->tipe }}"
                     data-default-next="{{ $pertanyaan->next_pertanyaan_id }}">
                    <div class="card-body py-3">
                        <label class="form-label fw-semibold mb-2 {{ $pertanyaan->wajib_diisi ? 'required' : '' }}">
                            {{ $pertanyaan->teks_pertanyaan }}
                        </label>
                        @if($pertanyaan->bantuan_teks)
                            <small class="form-hint d-block mb-2">{{ $pertanyaan->bantuan_teks }}</small>
                        @endif

                        @switch($pertanyaan->tipe)
                            @case('Teks_Singkat')
                                <input type="text" name="jawaban[{{ $pertanyaan->id }}]" class="form-control" 
                                       {{ $pertanyaan->wajib_diisi ? 'required' : '' }} placeholder="Jawaban singkat…">
                                @break

                            @case('Esai')
                                <textarea name="jawaban[{{ $pertanyaan->id }}]" class="form-control" rows="3" 
                                          {{ $pertanyaan->wajib_diisi ? 'required' : '' }} placeholder="Tulis jawaban…"></textarea>
                                @break

                            @case('Angka')
                                <input type="number" name="jawaban[{{ $pertanyaan->id }}]" class="form-control" 
                                       {{ $pertanyaan->wajib_diisi ? 'required' : '' }} placeholder="0">
                                @break

                            @case('Pilihan_Ganda')
                                <div class="space-y-1">
                                @foreach($pertanyaan->opsi as $opsi)
                                <label class="form-check">
                                    <input type="radio" class="form-check-input opsi-input" name="jawaban[{{ $pertanyaan->id }}]" 
                                           value="{{ $opsi->id }}" {{ $pertanyaan->wajib_diisi ? 'required' : '' }}
                                           data-next="{{ $opsi->next_pertanyaan_id }}">
                                    <span class="form-check-label">{{ $opsi->label }}</span>
                                </label>
                                @endforeach
                                </div>
                                @break

                            @case('Kotak_Centang')
                                <div class="space-y-1">
                                @foreach($pertanyaan->opsi as $opsi)
                                <label class="form-check">
                                    <input type="checkbox" class="form-check-input checkbox-required-group" 
                                           name="jawaban[{{ $pertanyaan->id }}][]" value="{{ $opsi->label }}"
                                           data-group="q-{{ $pertanyaan->id }}" data-required="{{ $pertanyaan->wajib_diisi ? '1' : '0' }}">
                                    <span class="form-check-label">{{ $opsi->label }}</span>
                                </label>
                                @endforeach
                                </div>
                                @break

                            @case('Dropdown')
                                <select name="jawaban[{{ $pertanyaan->id }}]" class="form-select opsi-input" {{ $pertanyaan->wajib_diisi ? 'required' : '' }}
                                        onchange="$(this).find('option:selected').data('next') && $(this).data('next', $(this).find('option:selected').data('next'))">
                                    <option value="">Pilih jawaban…</option>
                                    @foreach($pertanyaan->opsi as $opsi)
                                        <option value="{{ $opsi->id }}" data-next="{{ $opsi->next_pertanyaan_id }}">{{ $opsi->label }}</option>
                                    @endforeach
                                </select>
                                @break

                            @case('Skala_Linear')
                                @php
                                    $min = $pertanyaan->config_json['min'] ?? 1;
                                    $max = $pertanyaan->config_json['max'] ?? 5;
                                    $labelMin = $pertanyaan->config_json['label_min'] ?? '';
                                    $labelMax = $pertanyaan->config_json['label_max'] ?? '';
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    @if($labelMin)<small class="text-muted text-nowrap">{{ $labelMin }}</small>@endif
                                    <div class="d-flex gap-1 flex-wrap justify-content-center flex-grow-1">
                                        @for($i = $min; $i <= $max; $i++)
                                            <label class="form-selectgroup-item">
                                                <input class="form-selectgroup-input" type="radio" 
                                                       name="jawaban[{{ $pertanyaan->id }}]" value="{{ $i }}" 
                                                       {{ $pertanyaan->wajib_diisi ? 'required' : '' }}>
                                                <span class="form-selectgroup-label">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    @if($labelMax)<small class="text-muted text-nowrap">{{ $labelMax }}</small>@endif
                                </div>
                                @break

                            @case('Tanggal')
                                <input type="date" name="jawaban[{{ $pertanyaan->id }}]" class="form-control" 
                                       {{ $pertanyaan->wajib_diisi ? 'required' : '' }}>
                                @break

                            @case('Upload_File')
                                <input type="file" name="jawaban[{{ $pertanyaan->id }}]" class="form-control" 
                                       {{ $pertanyaan->wajib_diisi ? 'required' : '' }}>
                                <small class="form-hint">Maks 2MB. Format: PDF, JPG, PNG</small>
                                @break


                            @default
                                <p class="text-danger small mb-0">Tipe tidak didukung: {{ $pertanyaan->tipe }}</p>
                        @endswitch
                    </div>
                </div>
                @endforeach

                {{-- Navigation --}}
                <div class="d-flex justify-content-between mt-3 mb-4">
                    @if(!$loop->first)
                    <x-tabler.button type="button" class="btn-outline-secondary btn-prev" data-target="#halaman-{{ $survei->halaman[$index-1]->id }}" icon="ti ti-chevron-left" text="Kembali" />
                    @else
                    <div></div>
                    @endif

                    @if(!$loop->last || ($survei->mode === 'Bercabang'))
                    <x-tabler.button type="button" class="btn-primary btn-next" data-target="#halaman-{{ $survei->halaman[$index+1]->id ?? '' }}" data-page="{{ $loop->iteration + 1 }}">
                        Lanjut<i class="ti ti-chevron-right ms-1"></i>
                    </x-tabler.button>
                    @endif

                    @if($loop->last || ($survei->mode === 'Bercabang'))
                        @php
                            $isPreviewMode = isset($isPreview) && $isPreview;
                        @endphp
                        <x-tabler.button type="{{ $isPreviewMode ? 'button' : 'submit' }}" 
                                class="btn-success {{ $survei->mode === 'Bercabang' ? 'd-none' : '' }} btn-submit"
                                onclick="{{ $isPreviewMode ? 'alert(\'Preview: Jawaban tidak disimpan\')' : '' }}"
                                icon="ti ti-send" text="Kirim Jawaban" />
                    @endif
                </div>
            </div>
            @endforeach
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mode = '{{ $survei->mode }}';
        const totalPages = {{ $survei->halaman->count() }};
        let branchStack = []; // For Back button in branching mode

        if (mode === 'Bercabang') {
            // Initial state for branching: hide all questions except the first one in the first page
            $('.question-item').addClass('d-none');
            $('.survei-halaman').removeClass('d-none');
            $('.question-item').first().removeClass('d-none');
            
            // Hide all standard next/prev buttons, we'll use per-question buttons or a modified behavior
            // Actually, let's just make the existing buttons work per question
            $('.btn-next').text('Lanjut').html('Lanjut<i class="ti ti-chevron-right ms-1"></i>');
        }

        // Navigation
        $('.btn-next').click(function() {
            let currentHalaman = $(this).closest('.survei-halaman');
            let isValid = true;
            
            // Validation (only for visible questions)
            currentHalaman.find('.question-item:not(.d-none) input[required], .question-item:not(.d-none) select[required], .question-item:not(.d-none) textarea[required]').each(function() {
                if (!this.checkValidity()) {
                    this.reportValidity();
                    isValid = false;
                    return false;
                }
            });

            if (!isValid) return;

            if (mode === 'Bercabang') {
                let currentQuestion = currentHalaman.find('.question-item:not(.d-none)');
                let nextQuestionId = null;

                // 1. Check option logic
                let selectedOpsi = currentQuestion.find('.opsi-input:checked, select.opsi-input').first();
                if (selectedOpsi.length) {
                    if (selectedOpsi.is('select')) {
                        nextQuestionId = selectedOpsi.find('option:selected').data('next');
                    } else {
                        nextQuestionId = selectedOpsi.data('next');
                    }
                }

                // 2. Check question default logic if no option logic
                if (!nextQuestionId) {
                    nextQuestionId = currentQuestion.data('default-next');
                }

                let nextQuestion = null;
                if (nextQuestionId) {
                    nextQuestion = $(`.question-item[data-id="${nextQuestionId}"]`);
                } else {
                    // 3. Follow default order
                    nextQuestion = currentQuestion.next('.question-item');
                    if (nextQuestion.length === 0) {
                        // Try next page
                        let nextPage = currentHalaman.next('.survei-halaman');
                        if (nextPage.length) {
                            nextQuestion = nextPage.find('.question-item').first();
                        }
                    }
                }

                if (nextQuestion && nextQuestion.length) {
                    branchStack.push(currentQuestion.data('id'));
                    currentQuestion.addClass('d-none');
                    
                    // Handle page transition if needed
                    let nextPage = nextQuestion.closest('.survei-halaman');
                    if (nextPage.attr('id') !== currentHalaman.attr('id')) {
                        currentHalaman.addClass('d-none');
                        nextPage.removeClass('d-none');
                    }
                    
                    nextQuestion.removeClass('d-none');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    
                    // Update progress (rough estimate)
                    if (totalPages > 1) {
                        let pageNum = nextPage.data('urutan');
                        let pct = Math.round(pageNum / totalPages * 100);
                        $('#survei-progress').css('width', pct + '%');
                        $('#current-page-num').text(pageNum);
                    }

                    // Toggle submit button if it's the last question
                    // This part needs careful handling of the Submit button
                    updateNavButtons(nextQuestion);
                } else {
                    // No more questions, submit the form? 
                    // Usually there's a submit button at the end of the last page.
                    // If we reach here in branching, it means we should show the submit button.
                    $(this).closest('form').submit();
                }
            } else {
                // Standard Linear Mode logic
                let target = $(this).data('target');
                let pageNum = $(this).data('page');
                currentHalaman.addClass('d-none');
                $(target).removeClass('d-none');
                window.scrollTo({ top: 0, behavior: 'smooth' });

                if (totalPages > 1) {
                    let pct = Math.round(pageNum / totalPages * 100);
                    $('#survei-progress').css('width', pct + '%');
                    $('#current-page-num').text(pageNum);
                }
            }
        });

        function updateNavButtons(currentQuestion) {
            if (mode !== 'Bercabang') return;

            let nextBtn = $('.btn-next');
            let submitBtn = $('.btn-submit');

            // Find possible next question
            let nextQuestionId = null;
            let selectedOpsi = currentQuestion.find('.opsi-input:checked, select.opsi-input').first();
            if (selectedOpsi.length) {
                nextQuestionId = selectedOpsi.is('select') ? selectedOpsi.find('option:selected').data('next') : selectedOpsi.data('next');
            }
            if (!nextQuestionId) nextQuestionId = currentQuestion.data('default-next');

            let hasNext = false;
            if (nextQuestionId) {
                hasNext = $(`.question-item[data-id="${nextQuestionId}"]`).length > 0;
            } else {
                hasNext = currentQuestion.next('.question-item').length > 0 || currentQuestion.closest('.survei-halaman').next('.survei-halaman').length > 0;
            }

            if (hasNext) {
                nextBtn.removeClass('d-none');
                submitBtn.addClass('d-none');
            } else {
                nextBtn.addClass('d-none');
                submitBtn.removeClass('d-none');
            }
        }

        // Add event listener to inputs to trigger button update in branching mode
        if (mode === 'Bercabang') {
            $('.opsi-input').on('change', function() {
                updateNavButtons($('.question-item:not(.d-none)'));
            });
            // Initial check
            updateNavButtons($('.question-item:not(.d-none)'));
        }

        $('.btn-prev').click(function() {
            if (mode === 'Bercabang' && branchStack.length > 0) {
                let currentQuestion = $('.question-item:not(.d-none)');
                let prevQuestionId = branchStack.pop();
                let prevQuestion = $(`.question-item[data-id="${prevQuestionId}"]`);
                
                currentQuestion.addClass('d-none');
                let prevPage = prevQuestion.closest('.survei-halaman');
                let currentHalaman = $(this).closest('.survei-halaman');
                
                if (prevPage.attr('id') !== currentHalaman.attr('id')) {
                    currentHalaman.addClass('d-none');
                    prevPage.removeClass('d-none');
                }
                
                prevQuestion.removeClass('d-none');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                let target = $(this).data('target');
                let $targetEl = $(target);
                let pageNum = $targetEl.data('urutan');

                $(this).closest('.survei-halaman').addClass('d-none');
                $targetEl.removeClass('d-none');
                window.scrollTo({ top: 0, behavior: 'smooth' });

                if (totalPages > 1) {
                    let pct = Math.round(pageNum / totalPages * 100);
                    $('#survei-progress').css('width', pct + '%');
                    $('#current-page-num').text(pageNum);
                }
            }
        });

        @if(isset($isPreview) && $isPreview)
        $('#survei-form').on('submit', function(e) {
            e.preventDefault();
            alert('Ini adalah mode preview. Jawaban tidak akan disimpan.');
        });
        @endif
    });
</script>
@endpush
