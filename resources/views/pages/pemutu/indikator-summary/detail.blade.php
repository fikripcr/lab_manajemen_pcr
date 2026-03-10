@extends('layouts.tabler.app')
@section('title', $pageTitle)

@section('header')
<x-tabler.page-header :title="$pageTitle" pretitle="Detail Lengkap Indikator">
    <x-slot:actions>
        <x-tabler.button type="back" :href="route('pemutu.indikator-summary.index')" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row">
    {{-- Info Umum Indikator --}}
    <div class="col-12">
        <x-tabler.card>
            <x-tabler.card-header title="Informasi Umum" icon="ti ti-info-circle" />
            <x-tabler.card-body>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" style="width: 200px;">Nomor Indikator</td>
                                <td><strong class="fs-4">{{ $indikator->no_indikator ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tipe</td>
                                <td>
                                    @php
                                        $typeColors = ['renop' => 'primary', 'standar' => 'success', 'performa' => 'info'];
                                        $color = $typeColors[$indikator->type] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}-lt">{{ ucfirst($indikator->type) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kelompok Indikator</td>
                                <td>{{ $indikator->kelompok_indikator ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Parent Indikator</td>
                                <td>
                                    @if($indikator->parent_no_indikator)
                                        <span class="badge bg-azure-lt">{{ $indikator->parent_no_indikator }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" style="width: 200px;">Jenis Indikator</td>
                                <td>{{ $indikator->jenis_indikator ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jenis Data</td>
                                <td>{{ $indikator->jenis_data ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Unit Ukuran</td>
                                <td>{{ $indikator->unit_ukuran ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Level Risk</td>
                                <td>{{ $indikator->level_risk ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pernyataan Indikator:</label>
                            <p class="fs-5">{{ $indikator->indikator ?? '-' }}</p>
                        </div>
                        @if($indikator->target_indikator)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Target:</label>
                            <p class="fs-5 text-primary">{{ $indikator->target_indikator }}</p>
                        </div>
                        @endif
                        @if($indikator->keterangan)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Keterangan:</label>
                            <p class="text-muted">{{ $indikator->keterangan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>

    {{-- Labels & DokSub --}}
    @if($indikator->all_labels || $indikator->all_doksub_judul)
    <div class="col-md-6">
        <x-tabler.card>
            <x-tabler.card-header title="Labels" icon="ti ti-tag" />
            <x-tabler.card-body>
                @if($indikator->all_labels)
                    @php
                        $labels = explode(', ', $indikator->all_labels);
                        $colors = explode(', ', $indikator->all_label_colors ?? '');
                    @endphp
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($labels as $index => $label)
                            <span class="badge bg-{{ $colors[$index] ?? 'secondary' }}-lt fs-6 py-2 px-3">
                                {{ $label }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <span class="text-muted">Tidak ada label</span>
                @endif
            </x-tabler.card-body>
        </x-tabler.card>
    </div>

    <div class="col-md-6">
        <x-tabler.card>
            <x-tabler.card-header title="Dokumen Terkait" icon="ti ti-files" />
            <x-tabler.card-body>
                @if($indikator->all_doksub_judul)
                    @php
                        $judul = explode(' || ', $indikator->all_doksub_judul);
                        $kode = explode(' || ', $indikator->all_doksub_kode ?? '');
                    @endphp
                    <ul class="list-group list-group-flush">
                        @foreach($judul as $index => $j)
                            <li class="list-group-item px-0">
                                <strong>{{ $kode[$index] ?? '' }}</strong> - {{ $j }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <span class="text-muted">Tidak ada dokumen terkait</span>
                @endif
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
    @endif

    {{-- Summary Statistics --}}
    <div class="col-12">
        <x-tabler.card>
            <x-tabler.card-header title="Ringkasan Statistik" icon="ti ti-chart-bar" />
            <x-tabler.card-body>
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <div class="text-muted">Total Unit Organisasi</div>
                            <div class="h2 mb-0">{{ $indikator->total_org_units ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <div class="text-muted">Isi Evaluasi Diri</div>
                            <div class="h2 mb-0 text-success">{{ $indikator->ed_filled_units ?? 0 }}</div>
                            <small class="text-muted">dari {{ $indikator->total_org_units ?? 0 }} unit</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <div class="text-muted">Pelaksanaan AMI</div>
                            <div class="h2 mb-0 text-primary">{{ $indikator->ami_assessed_units ?? 0 }}</div>
                            <small class="text-muted">
                                <span class="text-danger">{{ $indikator->ami_kts_units ?? 0 }}</span> KTS |
                                <span class="text-success">{{ $indikator->ami_terpenuhi_units ?? 0 }}</span> Terpenuhi |
                                <span class="text-info">{{ $indikator->ami_terlampaui_units ?? 0 }}</span> Terlampaui
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <div class="text-muted">Pengendalian</div>
                            <div class="h2 mb-0 text-info">{{ $indikator->pengend_filled_units ?? 0 }}</div>
                            <small class="text-muted">unit aktif</small>
                        </div>
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>

    {{-- ED Details --}}
    <div class="col-12">
        <x-tabler.card>
            <x-tabler.card-header title="Evaluasi Diri (ED)" icon="ti ti-file-check" />
            <x-tabler.card-body>
                @if($edDetails->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th>Capaian</th>
                                    <th>Skala</th>
                                    <th>Analisis</th>
                                    <th>Bukti</th>
                                    <th>Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($edDetails as $ed)
                                <tr>
                                    <td>
                                        <strong>{{ $ed->unit_name }}</strong>
                                        <br><small class="text-muted">{{ $ed->unit_code }}</small>
                                    </td>
                                    <td>{{ $ed->ed_capaian ?? '-' }}</td>
                                    <td>
                                        @if($ed->ed_skala)
                                            <span class="badge bg-{{ $ed->ed_skala >= 3 ? 'success' : ($ed->ed_skala >= 2 ? 'warning' : 'danger') }}-lt">
                                                {{ $ed->ed_skala }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <x-tabler.button type="button" class="btn-link btn-sm"
                                            data-bs-toggle="collapse" data-bs-target="#ed-analisis-{{ $loop->index }}"
                                            text="Lihat Analisis" />
                                        <div class="collapse" id="ed-analisis-{{ $loop->index }}">
                                            <x-tabler.card-body small bg-light>
                                                {{ $ed->ed_analisis ?? '-' }}
                                            </x-tabler.card-body>
                                        </div>
                                    </td>
                                    <td>
                                        @if($ed->ed_attachment)
                                            <x-tabler.button size="sm" style="ghost-primary" text="Detail" />
                                        @endif
                                        @if($ed->ed_links)
                                            @php $links = json_decode($ed->ed_links, true) ?? []; @endphp
                                            @foreach($links as $link)
                                                <a href="{{ $link['url'] ?? '#' }}" target="_blank" class="btn btn-sm btn-ghost-info">
                                                    <i class="ti ti-link"></i> {{ $link['name'] ?? 'Link' }}
                                                </a>
                                            @endforeach
                                        @endif
                                        @if(!$ed->ed_attachment && empty($ed->ed_links))
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($ed->updated_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-file-x text-muted fs-1"></i>
                        <p class="text-muted mt-2">Belum ada data Evaluasi Diri</p>
                    </div>
                @endif
            </x-tabler.card-body>
        </x-tabler.card>
    </div>

    {{-- AMI Details --}}
    <div class="col-12">
        <x-tabler.card>
            <x-tabler.card-header title="Audit Mutu Internal (AMI)" icon="ti ti-user-search" />
            <x-tabler.card-body>
                @if($amiDetails->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th>Hasil</th>
                                    <th>Temuan</th>
                                    <th>Sebab</th>
                                    <th>Akibat</th>
                                    <th>Rekomendasi</th>
                                    <th>Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($amiDetails as $ami)
                                @php
                                    $hasilLabels = [0 => ['label' => 'KTS', 'color' => 'danger'], 1 => ['label' => 'Terpenuhi', 'color' => 'success'], 2 => ['label' => 'Terlampaui', 'color' => 'info']];
                                    $hasil = $hasilLabels[$ami->ami_hasil_akhir] ?? ['label' => '-', 'color' => 'secondary'];
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $ami->unit_name }}</strong>
                                        <br><small class="text-muted">{{ $ami->unit_code }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $hasil['color'] }}-lt fs-6">{{ $hasil['label'] }}</span>
                                    </td>
                                    <td>
                                        @if($ami->ami_hasil_temuan)
                                            <x-tabler.button type="button" class="btn-link btn-sm"
                                                data-bs-toggle="collapse" data-bs-target="#ami-temuan-{{ $loop->index }}"
                                                text="Lihat Temuan" />
                                            <div class="collapse" id="ami-temuan-{{ $loop->index }}">
                                                <x-tabler.card-body small bg-light>{{ $ami->ami_hasil_temuan }}</x-tabler.card-body>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ami->ami_hasil_temuan_sebab)
                                            <x-tabler.button type="button" class="btn-link btn-sm"
                                                data-bs-toggle="collapse" data-bs-target="#ami-sebab-{{ $loop->index }}"
                                                text="Lihat Sebab" />
                                            <div class="collapse" id="ami-sebab-{{ $loop->index }}">
                                                <x-tabler.card-body small bg-light">{{ $ami->ami_hasil_temuan_sebab }}</x-tabler.card-body>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ami->ami_hasil_temuan_akibat)
                                            <x-tabler.button type="button" class="btn-link btn-sm"
                                                data-bs-toggle="collapse" data-bs-target="#ami-akibat-{{ $loop->index }}"
                                                text="Lihat Akibat" />
                                            <div class="collapse" id="ami-akibat-{{ $loop->index }}">
                                                <x-tabler.card-body small bg-light">{{ $ami->ami_hasil_temuan_akibat }}</x-tabler.card-body>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ami->ami_hasil_temuan_rekom)
                                            <x-tabler.button type="button" class="btn-link btn-sm"
                                                data-bs-toggle="collapse" data-bs-target="#ami-rekom-{{ $loop->index }}"
                                                text="Lihat Rekom" />
                                            <div class="collapse" id="ami-rekom-{{ $loop->index }}">
                                                <x-tabler.card-body small bg-light">{{ $ami->ami_hasil_temuan_rekom }}</x-tabler.card-body>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($ami->updated_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-user-x text-muted fs-1"></i>
                        <p class="text-muted mt-2">Belum ada data AMI</p>
                    </div>
                @endif
            </x-tabler.card-body>
        </x-tabler.card>
    </div>

    {{-- Pengendalian Details --}}
    <div class="col-12">
        <x-tabler.card>
            <x-tabler.card-header title="Pengendalian" icon="ti ti-settings" />
            <x-tabler.card-body>
                @if($pengendDetails->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th>Status</th>
                                    <th>Target</th>
                                    <th>Analisis</th>
                                    <th>Penyesuaian</th>
                                    <th>Matriks</th>
                                    <th>Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengendDetails as $pengend)
                                @php
                                    $statusColor = match(strtolower($pengend->pengend_status)) {
                                        'selesai' => 'success',
                                        'proses' => 'warning',
                                        'belum' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $pengend->unit_name }}</strong>
                                        <br><small class="text-muted">{{ $pengend->unit_code }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusColor }}-lt fs-6">{{ $pengend->pengend_status ?? '-' }}</span>
                                    </td>
                                    <td>{{ $pengend->pengend_target ?? '-' }}</td>
                                    <td>
                                        @if($pengend->pengend_analisis)
                                            <x-tabler.button type="button" class="btn-link btn-sm"
                                                data-bs-toggle="collapse" data-bs-target="#pengend-analisis-{{ $loop->index }}"
                                                text="Lihat Analisis" />
                                            <div class="collapse" id="pengend-analisis-{{ $loop->index }}">
                                                <x-tabler.card-body small bg-light">{{ $pengend->pengend_analisis }}</x-tabler.card-body>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pengend->pengend_penyesuaian)
                                            <x-tabler.button type="button" class="btn-link btn-sm"
                                                data-bs-toggle="collapse" data-bs-target="#pengend-penyesuaian-{{ $loop->index }}"
                                                text="Lihat Penyesuaian" />
                                            <div class="collapse" id="pengend-penyesuaian-{{ $loop->index }}">
                                                <x-tabler.card-body small bg-light">{{ $pengend->pengend_penyesuaian }}</x-tabler.card-body>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pengend->pengend_important_matrix || $pengend->pengend_urgent_matrix)
                                            <div class="d-flex flex-column gap-1">
                                                @if($pengend->pengend_important_matrix)
                                                    <span class="badge bg-azure-lt">Important: {{ $pengend->pengend_important_matrix }}</span>
                                                @endif
                                                @if($pengend->pengend_urgent_matrix)
                                                    <span class="badge bg-orange-lt">Urgent: {{ $pengend->pengend_urgent_matrix }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($pengend->updated_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-settings-x text-muted fs-1"></i>
                        <p class="text-muted mt-2">Belum ada data Pengendalian</p>
                    </div>
                @endif
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
</div>
@endsection
