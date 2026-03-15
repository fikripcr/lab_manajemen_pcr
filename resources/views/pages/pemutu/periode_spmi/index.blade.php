@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $pageTitle }}" pretitle="Manajemen">
    <x-slot:actions>
        <div class="d-flex align-items-center gap-3">
            <div>
                <x-tabler.form-select name="year" id="year-filter">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                            Tahun {{ $year }}
                        </option>
                    @endforeach
                </x-tabler.form-select>
            </div>
            <x-tabler.button type="create" class="btn-primary" :modalUrl="route('pemutu.periode-spmi.create')" modalTitle="Tambah Periode" />
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row">
    @forelse($periodes as $periode)
        <div class="col-md-6">
            <x-tabler.card>
                <x-tabler.card-header title="Periode {{ $periode->periode }}">
                    <span class="ms-3 status status-{{$periode->jenis_periode == 'Akademik' ? 'primary' : 'secondary'}}"> {{ $periode->jenis_periode }}</span>
                    <x-slot:actions>
                        <x-tabler.dropdown>
                            <x-tabler.dropdown-item  type="edit" url="{{ route('pemutu.periode-spmi.edit', $periode->encrypted_periodespmi_id) }}" />
                            <x-tabler.dropdown-divider />
                            <x-tabler.dropdown-item type="delete" url="{{ route('pemutu.periode-spmi.destroy', $periode->encrypted_periodespmi_id) }}" />
                        </x-tabler.dropdown>
                    </x-slot:actions>
                </x-tabler.card-header>

                <x-tabler.card-body>
                    <ul class="timeline">
                        {{-- 1. PENETAPAN --}}
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-primary-lt">
                                <i class="ti ti-gavel"></i>
                            </div>
                            <div class="timeline-event-card shadow-none border">
                                <x-tabler.card-body class="p-2">
                                    <div class="fw-bold">Penetapan</div>
                                    <div class="text-muted small">
                                        {{ formatTanggalIndo($periode->penetapan_awal) }} 
                                        s/d 
                                        {{ formatTanggalIndo($periode->penetapan_akhir) }}
                                    </div>
                                </x-tabler.card-body>
                            </div>
                        </li>

                        {{-- 2. PELAKSANAAN --}}
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-teal-lt">
                                <i class="ti ti-player-play"></i>
                            </div>
                            <div class="timeline-event-card shadow-none border">
                                <x-tabler.card-body class="p-2">
                                    <div class="fw-bold">Pelaksanaan</div>
                                    <div class="text-muted small">
                                        Sepanjang Periode {{ $periode->periode }}
                                    </div>
                                </x-tabler.card-body>
                            </div>
                        </li>

                        {{-- 3. EVALUASI --}}
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-warning-lt">
                                <i class="ti ti-clipboard-check"></i>
                            </div>
                            <div class="timeline-event-card shadow-none border">
                                <x-tabler.card-body class="p-2">
                                    <div class="fw-bold">Evaluasi (ED & AMI)</div>
                                    <div class="text-muted small">
                                        @if($periode->ed_awal)
                                            <div><span class="text-warning">ED:</span> {{ formatTanggalIndo($periode->ed_awal) }} - {{ formatTanggalIndo($periode->ed_akhir) }}</div>
                                        @endif
                                        @if($periode->ami_awal)
                                            <div><span class="text-warning">AMI:</span> {{ formatTanggalIndo($periode->ami_awal) }} - {{ formatTanggalIndo($periode->ami_akhir) }}</div>
                                        @endif
                                    </div>
                                </x-tabler.card-body>
                            </div>
                        </li>

                        {{-- 4. PENGENDALIAN --}}
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-danger-lt">
                                <i class="ti ti-settings-exclamation"></i>
                            </div>
                            <div class="timeline-event-card shadow-none border">
                                <x-tabler.card-body class="p-2">
                                    <div class="fw-bold">Pengendalian</div>
                                    <div class="text-muted small">
                                        {{ formatTanggalIndo($periode->pengendalian_awal) }}
                                        s/d
                                        {{ formatTanggalIndo($periode->pengendalian_akhir) }}
                                    </div>
                                </x-tabler.card-body>
                            </div>
                        </li>

                        {{-- 5. PENINGKATAN --}}
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-success-lt">
                                <i class="ti ti-trending-up"></i>
                            </div>
                            <div class="timeline-event-card shadow-none border">
                                <x-tabler.card-body class="p-2">
                                    <div class="fw-bold">Peningkatan</div>
                                    <div class="text-muted small">
                                        {{ formatTanggalIndo($periode->peningkatan_awal) }}
                                        s/d
                                        {{ formatTanggalIndo($periode->peningkatan_akhir) }}
                                    </div>
                                </x-tabler.card-body>
                            </div>
                        </li>
                    </ul>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
    @empty
        <x-tabler.empty-state
            title="Belum ada Periode"
            text="Silakan tambahkan periode baru untuk memulai siklus SPMI."
            icon="ti ti-calendar-time"
        />
    @endforelse
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('year-filter').addEventListener('change', function() {
        const year = this.value;
        const url = new URL(window.location.href);
        url.searchParams.set('year', year);
        window.location.href = url.toString();
    });
</script>
@endpush
