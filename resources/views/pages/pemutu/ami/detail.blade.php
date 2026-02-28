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

        {{-- SECTION B: Tab Hasil Evaluasi Diri & Hasil AMI --}}
        <div class="card mb-4">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-ed" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                            <i class="ti ti-clipboard-data me-2"></i>B1. Hasil Evaluasi Diri
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-hasil-ami" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            <i class="ti ti-file-check me-2"></i>B2. Hasil AMI
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                {{-- TAB: Hasil Evaluasi Diri --}}
                <div class="tab-pane active show" id="tabs-ed" role="tabpanel">
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
                                    <div class="card-body p-2">
                                        <div class="row align-items-center">
                                            <div class="col-auto pe-3 border-end">
                                                <div class="fs-2 fw-bold {{ $isChosen ? 'text-primary' : 'text-muted' }} mb-0 ms-3">
                                                    {{ $level }}
                                                </div>
                                            </div>
                                            <div class="col ps-3">
                                                <div class="small {{ $isChosen ? 'text-primary fw-semibold' : 'text-muted' }}">
                                                    {!! $desc !!}
                                                </div>
                                            </div>
                                            @if($isChosen)
                                            <div class="col-auto">
                                                <i class="ti ti-check-circle-filled text-primary fs-3"></i>
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
                            <p class="mt-2">Evaluasi Diri belum diisi auditee.</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- TAB: Hasil AMI --}}
                <div class="tab-pane" id="tabs-hasil-ami" role="tabpanel">
                    <div class="card-body">
                        @php
                            $hasilSaatIni = $indOrg->ami_hasil_akhir !== null ? ($hasilAkhirLabels[$indOrg->ami_hasil_akhir] ?? null) : null;
                        @endphp

                        @if($hasilSaatIni)
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="text-uppercase text-muted small fw-bold mb-1">Hasil Akhir AMI</div>
                                    <div class="fs-3 fw-bold text-{{ $hasilSaatIni['color'] }} mb-1">
                                        <span class="badge bg-{{ $hasilSaatIni['color'] }}-lt fs-4">{{ $hasilSaatIni['label'] }}</span>
                                    </div>
                                    <div class="small text-muted">{{ $hasilSaatIni['desc'] }}</div>
                                </div>
                                
                                <div class="col-md-8">
                                    <div class="text-uppercase text-muted small fw-bold mb-1">Temuan Umum</div>
                                    <div class="fs-4">{{ $indOrg->ami_hasil_temuan ?? '—' }}</div>
                                </div>

                                {{-- Jika KTS --}}
                                @if($indOrg->ami_hasil_akhir === 0)
                                    <div class="col-12 mt-4">
                                        <div class="alert alert-danger">
                                            <h4 class="alert-title"><i class="ti ti-alert-triangle me-1"></i>Detail Ketidaksesuaian (KTS)</h4>
                                            <div class="mt-2">
                                                <div class="mb-2">
                                                    <strong>Sebab:</strong>
                                                    <div class="mt-1 bg-white p-2 border rounded small">{!! nl2br(e($indOrg->ami_hasil_temuan_sebab ?? '—')) !!}</div>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Akibat:</strong>
                                                    <div class="mt-1 bg-white p-2 border rounded small">{!! nl2br(e($indOrg->ami_hasil_temuan_akibat ?? '—')) !!}</div>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Rekomendasi Tindak Lanjut:</strong>
                                                    <div class="mt-1 bg-white p-2 border rounded small">{!! nl2br(e($indOrg->ami_hasil_temuan_rekom ?? '—')) !!}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <x-tabler.empty-state
                                title="Belum Ada Hasil AMI"
                                message="Auditor belum menyimpan penilaian untuk indikator ini."
                                icon="ti-clipboard-x"
                            />
                        @endif
                    </div>
                </div>
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

        {{-- SECTION C & D: Diskusi & Penilaian Auditor --}}
        <div class="card mb-4 sticky-top" style="top: 80px;">
            <div class="card-header p-0">
                <ul class="nav nav-tabs card-header-tabs w-100 m-0" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item flex-fill text-center" role="presentation">
                        <a href="#tabs-diskusi" class="nav-link active justify-content-center" data-bs-toggle="tab" aria-selected="true" role="tab" style="border-top-left-radius: 4px;">
                            <i class="ti ti-messages me-2"></i>Diskusi
                            @if($indOrg->diskusi->count() > 0)
                                <span class="badge bg-secondary ms-2">{{ $indOrg->diskusi->count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item flex-fill text-center" role="presentation">
                        <a href="#tabs-penilaian" class="nav-link justify-content-center" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1" style="border-top-right-radius: 4px;">
                            <i class="ti ti-gavel me-2"></i>Penilaian
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                {{-- TAB: DISKUSI --}}
                <div class="tab-pane active show" id="tabs-diskusi" role="tabpanel">
                    {{-- Chat Messages --}}
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;" id="chat-container">
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
                            <div style="max-width: 80%;">
                                <div class="d-flex align-items-center gap-2 mb-1 {{ $isSelf ? 'flex-row-reverse' : '' }}">
                                    <span class="fw-bold small">{{ $msg->pengirim->name ?? 'Unknown' }}</span>
                                    <span class="badge bg-{{ $color }}-lt text-{{ $color }} py-0 px-1 small">{{ ucfirst($msg->jenis_pengirim) }}</span>
                                    <span class="text-muted smaller">{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                                <div class="card mb-0 {{ $isSelf ? 'bg-primary-lt' : 'bg-gray-100' }}" style="border-radius: 12px;">
                                    <div class="card-body py-2 px-3">
                                        <div class="small text-rjustify">{!! nl2br(e($msg->isi)) !!}</div>
                                        @if($msg->attachment_file)
                                        <div class="mt-2 text-{{ $isSelf ? 'end' : 'start' }}">
                                            <a href="{{ route('pemutu.diskusi.download', encryptId($msg->diskusi_id)) }}" target="_blank" class="btn btn-ghost-secondary btn-sm py-0">
                                                <i class="ti ti-paperclip me-1"></i> Lampiran
                                            </a>
                                        </div>
                                        @endif
                                        @if(!empty($msg->attachment_link))
                                        <div class="mt-1 text-{{ $isSelf ? 'end' : 'start' }}">
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
                            <p class="mt-2 small">Belum ada diskusi.</p>
                        </div>
                        @endforelse
                    </div>

                    {{-- Form Kirim Diskusi --}}
                    <div class="card-footer bg-light p-2 border-top">
                        <form action="{{ route('pemutu.diskusi.store-ami', $indOrg->encrypted_indorgunit_id) }}" method="POST" enctype="multipart/form-data" class="ajax-form" data-redirect="true">
                            @csrf
                            <input type="hidden" name="jenis_pengirim" value="auditor">
                            <input type="hidden" name="jenis_diskusi" value="ami">
                            <x-tabler.form-textarea name="isi" rows="2" placeholder="Tuliskan pesan..." required="true" />
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="w-100 me-2" style="max-width: 200px;">
                                    <input type="file" class="form-control form-control-sm" name="attachment_file" accept=".pdf,.doc,.docx,.jpg,.png" title="Lampirkan File" />
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="ti ti-send"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- TAB: PENILAIAN --}}
                <div class="tab-pane" id="tabs-penilaian" role="tabpanel">
                    <div class="card-body">
                        <form action="{{ route('pemutu.ami.submit-nilai', $indOrg->encrypted_indorgunit_id) }}" method="POST" class="ajax-form" data-redirect="true">
                            @csrf

                            {{-- Pilih status --}}
                            <div class="mb-3">
                                <label class="form-label required fw-semibold">Hasil Akhir AMI</label>
                                <div class="row g-2">
                                    @foreach($hasilAkhirLabels as $value => $meta)
                                    <div class="col-12">
                                        <label class="form-check border rounded p-2 cursor-pointer w-100
                                            {{ $indOrg->ami_hasil_akhir === $value ? 'border-' . $meta['color'] . ' bg-' . $meta['color'] . '-lt' : '' }}">
                                            <input class="form-check-input mt-1" type="radio" name="ami_hasil_akhir" value="{{ $value }}"
                                                id="radio_hasil_{{ $value }}"
                                                {{ $indOrg->ami_hasil_akhir === $value ? 'checked' : '' }} required>
                                            <span class="form-check-label lh-base">
                                                <span class="badge bg-{{ $meta['color'] }}-lt text-{{ $meta['color'] }} me-1">{{ $meta['label'] }}</span>
                                                <span class="text-muted d-block small lh-sm mt-1">{{ $meta['desc'] }}</span>
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
                                    rows="2"
                                    :value="$indOrg->ami_hasil_temuan ?? ''"
                                />
                            </div>

                            {{-- KTS Conditional Fields --}}
                            <div id="kts-fields" class="{{ $indOrg->ami_hasil_akhir === 0 ? '' : 'd-none' }}">
                                <div class="alert alert-danger p-2 mb-3 align-items-center d-flex small">
                                    <strong><i class="ti ti-alert-triangle me-1"></i> Wajib diisi (KTS).</strong>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required fw-semibold small">Sebab</label>
                                    <textarea id="ami_sebab" name="ami_hasil_temuan_sebab" class="form-control" rows="2">{{ $indOrg->ami_hasil_temuan_sebab ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required fw-semibold small">Akibat</label>
                                    <textarea id="ami_akibat" name="ami_hasil_temuan_akibat" class="form-control" rows="2">{{ $indOrg->ami_hasil_temuan_akibat ?? '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required fw-semibold small">Rekomendasi</label>
                                    <textarea id="ami_rekom" name="ami_hasil_temuan_rekom" class="form-control" rows="2">{{ $indOrg->ami_hasil_temuan_rekom ?? '' }}</textarea>
                                </div>
                            </div>

                            <x-tabler.button
                                type="submit"
                                class="btn-primary w-100"
                                icon="ti ti-device-floppy"
                                text="Simpan"
                            />
                        </form>
                    </div>
                </div>
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
