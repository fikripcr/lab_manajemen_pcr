{{-- ADMIN DASHBOARD PARTIAL --}}
@php
    // Statistics for admin dashboard
    $stats = [
        'total_pendaftar' => App\Models\Pmb\Pendaftaran::count(),
        'pendaftar_hari_ini' => App\Models\Pmb\Pendaftaran::whereDate('waktu_daftar', today())->count(),
        'menunggu_verifikasi' => App\Models\Pmb\Pendaftaran::whereIn('status_terkini', ['Menunggu_Verifikasi_Bayar', 'Menunggu_Verifikasi_Berkas'])->count(),
        'siap_ujian' => App\Models\Pmb\Pendaftaran::where('status_terkini', 'Siap_Ujian')->count(),
        'lulus' => App\Models\Pmb\Pendaftaran::where('status_terkini', 'Lulus')->count(),
        'tidak_lulus' => App\Models\Pmb\Pendaftaran::where('status_terkini', 'Tidak_Lulus')->count(),
    ];

    $recentPendaftar = App\Models\Pmb\Pendaftaran::with(['user', 'periode', 'jalur'])
        ->latest('waktu_daftar')
        ->limit(10)
        ->get();

    $statsByJalur = App\Models\Pmb\Pendaftaran::join('pmb_jalur', 'pmb_pendaftaran.jalur_id', '=', 'pmb_jalur.id')
        ->selectRaw('pmb_jalur.nama_jalur, COUNT(*) as total')
        ->groupBy('pmb_jalur.nama_jalur')
        ->get();
@endphp

{{-- KPI Cards --}}
<div class="row row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Pendaftar</div>
                    <div class="ms-auto lh-1">
                        <div class="dropdown">
                            <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">All Time</a>
                        </div>
                    </div>
                </div>
                <div class="h1 mb-3">{{ $stats['total_pendaftar'] }}</div>
                <div class="d-flex mb-2">
                    <div>Hari ini</div>
                    <div class="ms-auto">
                        <span class="text-green">
                            <i class="ti ti-trending-up"></i> +{{ $stats['pendaftar_hari_ini'] }}
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-primary" style="width: 85%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Menunggu Verifikasi</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-yellow text-white">{{ $stats['menunggu_verifikasi'] }}</span>
                    </div>
                </div>
                <div class="h1 mb-3">{{ $stats['menunggu_verifikasi'] }}</div>
                <div class="d-flex mb-2">
                    <div>Perlu proses</div>
                    <div class="ms-auto">
                        <span class="text-yellow">
                            <i class="ti ti-clock"></i> Pending
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-yellow" style="width: {{ $stats['total_pendaftar'] > 0 ? ($stats['menunggu_verifikasi'] / $stats['total_pendaftar']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Siap Ujian</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-blue text-white">{{ $stats['siap_ujian'] }}</span>
                    </div>
                </div>
                <div class="h1 mb-3">{{ $stats['siap_ujian'] }}</div>
                <div class="d-flex mb-2">
                    <div>Siap mengikuti ujian</div>
                    <div class="ms-auto">
                        <span class="text-blue">
                            <i class="ti ti-clock-hour-4"></i> Ready
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-blue" style="width: {{ $stats['total_pendaftar'] > 0 ? ($stats['siap_ujian'] / $stats['total_pendaftar']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Kelulusan</div>
                    <div class="ms-auto lh-1">
                        <div class="dropdown">
                            <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">This Period</a>
                        </div>
                    </div>
                </div>
                <div class="h1 mb-3">
                    {{ $stats['lulus'] }} / {{ $stats['lulus'] + $stats['tidak_lulus'] }}
                </div>
                <div class="d-flex mb-2">
                    <div>Tingkat kelulusan</div>
                    <div class="ms-auto">
                        <span class="text-green">
                            @if($stats['lulus'] + $stats['tidak_lulus'] > 0)
                                {{ round(($stats['lulus'] / ($stats['lulus'] + $stats['tidak_lulus'])) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-green" style="width: {{ $stats['lulus'] + $stats['tidak_lulus'] > 0 ? ($stats['lulus'] / ($stats['lulus'] + $stats['tidak_lulus'])) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards mb-4">
    {{-- Recent Registrations --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pendaftaran Terbaru</h3>
                <div class="card-actions">
                    <a href="{{ route('pmb.pendaftaran.index') }}" class="btn-action">Lihat Semua</a>
                </div>
            </div>
            <div class="card-table table-vcenter">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th>No. Pendaftaran</th>
                            <th>Nama</th>
                            <th>Jalur</th>
                            <th>Status</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPendaftar as $pendaftar)
                            <tr>
                                <td>
                                    <span class="badge bg-blue-lt">{{ $pendaftar->no_pendaftaran }}</span>
                                </td>
                                <td>{{ $pendaftar->user->name }}</td>
                                <td>{{ $pendaftar->jalur->nama_jalur }}</td>
                                <td>
                                    <span class="badge {{ getStatusBadgeClass($pendaftar->status_terkini) }} text-white">
                                        {{ str_replace('_', ' ', $pendaftar->status_terkini) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('pmb.pendaftaran.show', $pendaftar->hashid) }}" class="btn btn-icon btn-sm">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Statistics by Jalur --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik per Jalur</h3>
                <div class="card-actions">
                    <a href="{{ route('pmb.jalur.index') }}" class="btn-action">Kelola</a>
                </div>
            </div>
            <div class="card-body">
                @if($statsByJalur->count() > 0)
                    <div class="datagrid">
                        @foreach($statsByJalur as $stat)
                            <div class="datagrid-item">
                                <div class="datagrid-title">{{ $stat->nama_jalur }}</div>
                                <div class="datagrid-content">
                                    <span class="badge bg-primary text-white">{{ $stat->total }} pendaftar</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty">
                        <div class="empty-img"><img src="{{ asset('images/illustrations/undraw_empty_xct9.svg') }}" height="128" alt=""></div>
                        <p class="empty-title">Belum ada data</p>
                        <p class="empty-subtitle text-muted">Belum ada pendaftar pada periode ini.</p>
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
                        <a href="{{ route('pmb.pendaftaran.index') }}" class="btn btn-primary w-100">
                            <i class="ti ti-users me-2"></i>
                            Kelola Pendaftaran
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('pmb.pendaftaran.index') }}?filter=menunggu_verifikasi" class="btn btn-warning w-100">
                            <i class="ti ti-clock me-2"></i>
                            Verifikasi Berkas ({{ $stats['menunggu_verifikasi'] }})
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('pmb.periode.index') }}" class="btn btn-secondary w-100">
                            <i class="ti ti-calendar me-2"></i>
                            Kelola Periode
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('pmb.jalur.index') }}" class="btn btn-info w-100">
                            <i class="ti ti-category me-2"></i>
                            Kelola Jalur
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    function getStatusBadgeClass($status) {
        $classes = [
            'Draft' => 'bg-secondary',
            'Menunggu_Verifikasi_Bayar' => 'bg-warning',
            'Menunggu_Verifikasi_Berkas' => 'bg-warning',
            'Revisi_Berkas' => 'bg-orange',
            'Siap_Ujian' => 'bg-info',
            'Selesai_Ujian' => 'bg-blue',
            'Lulus' => 'bg-success',
            'Tidak_Lulus' => 'bg-danger',
            'Daftar_Ulang' => 'bg-purple'
        ];
        
        return ($classes[$status] ?? 'bg-primary') . ' text-white';
    }
@endphp
