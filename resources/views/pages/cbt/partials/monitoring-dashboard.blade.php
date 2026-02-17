{{-- MONITORING DASHBOARD FOR ADMIN --}}
@php
    // Statistics for monitoring
    $stats = [
        'active_exams' => App\Models\Cbt\JadwalUjian::where('waktu_mulai', '<=', now())
            ->where('waktu_selesai', '>=', now())
            ->count(),
        'total_exams_today' => App\Models\Cbt\JadwalUjian::whereDate('waktu_mulai', today())->count(),
        'students_taking_exam' => App\Models\Cbt\RiwayatUjianSiswa::where('status', 'Sedang_Mengerjakan')->count(),
        'completed_exams_today' => App\Models\Cbt\RiwayatUjianSiswa::whereDate('waktu_selesai', today())->count(),
    ];

    $activeExams = App\Models\Cbt\JadwalUjian::with(['paket', 'riwayatSiswa.user'])
        ->where('waktu_mulai', '<=', now())
        ->where('waktu_selesai', '>=', now())
        ->get();

    $recentViolations = App\Models\Cbt\LogPelanggaran::with(['riwayatUjianSiswa.user'])
        ->latest('waktu_kejadian')
        ->limit(10)
        ->get();
@endphp

{{-- Monitoring Stats --}}
<div class="row row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Ujian Aktif</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-green text-green-fg">{{ $stats['active_exams'] }}</span>
                    </div>
                </div>
                <div class="h1 mb-3">{{ $stats['active_exams'] }}</div>
                <div class="d-flex mb-2">
                    <div>Sedang berlangsung</div>
                    <div class="ms-auto">
                        <span class="text-green">
                            <i class="ti ti-activity"></i> Live
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-green" style="width: 75%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Peserta Ujian</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-blue text-blue-fg">{{ $stats['students_taking_exam'] }}</span>
                    </div>
                </div>
                <div class="h1 mb-3">{{ $stats['students_taking_exam'] }}</div>
                <div class="d-flex mb-2">
                    <div>Sedang mengerjakan</div>
                    <div class="ms-auto">
                        <span class="text-blue">
                            <i class="ti ti-users"></i> Active
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-blue" style="width: {{ $stats['active_exams'] > 0 ? ($stats['students_taking_exam'] / $stats['active_exams']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Ujian Hari Ini</div>
                    <div class="ms-auto lh-1">
                        <div class="dropdown">
                            <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Today</a>
                        </div>
                    </div>
                </div>
                <div class="h1 mb-3">{{ $stats['total_exams_today'] }}</div>
                <div class="d-flex mb-2">
                    <div>Dijadwalkan hari ini</div>
                    <div class="ms-auto">
                        <span class="text-primary">
                            <i class="ti ti-calendar"></i> Scheduled
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-primary" style="width: 60%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Selesai Hari Ini</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-success text-success-fg">{{ $stats['completed_exams_today'] }}</span>
                    </div>
                </div>
                <div class="h1 mb-3">{{ $stats['completed_exams_today'] }}</div>
                <div class="d-flex mb-2">
                    <div>Telah selesai</div>
                    <div class="ms-auto">
                        <span class="text-success">
                            <i class="ti ti-check"></i> Completed
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-success" style="width: {{ $stats['total_exams_today'] > 0 ? ($stats['completed_exams_today'] / $stats['total_exams_today']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards mb-4">
    {{-- Active Exams --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ujian Sedang Berlangsung</h3>
                <div class="card-actions">
                    <a href="{{ route('cbt.jadwal.index') }}" class="btn-action">Kelola Semua</a>
                </div>
            </div>
            <div class="card-table table-vcenter">
                @if($activeExams->count() > 0)
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>Nama Ujian</th>
                                <th>Paket</th>
                                <th>Peserta</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeExams as $exam)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $exam->nama_kegiatan }}</strong>
                                            @if($exam->is_token_aktif)
                                                <span class="badge bg-success text-white ms-2">Token Aktif</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $exam->paket->nama_paket }}</td>
                                    <td>
                                        <span class="badge bg-blue text-white">{{ $exam->riwayatSiswa->count() }} peserta</span>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $exam->waktu_mulai->format('H:i') }} - {{ $exam->waktu_selesai->format('H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-green text-white">Berlangsung</span>
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <button type="button" class="btn btn-icon btn-sm" onclick="monitorExam({{ $exam->id }})">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-icon btn-sm" onclick="toggleToken({{ $exam->id }})">
                                                <i class="ti ti-key"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty">
                        <div class="empty-img"><img src="{{ asset('images/illustrations/undraw_empty_xct9.svg') }}" height="128" alt=""></div>
                        <p class="empty-title">Tidak ada ujian aktif</p>
                        <p class="empty-subtitle text-muted">Tidak ada ujian yang sedang berlangsung saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent Violations --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pelanggaran Terbaru</h3>
                <div class="card-actions">
                    <span class="badge bg-red text-white">{{ $recentViolations->count() }}</span>
                </div>
            </div>
            <div class="card-body">
                @if($recentViolations->count() > 0)
                    <div class="timeline">
                        @foreach($recentViolations as $violation)
                            <div class="timeline-item">
                                <div class="timeline-point timeline-point-danger"></div>
                                <div class="timeline-content">
                                    <div class="timeline-time">{{ $violation->waktu_kejadian->diffForHumans() }}</div>
                                    <div class="timeline-title">{{ $violation->jenis_pelanggaran }}</div>
                                    <div class="timeline-body text-muted">
                                        {{ $violation->riwayatUjianSiswa->user->name }}
                                        @if($violation->keterangan)
                                            - {{ $violation->keterangan }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty">
                        <div class="empty-img"><img src="{{ asset('images/illustrations/undraw_security_o890.svg') }}" height="128" alt=""></div>
                        <p class="empty-title">Aman</p>
                        <p class="empty-subtitle text-muted">Tidak ada pelanggaran yang terdeteksi.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aksi Cepat</h3>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('cbt.jadwal.create') }}" class="btn btn-primary w-100">
                            <i class="ti ti-plus me-2"></i>
                            Buat Jadwal Ujian
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('cbt.paket.create') }}" class="btn btn-secondary w-100">
                            <i class="ti ti-package me-2"></i>
                            Buat Paket Soal
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('cbt.soal.create') }}" class="btn btn-info w-100">
                            <i class="ti ti-file-text me-2"></i>
                            Tambah Soal
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <button type="button" class="btn btn-warning w-100" onclick="showViolationReport()">
                            <i class="ti ti-alert-triangle me-2"></i>
                            Lihat Laporan Pelanggaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Monitoring functions
function monitorExam(examId) {
    // Note: Assuming route 'cbt.monitor.show' exists, otherwise fallback to constructed URL handled by controller
    // Using string interpolation for JS, but safer to use route() if possible. 
    // Since examId is dynamic JS variable, we keep the structure but ensure route prefix is correct.
    const url = "{{ url('cbt/monitor') }}/" + examId;
    window.open(url, '_blank', 'width=1200,height=800');
}

function toggleToken(examId) {
    if (confirm('Apakah Anda yakin ingin mengubah status token?')) {
        const url = "{{ url('cbt/api/toggle-token') }}/" + examId;
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                location.reload();
            } else {
                alert('Gagal mengubah status token');
            }
        });
    }
}

function showViolationReport() {
    window.location.href = "{{ route('cbt.laporan.pelanggaran') }}"; 
}

// Auto-refresh monitoring data
setInterval(() => {
    location.reload();
}, 30000); // Refresh every 30 seconds
</script>
@endpush
