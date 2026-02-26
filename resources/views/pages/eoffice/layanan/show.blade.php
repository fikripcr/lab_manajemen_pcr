@use('Illuminate\Support\Facades\Storage')
@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $layanan->no_layanan }}" pretitle="{{ $layanan->jenisLayanan->nama_layanan }}">
    <x-slot:actions>
        <div class="btn-list">
            <x-tabler.button type="back" href="{{ route('eoffice.layanan.index') }}" />
            <div class="dropdown">
                <x-tabler.button type="button" class="btn-ghost-secondary dropdown-toggle" data-bs-toggle="dropdown" text="Action" />
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item {{ in_array($layanan->latestStatus->status_layanan, ['Selesai', 'Selesai (Otomatis)']) ? '' : 'disabled' }}" href="{{ route('eoffice.layanan.download-pdf', $layanan->encrypted_layanan_id) }}">
                        <i class="ti ti-file-download me-1"></i> Unduh Bukti PDF
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#">
                        <i class="ti ti-trash me-1"></i> Batalkan Pengajuan
                    </a>
                </div>
            </div>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    {{-- Left Column: Main Info & Data --}}
    <div class="col-lg-8">
        
        {{-- 1. Status Summary Card --}}
        <div class="card mb-3">
            <div class="card-status-top bg-{{ statusLayananColor($layanan->latestStatus->status_layanan ?? 'Diajukan')['color'] }}"></div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="avatar avatar-lg rounded bg-{{ statusLayananColor($layanan->latestStatus->status_layanan ?? 'Diajukan')['color'] }}-lt">
                            <i class="ti ti-file-text fs-1"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="h3 mb-1">{{ $layanan->latestStatus->status_layanan ?? 'Diajukan' }}</div>
                        <div class="text-muted small">
                            {{ $layanan->latestStatus->keterangan ?? 'Permohonan sedang diproses.' }}
                        </div>
                    </div>
                    @if($layanan->latestStatus->done_duration)
                        <div class="col-auto text-end">
                            <div class="text-muted small">Durasi Proses</div>
                            <div class="fw-bold">{{ $layanan->latestStatus->done_duration }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Feedback Section --}}
        @if($layanan->jenisLayanan->is_feedback && in_array($layanan->latestStatus->status_layanan, ['Selesai', 'Selesai (Otomatis)']))
            <div class="card mb-3 {{ $layanan->feedback ? 'bg-success-lt' : 'bg-warning-lt' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Feedback Layanan</div>
                        @if($layanan->feedback)
                            <div class="ms-auto">
                                @for($i=1; $i<=5; $i++)
                                    <i class="ti ti-star-filled {{ $i <= $layanan->feedback->rating ? 'text-yellow' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                        @endif
                    </div>
                    
                    @if($layanan->feedback)
                        <div class="h3 mb-2">Terima kasih atas masukan Anda!</div>
                        <div class="text-muted">
                            "{{ $layanan->feedback->catatan ?? 'Tidak ada catatan tambahan.' }}"
                        </div>
                        <div class="small text-muted mt-2">
                            Dikirim pada: {{ $layanan->feedback->created_at->format('d M Y H:i') }}
                        </div>
                    @else
                        <div class="h3 mb-2">Bagaimana pengalaman Anda?</div>
                        <p class="text-muted mb-3">Silakan berikan penilaian dan masukan untuk meningkatkan kualitas layanan kami.</p>
                        <x-tabler.button type="button" class="btn-warning w-100" data-bs-toggle="modal" data-bs-target="#modal-feedback" icon="ti ti-star" text="Beri Nilai Layanan" />
                    @endif
                </div>
            </div>
        @endif

        {{-- 2. Data Isian Display --}}
        <div class="card mb-3">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tab-pemohon" class="nav-link active" data-bs-toggle="tab">Data Pemohon</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-disposisi" class="nav-link" data-bs-toggle="tab">Data Isian Petugas</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-history" class="nav-link" data-bs-toggle="tab">Log Pengolahan</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    {{-- Tab Pemohon --}}
                    <div class="tab-pane active show" id="tab-pemohon">
                        <div class="row">
                            @if($dataIsian['Pemohon']->isEmpty())
                                <div class="col-12 text-center text-muted py-3">Tidak ada data isian pemohon.</div>
                            @else
                                @foreach($dataIsian['Pemohon'] as $field)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small uppercase fw-bold">{{ $field->nama_isian }}</label>
                                        <div class="form-control-plaintext">
                                            @if($field->type === 'file' && str_contains($field->isi, 'eoffice/requests/'))
                                                <x-tabler.button href="{{ Storage::url($field->isi) }}" target="_blank" class="btn-sm btn-outline-info" icon="ti ti-download" text="Unduh Berkas" />
                                            @else
                                                {!! nl2br(e($field->isi)) ?? '-' !!}
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="col-12 mb-3">
                                <label class="form-label text-muted small uppercase fw-bold">Keterangan Tambahan</label>
                                <div class="form-control-plaintext italic">"{{ $layanan->keterangan ?? '-' }}"</div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab Disposisi --}}
                    <div class="tab-pane" id="tab-disposisi">
                        <div class="datagrid">
                            @foreach(['Disposisi 1', 'Disposisi 2', 'Sistem'] as $cat)
                                @foreach($dataIsian[$cat] as $field)
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">{{ $field->nama_isian }}</div>
                                        <div class="datagrid-content">
                                            @if($field->type === 'file' && str_contains($field->isi, 'eoffice/requests/'))
                                                <x-tabler.button href="{{ Storage::url($field->isi) }}" target="_blank" class="btn-sm btn-pill btn-ghost-info" icon="ti ti-file" text="Berkas" />
                                            @else
                                                {{ $field->isi ?? '-' }}
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                        @if($dataIsian['Disposisi 1']->isEmpty() && $dataIsian['Disposisi 2']->isEmpty() && $dataIsian['Sistem']->isEmpty())
                            <div class="text-center text-muted py-4 small">Belum ada data isian dari petugas/sistem.</div>
                        @endif
                    </div>

                    {{-- Tab History --}}
                    <div class="tab-pane" id="tab-history">
                        <ul class="steps steps-vertical small">
                            @foreach($layanan->statuses->sortByDesc('created_at') as $st)
                                <li class="step-item">
                                    <div class="h4 m-0 d-flex justify-content-between">
                                        <span>{{ $st->status_layanan }}</span>
                                        <small class="text-muted">{{ $st->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <div class="text-muted">
                                        {{ $st->keterangan ?? '-' }}
                                        <div class="small mt-1 mt-1 text-primary">Oleh: {{ $st->user->name ?? 'System' }}</div>
                                        @if($st->file_lampiran)
                                            <div class="mt-1">
                                                <a href="{{ Storage::url($st->file_lampiran) }}" target="_blank" class="text-info"><i class="ti ti-paperclip"></i> Lampiran</a>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column: Actions, Entities & Discussion --}}
    <div class="col-lg-4">
        
        {{-- 3. Action Section (Workflow) --}}
        @if($canAction)
            <div class="card mb-3 bg-primary-lt border-primary shadow-sm hover-shadow">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-bolt me-1"></i> Aksi Pengolahan</h3>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @if($layanan->latestStatus->status_layanan === 'Diajukan')
                            <div class="col-12">
                                <x-tabler.button type="submit" href="{{ route('eoffice.layanan.update-status', [$layanan->encrypted_layanan_id, 'proses']) }}" class="w-100" icon="ti ti-player-play" text="Terima & Proses" />
                            </div>
                        @else
                            {{-- Unified form for status updates --}}
                            <form action="{{ route('eoffice.layanan.update-status', $layanan->encrypted_layanan_id) }}" method="POST" class="ajax-form w-100" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label required">Update Status Pengolahan</label>
                                    <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                        @if($nextDisposisi)
                                            <label class="form-selectgroup-item flex-fill">
                                                <input type="radio" name="status_layanan" value="Disposisi" class="form-selectgroup-input" checked>
                                                <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                    <div class="me-3">
                                                        <span class="form-selectgroup-check"></span>
                                                    </div>
                                                    <div>
                                                        <span class="d-block fw-bold">Disposisi</span>
                                                        <small class="text-muted">Teruskan ke: {{ $nextDisposisi->value }}</small>
                                                    </div>
                                                    <input type="hidden" name="disposisi_seq" value="{{ $nextDisposisi->seq }}">
                                                </div>
                                            </label>
                                        @endif
                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" name="status_layanan" value="Selesai" class="form-selectgroup-input" {{ !$nextDisposisi ? 'checked' : '' }}>
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-bold text-success">Selesaikan Layanan</span>
                                                    <small class="text-muted">Proses validasi telah usai.</small>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" name="status_layanan" value="Direvisi" class="form-selectgroup-input">
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-bold text-warning">Minta Revisi</span>
                                                    <small class="text-muted">Kembalikan ke pemohon untuk diperbaiki.</small>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" name="status_layanan" value="Ditolak" class="form-selectgroup-input">
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-bold text-danger">Tolak Layanan</span>
                                                    <small class="text-muted">Pengajuan tidak sesuai persyaratan.</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <x-tabler.form-textarea name="keterangan" label="Keterangan / Pesan ke Pemohon" rows="3" placeholder="Wajib jika Tolak / Revisi" />

                                <x-tabler.form-input type="file" name="file_lampiran" label="File Output / Lampiran" />

                                <div class="text-end">
                                    <x-tabler.button type="submit" class="w-100" text="Kirim Perubahan Status" />
                                </div>
                            </form>
                        @endif

                        @if($layanan->latestStatus->status_layanan !== 'Diajukan')
                            <div class="col-12 mt-2">
                                <x-tabler.button type="cancel" href="{{ route('eoffice.layanan.update-status', [$layanan->encrypted_layanan_id, 'batal']) }}" class="btn-sm btn-ghost-danger w-100 ajax-confirm" data-title="Batal Proses?" data-text="Status akan kembali ke Antrian/Diajukan." icon="ti ti-history" text="Batal Proses" />
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- 4. Entities (Applicant & PIC) --}}
        <div class="card mb-3">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title">Pihak Terlibat</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted small uppercase fw-bold mb-2">Pemohon</div>
                    <div class="d-flex align-items-center">
                        <span class="avatar avatar-sm me-2 bg-blue-lt">{{ substr($layanan->pengusul_nama, 0, 1) }}</span>
                        <div>
                            <div class="fw-bold">{{ $layanan->pengusul_nama }}</div>
                            <div class="text-muted small">{{ $layanan->pengusul_nim }} / {{ $layanan->pengusul_prodi ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                @if($layanan->pic_awal_user)
                    <div class="mb-3 border-top pt-3">
                        <div class="text-muted small uppercase fw-bold mb-2">PIC Pengolah</div>
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-sm me-2 bg-yellow-lt">{{ substr($layanan->pic_awal_user->name, 0, 1) }}</span>
                            <div>
                                <div class="fw-bold">{{ $layanan->pic_awal_user->name }}</div>
                                <div class="text-muted small">{{ $layanan->pic_awal_user->email }}</div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="border-top pt-3">
                    <div class="text-muted small uppercase fw-bold mb-2">Histori Keterlibatan</div>
                    @foreach($layanan->keterlibatan as $k)
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-fill small">
                                <span class="fw-bold">{{ $k->user->name }}</span> ({{ $k->jabatan }})
                                <div class="text-muted">{{ $k->created_at->format('d M y') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 5. Discussion Forum (Moved to Right Column) --}}
        @if($layanan->jenisLayanan->is_diskusi)
        <div class="card mb-3" style="max-height: 600px;">
            <div class="card-header">
                <h3 class="card-title">Diskusi & Komentar</h3>
                <div class="card-actions">
                    <span class="badge bg-purple-lt">{{ $layanan->diskusi->count() }} Pesan</span>
                </div>
            </div>
            <div class="card-body scrollable py-2" id="chat-container" style="height: 400px; overflow-y: auto;">
                <div class="chat-messages p-2">
                    @forelse($layanan->diskusi->sortBy('created_at') as $chat)
                        <div class="mb-3 {{ $chat->created_by == auth()->id() ? 'text-end' : '' }}">
                            <div class="d-inline-block p-2 rounded-3 {{ $chat->created_by == auth()->id() ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 90%;">
                                @if($chat->created_by != auth()->id())
                                    <div class="small fw-bold border-bottom mb-1 pb-1">
                                        {{ $chat->user->name }} ({{ $chat->status_pengirim }})
                                    </div>
                                @endif
                                <div class="chat-text small" style="white-space: pre-wrap;">{{ $chat->pesan }}</div>
                                @if($chat->file_lampiran)
                                    <div class="mt-2 pt-2 border-top small">
                                        <a href="{{ Storage::url($chat->file_lampiran) }}" target="_blank" class="{{ $chat->created_by == auth()->id() ? 'text-white' : 'text-primary' }}">
                                            <i class="ti ti-paperclip"></i> Lampiran
                                        </a>
                                    </div>
                                @endif
                                <div class="small mt-1 {{ $chat->created_by == auth()->id() ? 'text-white-50' : 'text-muted' }}" style="font-size: 0.7rem;">
                                    {{ $chat->created_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="ti ti-messages fs-1"></i>
                            <p class="mt-2 small">Mulai diskusi jika ada pertanyaan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer border-top p-2">
                <form action="{{ route('eoffice.layanan.diskusi.store') }}" method="POST" class="ajax-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="layanan_id" value="{{ $layanan->encrypted_layanan_id }}">
                    <x-tabler.form-textarea name="pesan" rows="2" placeholder="Tulis pesan..." required="true" />
                    <div class="text-end">
                        <x-tabler.button type="submit" class="btn-primary btn-sm" title="Kirim" icon="ti ti-send" text="Kirim Pesan" />
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@if($layanan->jenisLayanan->is_feedback && !$layanan->feedback)
<!-- Modal Feedback -->
<!-- Modal Feedback -->
<x-tabler.form-modal
    id="modal-feedback"
    title="Beri Penilaian Layanan"
    route="{{ route('eoffice.feedback.store') }}"
    method="POST"
    submitText="Kirim Feedback"
    submitIcon="ti-star"
    class="btn-warning ms-auto"
>
    <input type="hidden" name="layanan_id" value="{{ $layanan->encrypted_layanan_id }}">
    
    <div class="text-center py-4">
        <div class="mb-3">
            <div class="text-muted mb-2">Ketuk bintang untuk memberi nilai</div>
            <div class="rating-stars">
                @for($i=1; $i<=5; $i++)
                    <input type="radio" name="rating" id="rating-{{ $i }}" value="{{ $i }}" class="d-none">
                    <label for="rating-{{ $i }}" class="cursor-pointer">
                        <i class="ti ti-star fs-1 text-muted star-icon" data-value="{{ $i }}"></i>
                    </label>
                @endfor
            </div>
            <div class="mt-2 fw-bold text-warning" id="rating-text">Pilih rating...</div>
        </div>
        <div class="mb-3 text-start">
            <x-tabler.form-textarea name="catatan" label="Catatan / Masukan (Opsional)" rows="3" placeholder="Ceritakan pengalaman Anda..." />
        </div>
    </div>
</x-tabler.form-modal>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll chat to bottom
        let chatContainer = document.getElementById('chat-container');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Ajax Confirm for Batal
        $('.ajax-confirm').on('click', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let title = $(this).data('title') || 'Konfirmasi';
            let text = $(this).data('text') || 'Apakah Anda yakin?';

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        document.addEventListener('form-success', function() {
            window.location.reload();
        });

        // Star Rating Logic
        const stars = document.querySelectorAll('.star-icon');
        const ratingText = document.getElementById('rating-text');
        const ratingLabels = ['Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'];

        stars.forEach(star => {
            star.addEventListener('mouseover', function() {
                let val = this.dataset.value;
                highlightStars(val);
            });

            star.addEventListener('mouseout', function() {
                let checked = document.querySelector('input[name="rating"]:checked');
                if (checked) {
                    highlightStars(checked.value);
                } else {
                    resetStars();
                }
            });

            star.addEventListener('click', function() {
                let val = this.dataset.value;
                document.getElementById('rating-' + val).checked = true;
                highlightStars(val);
                ratingText.textContent = ratingLabels[val - 1];
            });
        });

        function highlightStars(count) {
            stars.forEach(s => {
                if (s.dataset.value <= count) {
                    s.classList.remove('text-muted');
                    s.classList.add('text-yellow');
                    s.classList.remove('ti-star');
                    s.classList.add('ti-star-filled');
                } else {
                    s.classList.add('text-muted');
                    s.classList.remove('text-yellow');
                    s.classList.add('ti-star');
                    s.classList.remove('ti-star-filled');
                }
            });
        }

        function resetStars() {
            stars.forEach(s => {
                s.classList.add('text-muted');
                s.classList.remove('text-yellow');
                s.classList.add('ti-star');
                s.classList.remove('ti-star-filled');
            });
            ratingText.textContent = 'Pilih rating...';
        }
    });
</script>
@endpush
