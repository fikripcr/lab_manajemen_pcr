@extends('layouts.tabler.app')

@section('title', 'Monitor Ujian: ' . $jadwal->nama_kegiatan)

@section('header')
<x-tabler.page-header title="{{ $jadwal->nama_kegiatan }}" pretitle="Monitor CBT">
    <x-slot:actions>
        <span class="badge bg-primary-lt px-3 py-2">
            <i class="ti ti-clock me-1"></i> {{ $jadwal->waktu_mulai->format('H:i') }} - {{ $jadwal->waktu_selesai->format('H:i') }}
        </span>
        <x-tabler.button type="button" class="btn-outline-secondary" onclick="location.reload()" icon="ti ti-refresh" text="Refresh" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            {{-- Summary Stats --}}
            <div class="col-12">
                <div class="row row-cards">
                    <div class="col-md-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-blue text-white avatar"><i class="ti ti-users"></i></span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">Total Peserta</div>
                                        <div class="text-muted">{{ $jadwal->riwayatSiswa->count() }} Orang</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-green text-white avatar"><i class="ti ti-player-play"></i></span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">Sedang Mengerjakan</div>
                                        <div class="text-muted">{{ $jadwal->riwayatSiswa->where('status', 'Sedang_Mengerjakan')->count() }} Orang</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-success text-white avatar"><i class="ti ti-check"></i></span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">Sudah Selesai</div>
                                        <div class="text-muted">{{ $jadwal->riwayatSiswa->where('status', 'Selesai')->count() }} Orang</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-red text-white avatar"><i class="ti ti-alert-triangle"></i></span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">Total Pelanggaran</div>
                                        <div class="text-muted">{{ \App\Models\Cbt\LogPelanggaran::whereIn('riwayat_id', $jadwal->riwayatSiswa->pluck('id'))->count() }} Kejadian</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Participant Table --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-table">
                        <x-tabler.datatable-client
                            id="table-peserta"
                            :columns="[
                                ['name' => 'No'],
                                ['name' => 'Nama Peserta'],
                                ['name' => 'Username'],
                                ['name' => 'Mulai Pada'],
                                ['name' => 'Progress'],
                                ['name' => 'Status'],
                                ['name' => 'Aksi', 'className' => 'w-1']
                            ]"
                        >
                            @forelse($jadwal->riwayatSiswa as $riwayat)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-sm rounded-circle me-2 bg-blue-lt">{{ substr($riwayat->user->name, 0, 1) }}</span>
                                            <div>{{ $riwayat->user->name }}</div>
                                        </div>
                                    </td>
                                    <td><div class="text-muted">{{ $riwayat->user->username }}</div></td>
                                    <td>{{ $riwayat->waktu_mulai->format('H:i:s') }}</td>
                                    <td>
                                        @php
                                            $totalSoal = $jadwal->paket->total_soal ?: $jadwal->paket->komposisi->count();
                                            $answered = $riwayat->jawaban->count();
                                            $percent = $totalSoal > 0 ? ($answered / $totalSoal) * 100 : 0;
                                        @endphp
                                        <div class="d-flex align-items-center gap-2" style="min-width: 150px">
                                            <div class="progress progress-sm flex-grow-1">
                                                <div class="progress-bar bg-primary" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $answered }}/{{ $totalSoal }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($riwayat->status == 'Sedang_Mengerjakan')
                                            <span class="status status-blue d-flex align-items-center gap-1">
                                                <span class="status-dot status-dot-animated"></span> Mengerjakan
                                            </span>
                                        @else
                                            <span class="status status-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <x-tabler.button class="btn-sm btn-icon" title="Reset Session" onclick="resetRiwayat('{{ $riwayat->encrypted_riwayat_ujian_id }}')" icon="ti ti-refresh text-warning" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- Handled by component --}}
                            @endforelse
                        </x-tabler.datatable-client>

                        @if($jadwal->riwayatSiswa->isEmpty())
                            <div class="text-center py-4 text-muted">Belum ada peserta yang memulai ujian.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function resetRiwayat(id) {
        if (confirm('Aksi ini akan menghapus progres peserta terpilih dan memungkinkan mereka mengulang ujian. Lanjutkan?')) {
            // Placeholder for actual reset logic if needed
            toastr.info('Fitur reset peserta sedang dalam pengembangan.');
        }
    }

    // Auto refresh every 20 seconds
    setInterval(() => {
        location.reload();
    }, 20000);
</script>
@endpush
