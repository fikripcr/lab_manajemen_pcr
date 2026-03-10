@extends('layouts.tabler.app')
@section('title', 'Detail AMI — ' . ($indikator->no_indikator ?? ''))

@section('header')
<x-tabler.page-header title="Detail Audit Mutu Internal" pretitle="AMI / {{ $indikator->no_indikator }}">
    <x-slot:actions>
        <x-tabler.button type="back" size="sm" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">

    {{-- ===== SINGLE FULL-WIDTH COLUMN ===== --}}
    <div class="col-lg-12">

        {{-- SECTION A: Informasi Indikator --}}
        <x-tabler.card class="mb-4">
            <x-tabler.card-header title='<i class="ti ti-info-circle me-2"></i>A. Informasi Indikator' class="py-3" />
            <x-tabler.card-body>
                {{-- Monitoring Alert --}}
                @if(isset($monitorings) && $monitorings->isNotEmpty())
                    @foreach($monitorings as $mon)
                        <div class="alert alert-important alert-info mb-3 d-flex align-items-center justify-content-between p-2 px-3">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-broadcast fs-3 me-2"></i>
                                <div>
                                    <span class="fw-bold">Indikator ini dalam Pemantauan:</span> 
                                    {{ $mon->tgl_rapat->format('d M Y') }} — {{ $mon->judul_kegiatan }}
                                </div>
                            </div>
                            <a href="{{ route('Kegiatan.rapat.show', $mon->encrypted_rapat_id) }}" class="btn btn-sm btn-white text-info fw-bold">
                                <i class="ti ti-eye me-1"></i>Detail Rapat
                            </a>
                        </div>
                    @endforeach
                @endif

                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Kode Indikator</div>
                        <div class="fs-3 fw-bold text-primary">{{ $indikator->no_indikator ?? '—' }}</div>
                    </div>
                    <div class="col-md-9">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Pernyataan Standar / Indikator</div>
                        <div class="fs-4">{{ $indikator->indikator }}</div>
                        @if($indikator->keterangan)
                            <div class="mt-2 text-muted bg-light p-3 rounded small border-start border-3 border-info">
                                {!! $indikator->keterangan !!}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Unit Kerja</div>
                        <div class="fw-semibold">{{ $indOrg->orgUnit->name ?? '—' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Target</div>
                        <div class="fw-semibold">{{ $indOrg->target ?? '(Belum ditetapkan)' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Tipe / Jenis</div>
                        <div>
                            @php $typeInfo = pemutuIndikatorTypeInfo($indikator->type); @endphp
                            <span class="badge bg-{{ $typeInfo['color'] }}-lt text-{{ $typeInfo['color'] }}">{{ $typeInfo['label'] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Kelompok</div>
                        <div class="fw-medium">
                            @if(strtolower($indikator->kelompok_indikator ?? '') == 'akademik')
                                <span class="text-blue"><i class="ti ti-book me-1"></i>Akademik</span>
                            @elseif(in_array(strtolower($indikator->kelompok_indikator ?? ''), ['non_akademik', 'non-akademik']))
                                <span class="text-orange"><i class="ti ti-briefcase me-1"></i>Non-Akademik</span>
                            @else
                                {{ $indikator->kelompok_indikator ?? '—' }}
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Label</div>
                        <div>
                            @forelse($indikator->labels as $label)
                                {!! pemutuLabelBadge($label) !!}
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
            </x-tabler.card-body>
        </x-tabler.card>

        {{-- SECTION B: Tab Hasil Evaluasi Diri, Audit & Diskusi --}}
        <x-tabler.card class="mb-4">
            <x-tabler.card-header>
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-ed" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                            <i class="ti ti-clipboard-data me-2"></i>B1. Evaluasi Diri
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-audit" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            <i class="ti ti-gavel me-2"></i>B2. Audit
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-diskusi" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            <i class="ti ti-messages me-2"></i>B3. Diskusi
                            @if($indOrg->diskusi->count() > 0)
                                <span class="badge bg-azure-lt ms-2">{{ $indOrg->diskusi->count() }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </x-tabler.card-header>
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
                                <div class="indicator-scroll">{!! $indOrg->ed_analisis ?? '—' !!}</div>
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

                {{-- TAB: AUDIT (PENILAIAN) --}}
                <div class="tab-pane" id="tabs-audit" role="tabpanel">
                    <div class="card-body">
                        <form action="{{ route('pemutu.ami.submit-nilai', $indOrg->encrypted_indorgunit_id) }}" method="POST" class="ajax-form" data-redirect="true">
                            @csrf

                            <div class="row">
                                <div class="col-md-5 border-end">
                                    <div class="mb-3">
                                        <label class="form-label required fw-semibold">Pilih Hasil Akhir AMI</label>
                                        <div class="row g-2">
                                            @foreach($hasilAkhirLabels as $value => $meta)
                                            <div class="col-12">
                                                <label class="form-check border rounded p-2 ms-4 cursor-pointer
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
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-3">
                                        <x-tabler.form-textarea
                                            name="ami_hasil_temuan"
                                            label="Temuan Umum"
                                            placeholder="Tuliskan temuan audit secara umum..."
                                            rows="3"
                                            :value="$indOrg->ami_hasil_temuan ?? ''"
                                        />
                                    </div>

                                    {{-- KTS Conditional Fields --}}
                                    <div id="kts-fields" class="{{ $indOrg->ami_hasil_akhir === 0 ? '' : 'd-none' }}">
                                        <div class="alert alert-important alert-danger p-2 mb-3 small">
                                            <i class="ti ti-alert-triangle me-1"></i> Mode KTS Aktif: Lengkapi detail sebab, akibat, dan rekomendasi.
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <x-tabler.form-textarea name="ami_hasil_temuan_sebab" label="Sebab" :value="$indOrg->ami_hasil_temuan_sebab ?? ''" rows="3" required="true" id="ami_sebab" />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <x-tabler.form-textarea name="ami_hasil_temuan_akibat" label="Akibat" :value="$indOrg->ami_hasil_temuan_akibat ?? ''" rows="3" required="true" id="ami_akibat" />
                                            </div>
                                            <div class="col-12 mb-3">
                                                <x-tabler.form-textarea name="ami_hasil_temuan_rekom" label="Rekomendasi Tindak Lanjut" :value="$indOrg->ami_hasil_temuan_rekom ?? ''" rows="3" required="true" id="ami_rekom" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-end">
                                        <x-tabler.button type="submit" size="lg" icon="ti ti-device-floppy" text="Simpan Penilaian Audit" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- TAB: DISKUSI --}}
                <div class="tab-pane" id="tabs-diskusi" role="tabpanel">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7 border-end">
                                {{-- Chat Messages --}}
                                <div class="chat-wrapper" style="max-height: 500px; overflow-y: auto;" id="chat-container">
                                    @forelse($indOrg->diskusi as $msg)
                                    @php
                                        $isSelf = $msg->pengirim_user_id === auth()->id();
                                        $color  = $msg->jenis_pengirim === 'auditor' ? 'primary' : 'azure';
                                    @endphp
                                    <div class="d-flex align-items-start mb-3 {{ $isSelf ? 'flex-row-reverse' : '' }}">
                                        <div class="avatar avatar-sm bg-{{ $color }}-lt text-{{ $color }} me-2 {{ $isSelf ? 'ms-2 me-0' : '' }}">
                                            <i class="ti ti-user"></i>
                                        </div>
                                        <div style="max-width: 85%;">
                                            <div class="d-flex align-items-center gap-2 mb-1 {{ $isSelf ? 'flex-row-reverse' : '' }}">
                                                <span class="fw-bold small">{{ $msg->pengirim->name ?? 'Unknown' }}</span>
                                                <span class="badge bg-{{ $color }}-lt text-{{ $color }} py-0 px-1 smaller">{{ ucfirst($msg->jenis_pengirim) }}</span>
                                                <span class="text-muted smaller">{{ $msg->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="card mb-0 {{ $isSelf ? 'bg-primary-lt' : 'bg-secondary-lt' }} border-0" style="border-radius: 12px;">
                                                <div class="card-body py-2 px-3">
                                                    <div class="small">{!! nl2br(e($msg->isi)) !!}</div>
                                                    @if($msg->attachment_file)
                                                    <div class="mt-2 border-top pt-1 text-{{ $isSelf ? 'end' : 'start' }}">
                                                        <a href="{{ route('pemutu.diskusi.download', encryptId($msg->diskusi_id)) }}" target="_blank" class="badge bg-white text-dark border py-1">
                                                            <i class="ti ti-paperclip me-1"></i> Lampiran File
                                                        </a>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-5 text-muted">
                                        <i class="ti ti-message-off fs-1"></i>
                                        <p class="mt-2">Belum ada diskusi untuk indikator ini.</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="col-md-5">
                                {{-- Form Kirim Diskusi --}}
                                <div class="p-2">
                                    <h4 class="mb-3"><i class="ti ti-send me-1"></i>Kirim Pesan Baru</h4>
                                    <form action="{{ route('pemutu.diskusi.store-ami', $indOrg->encrypted_indorgunit_id) }}" method="POST" enctype="multipart/form-data" class="ajax-form" data-redirect="true">
                                        @csrf
                                        <input type="hidden" name="jenis_diskusi" value="ami">
                                        <div class="mb-3">
                                            <x-tabler.form-textarea name="isi" rows="4" placeholder="Tuliskan pesan..." required="true" label="Pesan" />
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Lampiran (Opsional)</label>
                                            <input type="file" name="attachment_file" class="filepond" />
                                        </div>

                                        <div class="row g-2 align-items-center">
                                            <div class="col-sm-7">
                                                <x-tabler.form-select name="jenis_pengirim" required="true">
                                                    <option value="auditor" selected>Kirim sebagai: Auditor</option>
                                                    <option value="auditee">Kirim sebagai: Auditee</option>
                                                </x-tabler.form-select>
                                            </div>
                                            <div class="col-sm-5 text-end">
                                                <x-tabler.button type="submit" class="w-100" icon="ti ti-send" text="Kirim Pesan" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-tabler.card>

    </div>{{-- end single full-width column --}}


</div>

@push('scripts')
<script type="module">
window.loadFilePond();

// Init HugeRTE untuk KTS fields
if (window.loadHugeRTE) {
    const ktsConfig = {
        height: 180, menubar: false, statusbar: false,
        plugins: 'lists',
        toolbar: 'bold italic | bullist numlist',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
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

// Simpan state Tab Aktif menggunakan sessionStorage
const tabKey = 'ami_detail_active_tab';

// Restore active tab
const savedHref = sessionStorage.getItem(tabKey);
if (savedHref) {
    const targetTab = document.querySelector(`a[data-bs-toggle="tab"][href="${savedHref}"]`);
    if (targetTab) {
        const tabEl = new bootstrap.Tab(targetTab);
        tabEl.show();
    }
}

// Save on tab change
document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function (e) {
        sessionStorage.setItem(tabKey, e.target.getAttribute('href'));
    });
});
</script>
@endpush
@endsection
