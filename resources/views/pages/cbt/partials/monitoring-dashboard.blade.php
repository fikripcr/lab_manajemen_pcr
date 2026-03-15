<div class="row row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <x-tabler.card>
            <x-tabler.card-body>
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
            </x-tabler.card-body>
        </x-tabler.card>
    </div>

    <div class="col-sm-6 col-lg-3">
        <x-tabler.card>
            <x-tabler.card-body>
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
            </x-tabler.card-body>
        </x-tabler.card>
    </div>

    <div class="col-sm-6 col-lg-3">
        <x-tabler.card>
            <x-tabler.card-body>
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
            </x-tabler.card-body>
        </x-tabler.card>
    </div>

    <div class="col-sm-6 col-lg-3">
        <x-tabler.card>
            <x-tabler.card-body>
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
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
</div>

<div class="row row-deck row-cards mb-4">
    {{-- Active Exams --}}
    <div class="col-md-8">
        <x-tabler.card>
            <x-tabler.card-header title="Ujian Sedang Berlangsung">
                <x-slot:actions>
                    <a href="{{ route('cbt.execution.index') }}" class="btn-action" title="View All Exams">
                        <i class="ti ti-arrow-right"></i>
                    </a>
                </x-slot:actions>
            </x-tabler.card-header>
            <x-tabler.card-body class="p-0">
                @if($activeExams->isEmpty())
                    <x-tabler.empty-state title="Tidak ada ujian aktif" text="Tidak ada ujian yang sedang berlangsung saat ini." />
                @else
                    <x-tabler.datatable-client
                        id="table-active-exams"
                        :columns="[
                            ['name' => 'Nama Ujian'],
                            ['name' => 'Paket'],
                            ['name' => 'Peserta'],
                            ['name' => 'Waktu'],
                            ['name' => 'Status'],
                            ['name' => 'Aksi', 'sortable' => false]
                        ]"
                    >
                        @foreach($activeExams as $exam)
                            <tr>
                                <td>
                                    <div>
                                        <div class="font-weight-bold">{{ $exam->nama_kegiatan }}</div>
                                        @if($exam->is_token_aktif)
                                            <span class="badge bg-success-lt mt-1">Token Aktif</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $exam->paket->nama_paket }}</td>
                                <td>
                                    <span class="badge bg-blue-lt">{{ $exam->riwayatSiswa->count() }} peserta</span>
                                </td>
                                <td>
                                    <div class="text-muted small">
                                        {{ $exam->waktu_mulai->format('H:i') }} - {{ $exam->waktu_selesai->format('H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-green text-green-fg">Berlangsung</span>
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('cbt.execute.test-exam', $exam->hashid) }}" class="btn btn-icon btn-sm btn-primary" title="Tes Ujian">
                                            <i class="ti ti-player-play"></i>
                                        </a>
                                        <x-tabler.button type="button" class="btn-icon btn-sm btn-info" onclick="monitorExam('{{ $exam->hashid }}')" title="Monitor" iconOnly="true" icon="ti ti-eye" />
                                        <x-tabler.button type="button" class="btn-icon btn-sm btn-secondary" onclick="toggleToken('{{ $exam->hashid }}')" title="Toggle Token" iconOnly="true" icon="ti ti-key" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-tabler.datatable-client>
                @endif
            </x-tabler.card-body>
        </x-tabler.card>
    </div>

    {{-- Recent Violations --}}
    <div class="col-md-4">
        <x-tabler.card>
            <x-tabler.card-header title="Pelanggaran Terbaru">
                <x-slot:actions>
                    <span class="badge bg-red text-white">{{ $recentViolations->count() }}</span>
                </x-slot:actions>
            </x-tabler.card-header>
            <x-tabler.card-body>
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
                    <x-tabler.empty-state title="Aman" text="Tidak ada pelanggaran yang terdeteksi." />
                @endif
            </x-tabler.card-body>
        </x-tabler.card>
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
