@extends('layouts.tabler.app')
@section('title', 'Detail AMI — ' . ($indikator->no_indikator ?? ''))

@section('header')
<x-tabler.page-header title="Detail Audit Mutu Internal" pretitle="AMI / {{ $indikator->no_indikator }}">
    <x-slot:actions>
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
            <i class="ti ti-arrow-left me-1"></i> Kembali
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">

    {{-- ===== KOLOM KIRI ===== --}}
    <div class="col-lg-8">

        {{-- SECTION A: Informasi Indikator --}}
        <div class="card mb-4">
            <div class="card-header py-3">
                <h3 class="card-title mb-0">
                    <i class="ti ti-info-circle me-2"></i>A. Informasi Indikator
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Kode Indikator</div>
                        <div class="fs-3 fw-bold text-primary">{{ $indikator->no_indikator ?? '—' }}</div>
                    </div>
                    <div class="col-md-9">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Pernyataan Standar / Indikator</div>
                        <div class="fs-4">{{ $indikator->indikator }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Unit Kerja</div>
                        <div class="fw-semibold">{{ $indOrg->orgUnit->name ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Target</div>
                        <div class="fw-semibold">{{ $indOrg->target ?? '(Belum ditetapkan)' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Label</div>
                        <div>
                            @forelse($indikator->labels as $label)
                                <span class="badge bg-{{ $label->color ?? 'secondary' }}-lt text-{{ $label->color ?? 'secondary' }}">{{ $label->name }}</span>
                            @empty
                                <span class="text-muted">—</span>
                            @endforelse
                        </div>
                    </div>
                    @if(count($breadcrumbs) > 1)
                    <div class="col-12">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Hierarki</div>
                        <ol class="breadcrumb breadcrumb-arrows fst-italic mb-0">
                            @foreach($breadcrumbs as $b)
                                <li class="breadcrumb-item {{ $loop->last ? 'active fw-bold' : '' }}">{{ $b->no_indikator ?? '-' }}</li>
                            @endforeach
                        </ol>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- SECTION B: Hasil Evaluasi Diri + Highlight Skala --}}
        <div class="card mb-4">
            <div class="card-header py-3">
                <h3 class="card-title mb-0">
                    <i class="ti ti-clipboard-data me-2"></i>B. Hasil Evaluasi Diri
                </h3>
            </div>
            <div class="card-body">
                @if($indOrg->ed_capaian)
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Capaian</div>
                        <div class="fw-semibold fs-4">{{ $indOrg->ed_capaian }}</div>
                        <div class="text-uppercase text-muted small fw-bold mb-1 mt-3">Bukti & Dokumen</div>
                        <div class="d-flex flex-wrap align-items-center">
                            @if($indOrg->ed_attachment)
                                <a href="{{ route('pemutu.evaluasi-diri.download', $indOrg->encrypted_indorgunit_id) }}" target="_blank" class="btn btn-sm btn-ghost-primary me-1 mb-1" title="Unduh File Pendukung" data-bs-toggle="tooltip">
                                    <i class="ti ti-file-download fs-3"></i>
                                </a>
                            @endif
                            @if(!empty($indOrg->ed_links))
                                @foreach($indOrg->ed_links as $link)
                                    <a href="{{ $link['url'] ?? '#' }}" target="_blank" class="btn btn-sm btn-ghost-info me-1 mb-1" title="{{ htmlspecialchars($link['name'] ?? 'Tautan') }}" data-bs-toggle="tooltip">
                                        <i class="ti ti-link fs-3"></i>
                                    </a>
                                @endforeach
                            @endif
                            @if(!$indOrg->ed_attachment && empty($indOrg->ed_links))
                                <span class="text-muted small fst-italic">Tidak ada bukti terlampir</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Analisis</div>
                        <div style="max-height: 10em; overflow-y:scroll">{{ $indOrg->ed_analisis ?? '—' }}</div>
                    </div>


                </div>

                {{-- Highlight Skala --}}
                @if(!empty($skala))
                <hr class="my-3">
                <div class="text-uppercase text-muted small fw-bold mb-3">Penilaian Skala (Dipilih Auditee)</div>
                <div class="row g-2">
                    @foreach($skala as $level => $desc)
                    @php 
                        $isChosen = ($indOrg->ed_skala !== null && (int)$indOrg->ed_skala === (int)$level); 
                    @endphp
                    <div class="col-12">
                        <div class="card mb-0 border {{ $isChosen ? 'border-primary bg-primary-lt border-2 shadow-sm' : 'border opacity-75' }}">
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-auto pe-3 border-end">
                                        <div class="fs-1 fw-bold {{ $isChosen ? 'text-primary' : 'text-muted' }} mb-0">
                                            {{ $level }}
                                        </div>
                                    </div>
                                    <div class="col ps-3">
                                        <div class="{{ $isChosen ? 'text-primary fw-semibold' : 'text-muted' }}">
                                            {!! $desc !!}
                                        </div>
                                    </div>
                                    @if($isChosen)
                                    <div class="col-auto">
                                        <i class="ti ti-check-circle-filled text-primary fs-2"></i>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                @else
                <div class="text-center py-4 text-muted">
                    <i class="ti ti-clipboard-x fs-1"></i>
                    <p class="mt-2">Evaluasi Diri belum diisi auitee.</p>
                </div>
                @endif
            </div>
        </div>

        {{-- SECTION C: Diskusi / Chat Interface --}}
        <div class="card mb-4">
            <div class="card-header py-3">
                <h3 class="card-title mb-0">
                    <i class="ti ti-messages me-2"></i>C. Diskusi AMI
                </h3>
                <div class="card-options">
                    <span class="badge bg-secondary">{{ $indOrg->diskusi->count() }} pesan</span>
                </div>
            </div>

            {{-- Chat Messages --}}
            <div class="card-body" style="max-height: 450px; overflow-y: auto;" id="chat-container">
                @forelse($indOrg->diskusi as $msg)
                @php
                    $isSelf = $msg->pengirim_user_id === auth()->id();
                    $align  = $isSelf ? 'end' : 'start';
                    $color  = $msg->jenis_pengirim === 'auditor' ? 'primary' : 'azure';
                @endphp
                <div class="d-flex align-items-start mb-3 {{ $isSelf ? 'flex-row-reverse' : '' }}">
                    <div class="avatar avatar-sm bg-{{ $color }}-lt text-{{ $color }} me-2 {{ $isSelf ? 'ms-2 me-0' : '' }}">
                        <i class="ti ti-user"></i>
                    </div>
                    <div style="max-width: 75%;">
                        <div class="d-flex align-items-center gap-2 mb-1 {{ $isSelf ? 'flex-row-reverse' : '' }}">
                            <span class="fw-bold small">{{ $msg->pengirim->name ?? 'Unknown' }}</span>
                            <span class="badge bg-{{ $color }}-lt text-{{ $color }} py-0 px-1 small">{{ ucfirst($msg->jenis_pengirim) }}</span>
                            <span class="text-muted smaller">{{ $msg->created_at->format('d/m H:i') }}</span>
                        </div>
                        <div class="card mb-0 {{ $isSelf ? 'bg-primary-lt' : 'bg-gray-100' }}" style="border-radius: 12px;">
                            <div class="card-body py-2 px-3">
                                <div class="small text-rjustify">{!! nl2br(e($msg->isi)) !!}</div>
                                @if($msg->attachment_file)
                                <div class="mt-2">
                                    <a href="{{ route('pemutu.diskusi.download', encryptId($msg->diskusi_id)) }}" target="_blank" class="btn btn-ghost-secondary btn-sm py-0">
                                        <i class="ti ti-paperclip me-1"></i> Lampiran
                                    </a>
                                </div>
                                @endif
                                @if(!empty($msg->attachment_link))
                                <div class="mt-1">
                                    @foreach($msg->attachment_link as $link)
                                        <a href="{{ $link['url'] ?? '#' }}" target="_blank" class="btn btn-ghost-primary btn-sm py-0 me-1">
                                            <i class="ti ti-external-link me-1"></i>{{ $link['name'] ?? 'Link' }}
                                        </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="ti ti-message-off fs-1"></i>
                    <p class="mt-2 small">Belum ada diskusi. Mulai dengan mengirim pesan pertama.</p>
                </div>
                @endforelse
            </div>

            {{-- Form Kirim Diskusi --}}
            <div class="card-footer">
                <form action="{{ route('pemutu.diskusi.store-ami', $indOrg->encrypted_indorgunit_id) }}" method="POST" enctype="multipart/form-data" class="ajax-form" data-redirect="true">
                    @csrf
                    <input type="hidden" name="jenis_pengirim" value="auditor">
                    <input type="hidden" name="jenis_diskusi" value="ami">
                    <x-tabler.form-textarea name="isi" rows="2" placeholder="Tulis pesan diskusi..." required="true" />
                    <div class="row g-2">
                        <div class="col mt-2">
                            <x-tabler.form-input type="file" name="attachment_file" accept=".pdf,.doc,.docx,.jpg,.png" />
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ti ti-send me-1"></i>Kirim
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>{{-- end kolom kiri --}}

    {{-- ===== KOLOM KANAN ===== --}}
    <div class="col-lg-4">

        {{-- Status AMI saat ini --}}
        @php
            $hasilSaatIni = $indOrg->ami_hasil_akhir !== null ? ($hasilAkhirLabels[$indOrg->ami_hasil_akhir] ?? null) : null;
        @endphp
        @if($hasilSaatIni)
        <div class="card mb-4 border-{{ $hasilSaatIni['color'] }}">
            <div class="card-body text-center py-3">
                <div class="text-uppercase text-muted small fw-bold mb-1">Hasil AMI Saat Ini</div>
                <div class="display-5 fw-bold text-{{ $hasilSaatIni['color'] }}">{{ $hasilSaatIni['label'] }}</div>
                <div class="text-muted small">{{ $hasilSaatIni['desc'] }}</div>
                @if($indOrg->ami_hasil_temuan)
                <hr>
                <div class="text-start small text-muted">
                    <strong>Temuan:</strong><br>{{ $indOrg->ami_hasil_temuan }}
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- SECTION D: Form Penilaian Auditor --}}
        <div class="card sticky-top" style="top: 80px;">
            <div class="card-header bg-primary-lt py-3">
                <h3 class="card-title text-primary mb-0">
                    <i class="ti ti-gavel me-2"></i>D. Penilaian Auditor
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('pemutu.ami.submit-nilai', $indOrg->encrypted_indorgunit_id) }}" method="POST" class="ajax-form" data-redirect="true">
                    @csrf

                    {{-- Pilih status --}}
                    <div class="mb-3">
                        <label class="form-label required fw-semibold">Hasil Akhir AMI</label>
                        <div class="row g-2">
                            @foreach($hasilAkhirLabels as $value => $meta)
                            <div class="col-12">
                                <label class="form-check border rounded p-3 cursor-pointer w-100
                                    {{ $indOrg->ami_hasil_akhir === $value ? 'border-' . $meta['color'] . ' bg-' . $meta['color'] . '-lt' : '' }}">
                                    <input class="form-check-input" type="radio" name="ami_hasil_akhir" value="{{ $value }}"
                                        id="radio_hasil_{{ $value }}"
                                        {{ $indOrg->ami_hasil_akhir === $value ? 'checked' : '' }} required>
                                    <span class="form-check-label">
                                        <span class="badge bg-{{ $meta['color'] }}-lt text-{{ $meta['color'] }} me-2">{{ $meta['label'] }}</span>
                                        <span class="text-muted small">{{ $meta['desc'] }}</span>
                                    </span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-tabler.form-textarea
                            name="ami_hasil_temuan"
                            label="Catatan Temuan Umum"
                            placeholder="Catatan singkat temuan auditor..."
                            rows="3"
                            :value="$indOrg->ami_hasil_temuan ?? ''"
                        />
                    </div>

                    {{-- KTS Conditional Fields --}}
                    <div id="kts-fields" class="{{ $indOrg->ami_hasil_akhir === 0 ? '' : 'd-none' }}">
                        <div class="alert alert-danger p-2 mb-3">
                            <i class="ti ti-alert-triangle me-1"></i>
                            <strong>Status KTS</strong> — Wajib isi ketiga isian berikut.
                        </div>

                        <div class="mb-3">
                            <label class="form-label required fw-semibold">Sebab Ketidaksesuaian</label>
                            <textarea id="ami_sebab" name="ami_hasil_temuan_sebab" class="form-control" rows="3">{{ $indOrg->ami_hasil_temuan_sebab ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required fw-semibold">Akibat Ketidaksesuaian</label>
                            <textarea id="ami_akibat" name="ami_hasil_temuan_akibat" class="form-control" rows="3">{{ $indOrg->ami_hasil_temuan_akibat ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required fw-semibold">Rekomendasi Tindak Lanjut</label>
                            <textarea id="ami_rekom" name="ami_hasil_temuan_rekom" class="form-control" rows="3">{{ $indOrg->ami_hasil_temuan_rekom ?? '' }}</textarea>
                        </div>
                    </div>

                    <x-tabler.button
                        type="submit"
                        class="btn-primary w-100"
                        icon="ti ti-device-floppy"
                        text="Simpan Penilaian AMI"
                    />
                </form>
            </div>
        </div>

    </div>{{-- end kolom kanan --}}

</div>

@push('scripts')
<script type="module">
window.loadFilePond();

// Init HugeRTE untuk KTS fields
if (window.loadHugeRTE) {
    const ktsConfig = {
        height: 180, menubar: false, statusbar: false,
        plugins: 'lists',
        toolbar: 'bold italic | bullist numlist'
    };
    window.loadHugeRTE('#ami_sebab', ktsConfig);
    window.loadHugeRTE('#ami_akibat', ktsConfig);
    window.loadHugeRTE('#ami_rekom', ktsConfig);
}

// Toggle KTS fields berdasarkan pilihan radio
function toggleKtsFields() {
    const selected = document.querySelector('input[name="ami_hasil_akhir"]:checked');
    const ktsContainer = document.getElementById('kts-fields');
    if (!ktsContainer) return;
    if (selected && selected.value === '0') {
        ktsContainer.classList.remove('d-none');
    } else {
        ktsContainer.classList.add('d-none');
    }
}

document.querySelectorAll('input[name="ami_hasil_akhir"]').forEach(radio => {
    radio.addEventListener('change', toggleKtsFields);
});

// Scroll chat ke bawah otomatis
const chat = document.getElementById('chat-container');
if (chat) chat.scrollTop = chat.scrollHeight;
</script>
@endpush
@endsection
