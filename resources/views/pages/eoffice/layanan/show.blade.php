@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="{{ $layanan->no_layanan }}" pretitle="{{ $layanan->jenisLayanan->nama_layanan }}">
    <x-slot:actions>
        <div class="btn-list">
            <a href="{{ route('eoffice.layanan.index') }}" class="btn btn-link link-secondary">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
            <div class="dropdown">
                <button type="button" class="btn btn-ghost-secondary dropdown-toggle" data-bs-toggle="dropdown">Action</button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item {{ in_array($layanan->latestStatus->status_layanan, ['Selesai', 'Selesai (Otomatis)']) ? '' : 'disabled' }}" href="{{ route('eoffice.layanan.download-pdf', encryptId($layanan->layanan_id)) }}">
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
    {{-- Left Column: Main Info & Discussion --}}
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

        {{-- 2. Discussion Forum (Chat UI) --}}
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
                        <div class="mb-3 {{ $chat->created_by == Auth::id() ? 'text-end' : '' }}">
                            <div class="d-inline-block p-2 rounded-3 {{ $chat->created_by == Auth::id() ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 80%;">
                                @if($chat->created_by != Auth::id())
                                    <div class="small fw-bold border-bottom mb-1 pb-1">
                                        {{ $chat->user->name }} ({{ $chat->status_pengirim }})
                                    </div>
                                @endif
                                <div class="chat-text" style="white-space: pre-wrap;">{{ $chat->pesan }}</div>
                                @if($chat->file_lampiran)
                                    <div class="mt-2 pt-2 border-top small">
                                        <a href="{{ Storage::url($chat->file_lampiran) }}" target="_blank" class="{{ $chat->created_by == Auth::id() ? 'text-white' : 'text-primary' }}">
                                            <i class="ti ti-paperclip"></i> Lampiran
                                        </a>
                                    </div>
                                @endif
                                <div class="small mt-1 {{ $chat->created_by == Auth::id() ? 'text-white-50' : 'text-muted' }}">
                                    {{ $chat->created_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="ti ti-messages fs-1"></i>
                            <p class="mt-2">Belum ada diskusi. Mulai percakapan jika ada hal yang perlu ditanyakan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer border-top">
                <form action="{{ route('eoffice.layanan.diskusi.store') }}" method="POST" class="ajax-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="layanan_id" value="{{ encryptId($layanan->layanan_id) }}">
                    <div class="input-group">
                        <textarea name="pesan" class="form-control" rows="1" placeholder="Ketik pesan..." required></textarea>
                        <button type="submit" class="btn btn-primary btn-icon" title="Kirim">
                            <i class="ti ti-send"></i>
                        </button>
                    </div>
                    <div class="mt-2">
                        <input type="file" name="file_lampiran" class="form-control form-control-sm" title="Lampiran (PDF/IMG)">
                    </div>
                </form>
            </div>
        </div>

        {{-- 3. Data Isian Display --}}
        <div class="card">
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
                            @foreach($dataIsian['Pemohon'] as $field)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small uppercase fw-bold">{{ $field->nama_isian }}</label>
                                    <div class="form-control-plaintext">
                                        @if(str_contains($field->isi, 'eoffice/requests/'))
                                            <a href="{{ Storage::url($field->isi) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="ti ti-download"></i> Unduh Berkas
                                            </a>
                                        @else
                                            {!! nl2br(e($field->isi)) ?? '-' !!}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
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
                                            @if(str_contains($field->isi, 'eoffice/requests/'))
                                                <a href="{{ Storage::url($field->isi) }}" target="_blank" class="btn btn-sm btn-pill btn-ghost-info">
                                                    <i class="ti ti-file"></i> Berkas
                                                </a>
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
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column: Actions & Entities --}}
    <div class="col-lg-4">
        
        {{-- 4. Action Section (Workflow) --}}
        @if($canAction)
            <div class="card mb-3 bg-primary-lt border-primary shadow-sm hover-shadow">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-bolt me-1"></i> Aksi Pengolahan</h3>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @if($layanan->latestStatus->status_layanan === 'Diajukan')
                            <div class="col-12">
                                <a href="{{ route('eoffice.layanan.update-status', [encryptId($layanan->layanan_id), 'proses']) }}" class="btn btn-primary w-100">
                                    <i class="ti ti-player-play"></i> Terima & Proses
                                </a>
                            </div>
                        @else
                            {{-- Unified form for status updates --}}
                            <form action="{{ route('eoffice.layanan.update-status', encryptId($layanan->layanan_id)) }}" method="POST" class="ajax-form w-100" enctype="multipart/form-data">
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

                                <div class="mb-3">
                                    <label class="form-label">Keterangan / Pesan ke Pemohon</label>
                                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Wajib jika Tolak / Revisi"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">File Output / Lampiran</label>
                                    <input type="file" name="file_lampiran" class="form-control">
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary w-100">Kirim Perubahan Status</button>
                                </div>
                            </form>
                        @endif

                        @if($layanan->latestStatus->status_layanan !== 'Diajukan')
                            <div class="col-12 mt-2">
                                <a href="{{ route('eoffice.layanan.update-status', [encryptId($layanan->layanan_id), 'batal']) }}" class="btn btn-ghost-danger btn-sm w-100 ajax-confirm" data-title="Batal Proses?" data-text="Status akan kembali ke Antrian/Diajukan.">
                                    <i class="ti ti-history"></i> Batal Proses
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- 5. Entities (Applicant & PIC) --}}
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
    </div>
</div>
@endsection

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
    });
</script>
@endpush
