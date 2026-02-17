@extends(auth()->check() ? 'layouts.admin.app' : 'layouts.public.app')

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
            <a href="{{ route('survei.builder', $survei->id) }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i>Kembali ke Builder
            </a>
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
                <div class="card mb-3 border-0 shadow-sm question-item" data-id="{{ $pertanyaan->id }}" data-tipe="{{ $pertanyaan->tipe }}">
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
                                    <input type="radio" class="form-check-input" name="jawaban[{{ $pertanyaan->id }}]" 
                                           value="{{ $opsi->id }}" {{ $pertanyaan->wajib_diisi ? 'required' : '' }}>
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
                                <select name="jawaban[{{ $pertanyaan->id }}]" class="form-select" {{ $pertanyaan->wajib_diisi ? 'required' : '' }}>
                                    <option value="">Pilih jawaban…</option>
                                    @foreach($pertanyaan->opsi as $opsi)
                                        <option value="{{ $opsi->id }}">{{ $opsi->label }}</option>
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
                    <button type="button" class="btn btn-outline-secondary btn-prev" data-target="#halaman-{{ $survei->halaman[$index-1]->id }}">
                        <i class="ti ti-chevron-left me-1"></i>Kembali
                    </button>
                    @else
                    <div></div>
                    @endif

                    @if(!$loop->last)
                    <button type="button" class="btn btn-primary btn-next" data-target="#halaman-{{ $survei->halaman[$index+1]->id }}" data-page="{{ $loop->iteration + 1 }}">
                        Lanjut<i class="ti ti-chevron-right ms-1"></i>
                    </button>
                    @else
                        @if(isset($isPreview) && $isPreview)
                        <button type="button" class="btn btn-success" onclick="alert('Ini adalah mode preview. Jawaban tidak disimpan.')">
                            <i class="ti ti-check me-1"></i>Kirim Jawaban
                        </button>
                        @else
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-send me-1"></i>Kirim Jawaban
                        </button>
                        @endif
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
        const totalPages = {{ $survei->halaman->count() }};

        // Navigation
        $('.btn-next').click(function() {
            let currentPage = $(this).closest('.survei-halaman');
            let isValid = true;
            
            currentPage.find('input[required], select[required], textarea[required]').each(function() {
                if (!this.checkValidity()) {
                    this.reportValidity();
                    isValid = false;
                    return false;
                }
            });

            // Checkbox group validation
            let validatedGroups = {};
            currentPage.find('.checkbox-required-group[data-required="1"]').each(function() {
                let group = $(this).data('group');
                if (validatedGroups[group]) return;
                validatedGroups[group] = true;
                let checked = currentPage.find(`.checkbox-required-group[data-group="${group}"]:checked`).length;
                if (checked === 0) {
                    isValid = false;
                    alert('Pertanyaan checkbox wajib diisi minimal satu pilihan.');
                    return false;
                }
            });

            if (isValid) {
                let target = $(this).data('target');
                let pageNum = $(this).data('page');
                currentPage.addClass('d-none');
                $(target).removeClass('d-none');
                window.scrollTo({ top: 0, behavior: 'smooth' });

                if (totalPages > 1) {
                    let pct = Math.round(pageNum / totalPages * 100);
                    $('#survei-progress').css('width', pct + '%');
                    $('#current-page-num').text(pageNum);
                }
            }
        });

        $('.btn-prev').click(function() {
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
