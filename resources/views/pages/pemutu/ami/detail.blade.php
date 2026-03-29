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

    {{-- ===== KIRI: Informasi Indikator + Evaluasi Diri (col-md-5) ===== --}}
    <div class="col-md-5">

        {{-- Monitoring Alerts --}}
        @if(isset($monitorings) && $monitorings->isNotEmpty())
            @foreach($monitorings as $mon)
                <div class="alert alert-important alert-info mb-3 d-flex align-items-center justify-content-between p-2 px-3">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-broadcast fs-3 me-2"></i>
                        <div>
                            <span class="fw-bold">Dalam Pemantauan:</span>
                            {{ $mon->tgl_rapat->format('d M Y') }} — {{ $mon->judul_kegiatan }}
                        </div>
                    </div>
                    <a href="{{ route('Kegiatan.rapat.show', $mon->encrypted_rapat_id) }}" class="btn btn-sm btn-white text-info fw-bold">
                        <i class="ti ti-eye me-1"></i>Detail
                    </a>
                </div>
            @endforeach
        @endif

        {{-- CARD: Informasi Indikator --}}
        <x-tabler.card class="mb-3">
            <x-tabler.card-header title='<i class="ti ti-info-circle me-2"></i>Informasi Indikator' class="py-3" />
            <x-tabler.card-body>
                <div class="mb-3">
                    <div class="text-uppercase text-muted small fw-bold mb-1">Kode Indikator</div>
                    <div class="fs-3 fw-bold text-primary">{{ $indikator->no_indikator ?? '—' }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-uppercase text-muted small fw-bold mb-1">Pernyataan Standar / Indikator</div>
                    <div>{{ $indikator->indikator }}</div>
                    @if($indikator->keterangan)
                        <div class="mt-2 text-muted bg-light p-2 rounded small border-start border-3 border-info">
                            {!! $indikator->keterangan !!}
                        </div>
                    @endif
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Unit Kerja</div>
                        <div class="fw-semibold">{{ $indOrg->orgUnit->name ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Target</div>
                        <div class="fw-semibold">{{ $indOrg->target ?? '(Belum ditetapkan)' }}</div>
                    </div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Tipe / Jenis</div>
                        @php $typeInfo = pemutuIndikatorTypeInfo($indikator->type); @endphp
                        <span class="badge bg-{{ $typeInfo['color'] }}-lt text-{{ $typeInfo['color'] }}">{{ $typeInfo['label'] }}</span>
                    </div>
                    <div class="col-6">
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
                </div>
                <div>
                    {!! pemutuDtColLabelsList($indikator) !!}
                </div>
                @if(count($breadcrumbs) > 1)
                <hr class="my-2">
                <div>
                    <div class="text-uppercase text-muted small fw-bold mb-1">Hierarki</div>
                    <ol class="breadcrumb breadcrumb-arrows fst-italic mb-0">
                        @foreach($breadcrumbs as $b)
                            <li class="breadcrumb-item {{ $loop->last ? 'active fw-bold' : '' }}">{{ $b->no_indikator ?? '-' }}</li>
                        @endforeach
                    </ol>
                </div>
                @endif
            </x-tabler.card-body>
        </x-tabler.card>

        {{-- CARD: Evaluasi Diri --}}
        <x-tabler.card class="mb-3">
            <x-tabler.card-header title='<i class="ti ti-clipboard-data me-2"></i>Evaluasi Diri' class="py-3" />
            <x-tabler.card-body>
                @if($indOrg->ed_capaian)
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Capaian</div>
                        <div class="fw-semibold fs-4">{{ $indOrg->ed_capaian }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Bukti & Dokumen</div>
                        <div class="d-flex flex-wrap align-items-center">
                            @if($indOrg->hasMedia('ed_attachments'))
                                @foreach($indOrg->getMedia('ed_attachments') as $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-ghost-primary me-1 mb-1" title="Unduh: {{ $media->file_name }}" data-bs-toggle="tooltip">
                                    <i class="ti ti-file-download fs-3"></i>
                                </a>
                                @endforeach
                            @endif
                            @if(!empty($indOrg->ed_links))
                                @foreach($indOrg->ed_links as $link)
                                    <a href="{{ $link['url'] ?? '#' }}" target="_blank" class="btn btn-sm btn-ghost-info me-1 mb-1" title="{{ htmlspecialchars($link['name'] ?? 'Tautan') }}" data-bs-toggle="tooltip">
                                        <i class="ti ti-link fs-3"></i>
                                    </a>
                                @endforeach
                            @endif
                            @if(!$indOrg->hasMedia('ed_attachments') && empty($indOrg->ed_links))
                                <span class="text-muted small fst-italic">Tidak ada bukti terlampir</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="text-uppercase text-muted small fw-bold mb-1">Analisis</div>
                        <div>
                            {!! $indOrg->ed_analisis ?? '<span class="text-muted fst-italic">—</span>' !!}
                        </div>
                    </div>
                </div>

                {{-- Skala Penilaian --}}
                @if(!empty($skala))
                <hr class="my-3">
                <div class="text-uppercase text-muted small fw-bold mb-2">Penilaian Skala (Dipilih Auditee)</div>
                <div class="list-group list-group-flush">
                    @foreach($skala as $level => $desc)
                    @php
                        $isChosen = ($indOrg->ed_skala !== null && (int)$indOrg->ed_skala === (int)$level);
                    @endphp
                    <div class="list-group-item px-2 py-2 {{ $isChosen ? 'bg-primary-lt border-start border-3 border-primary' : '' }}">
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-xs {{ $isChosen ? 'bg-primary text-white' : 'bg-secondary-lt text-muted' }} me-2 rounded">
                                {{ $level }}
                            </span>
                            <div class="small {{ $isChosen ? 'text-primary fw-semibold' : 'text-muted' }} flex-fill">
                                {!! $desc !!}
                            </div>
                            @if($isChosen)
                                <i class="ti ti-circle-check-filled text-primary ms-2"></i>
                            @endif
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
            </x-tabler.card-body>
        </x-tabler.card>

    </div>

    {{-- ===== KANAN: Audit + Diskusi (col-md-7) ===== --}}
    <div class="col-md-7">

        <x-tabler.card class="mb-3">
            <x-tabler.card-header>
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist" id="ami-detail-tabs">
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-audit" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                            <i class="ti ti-gavel me-2"></i>Audit
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-te" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-clipboard-list me-2"></i>Rencana Tindakan Elektif (TE)
                                @if(!request('readonly'))
                                <button type="button" class="btn btn-sm btn-primary ajax-modal-btn ms-2" data-url="{{ route('pemutu.ami.te-edit', $indOrg->encrypted_indorgunit_id) }}" data-title="Tindakan Elektif (TE)">
                                    <i class="ti ti-edit me-1"></i> {{ $teData ? 'Edit TE' : 'Isi TE' }}
                                </button>
                                @endif
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-diskusi" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                            <i class="ti ti-messages me-2"></i>Diskusi
                            @if($indOrg->diskusi->count() > 0)
                                <span class="badge bg-azure-lt ms-2">{{ $indOrg->diskusi->count() }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </x-tabler.card-header>
            <div class="tab-content">
                {{-- TAB: AUDIT (PENILAIAN) --}}
                <div class="tab-pane active show" id="tabs-audit" role="tabpanel">
                    <x-tabler.card-body>
                @if(request('readonly') == 1)
                <div class="alert alert-warning mb-3">
                    <i class="ti ti-info-circle me-1"></i> Mode Read-Only: Jadwal pengisian AMI di luar masa aktif sehingga penginputan dikunci.
                </div>
                @endif
                <fieldset {{ request('readonly') == 1 ? 'disabled' : '' }}>
                <form action="{{ route('pemutu.ami.submit-nilai', $indOrg->encrypted_indorgunit_id) }}" method="POST" id="form-penilaian-ami" class="ajax-form" data-redirect="true">
                    @csrf

                            {{-- Hasil Akhir AMI: Radio Buttons --}}
                            <div class="mb-3">
                                <label class="form-label required fw-semibold">Hasil Akhir AMI</label>
                                <div id="radio-hasil-akhir-container" class="form-selectgroup">
                                    @foreach($hasilAkhirLabels as $value => $meta)
                                    <label class="form-selectgroup-item">
                                        <input type="radio" 
                                               name="ami_hasil_akhir" 
                                               value="{{ $value }}" 
                                               class="form-selectgroup-input"
                                               @checked($indOrg->ami_hasil_akhir === $value) 
                                               required>
                                        <span class="form-selectgroup-button border-{{ $meta['color'] }} text-{{ $meta['color'] }} fw-semibold">
                                            {{ $meta['label'] }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Temuan Umum: Rich Text Editor --}}
                            <div class="mb-3">
                                <x-tabler.form-textarea
                                    name="ami_hasil_temuan"
                                    label="Temuan Umum"
                                    placeholder="Tuliskan temuan audit secara umum..."
                                    rows="4"
                                    id="ami_temuan_umum"
                                    :value="$indOrg->ami_hasil_temuan ?? ''"
                                />
                            </div>

                            {{-- KTS Conditional Fields --}}
                            <div id="kts-fields" class="{{ $indOrg->ami_hasil_akhir === 0 ? '' : 'd-none' }}">
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
                            
                            @if(!request('readonly'))
                    <div class="mt-4 text-end">
                        <x-tabler.button type="submit" class="btn-primary" icon="ti-device-floppy" text="Simpan Penilaian" />
                    </div>
                    @endif
                </form>
                </fieldset>
            </x-tabler.card-body>
                </div>

                {{-- TAB: DISKUSI --}}
                <div class="tab-pane" id="tabs-diskusi" role="tabpanel">
                    <x-tabler.card-body>
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
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-1 {{ $isSelf ? 'flex-row-reverse' : '' }}">
                                                <span class="fw-bold small">{{ $msg->pengirim->name ?? 'Unknown' }}</span>
                                                <span class="badge bg-{{ $color }}-lt text-{{ $color }} py-0 px-1 smaller">{{ ucfirst($msg->jenis_pengirim) }}</span>
                                                <span class="text-muted smaller">{{ $msg->created_at->diffForHumans() }}</span>
                                            </div>
                                            <x-tabler.card class="mb-0 {{ $isSelf ? 'bg-primary-lt' : 'bg-secondary-lt' }} border-0" style="border-radius: 12px;">
                                                <x-tabler.card-body class="py-2 px-3">
                                                    <div class="small">{!! nl2br(e($msg->isi)) !!}</div>
                                                    @if($msg->hasMedia('diskusi_attachments'))
                                                    @php $media = $msg->getFirstMedia('diskusi_attachments'); @endphp
                                                    <div class="mt-2 border-top pt-1 text-{{ $isSelf ? 'end' : 'start' }}">
                                                        <a href="{{ $media->getUrl() }}" target="_blank" class="badge bg-white text-dark border py-1" title="Unduh {{ $media->file_name }}">
                                                            <i class="ti ti-paperclip me-1"></i> {{ $media->file_name }} ({{ $media->human_readable_size }})
                                                        </a>
                                                    </div>
                                                    @endif
                                                </x-tabler.card-body>
                                            </x-tabler.card>
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
                                            <input type="file" name="attachment_file" class="filepond-input" />
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
                    </x-tabler.card-body>
                </div>
            </div>
        </x-tabler.card>

    </div>

</div>

@push('scripts')
<script type="module">
window.loadFilePond();

// Init HugeRTE editors
if (window.loadHugeRTE) {
    const editorConfig = {
        height: 180, menubar: false, statusbar: false,
        plugins: 'lists',
        toolbar: 'bold italic | bullist numlist',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    };
    // Temuan Umum
    window.loadHugeRTE('#ami_temuan_umum', { ...editorConfig, height: 200 });
    // KTS fields
    window.loadHugeRTE('#ami_sebab', editorConfig);
    window.loadHugeRTE('#ami_akibat', editorConfig);
    window.loadHugeRTE('#ami_rekom', editorConfig);
}

// Radio Button Border & KTS toggle
function updateHasilAkhirState() {
    const radios = document.querySelectorAll('input[name="ami_hasil_akhir"]');
    const ktsContainer = document.getElementById('kts-fields');
    let isKts = false;
    
    radios.forEach(radio => {
        if (radio.checked) {
            if (radio.value === '0') isKts = true;
        }
    });
    
    // Toggle KTS
    if (ktsContainer) {
        if (isKts) {
            ktsContainer.classList.remove('d-none');
        } else {
            ktsContainer.classList.add('d-none');
        }
    }
}

document.querySelectorAll('input[name="ami_hasil_akhir"]').forEach(radio => {
    radio.addEventListener('change', updateHasilAkhirState);
});
updateHasilAkhirState();

// Scroll chat ke bawah otomatis
const chat = document.getElementById('chat-container');
if (chat) chat.scrollTop = chat.scrollHeight;
</script>
@endpush
@endsection
