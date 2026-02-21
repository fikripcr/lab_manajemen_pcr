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

    $statsByJalur = App\Models\Pmb\Pendaftaran::join('pmb_jalur', 'pmb_pendaftaran.jalur_id', '=', 'pmb_jalur.jalur_id')
        ->selectRaw('pmb_jalur.nama_jalur, COUNT(*) as total')
        ->groupBy('pmb_jalur.nama_jalur')
        ->get();
@endphp

{{-- KPI Cards --}}
<div class="row row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('pmb.pendaftaran.index') }}" class="card card-link">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-uppercase">Total Pendaftar</div>
                </div>
                <div class="h1 mb-3">{{ $stats['total_pendaftar'] }}</div>
                <div class="d-flex mb-2">
                    <div class="text-secondary">Pendaftaran baru hari ini</div>
                    <div class="ms-auto">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                            +{{ $stats['pendaftar_hari_ini'] }} <i class="ti ti-trending-up ms-1"></i>
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('pmb.pendaftaran.index') }}?status=Menunggu_Verifikasi_Berkas" class="card card-link">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-uppercase">Menunggu Verifikasi</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-yellow text-white">{{ $stats['menunggu_verifikasi'] }}</span>
                    </div>
                </div>
                <div class="h1 mb-3">{{ $stats['menunggu_verifikasi'] }}</div>
                <div class="d-flex mb-2">
                    <div class="text-secondary">Perlu verifikasi segera</div>
                    <div class="ms-auto">
                        <span class="text-yellow d-inline-flex align-items-center lh-1">
                            <i class="ti ti-clock ms-1"></i>
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-yellow" style="width: {{ $stats['total_pendaftar'] > 0 ? ($stats['menunggu_verifikasi'] / $stats['total_pendaftar']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('pmb.pendaftaran.index') }}?status=Siap_Ujian" class="card card-link">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-uppercase">Siap Ujian</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-blue text-white">{{ $stats['siap_ujian'] }}</span>
                    </div>
                </div>
                <div class="h1 mb-3">{{ $stats['siap_ujian'] }}</div>
                <div class="d-flex mb-2">
                    <div class="text-secondary">Peserta siap ujian</div>
                    <div class="ms-auto">
                        <span class="text-blue d-inline-flex align-items-center lh-1">
                            <i class="ti ti-school ms-1"></i>
                        </span>
                    </div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-blue" style="width: {{ $stats['total_pendaftar'] > 0 ? ($stats['siap_ujian'] / $stats['total_pendaftar']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('pmb.pendaftaran.index') }}?status=Lulus" class="card card-link">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-uppercase">Lulus Seleksi</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-green text-white">{{ $stats['lulus'] }}</span>
                    </div>
                </div>
                <div class="h1 mb-3">{{ $stats['lulus'] }}</div>
                <div class="d-flex mb-2">
                    <div class="text-secondary">Tingkat kelulusan</div>
                    <div class="ms-auto">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                            @if($stats['lulus'] + $stats['tidak_lulus'] > 0)
                                {{ round(($stats['lulus'] / ($stats['lulus'] + $stats['tidak_lulus'])) * 100, 1) }}%
                            @else
                                0%
                            @endif
                            <i class="ti ti-trophy ms-1"></i>
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
            <div class="card-table">
                <x-tabler.datatable-client
                    id="table-recent-registrations"
                    :columns="[
                        ['name' => 'No. Pendaftaran'],
                        ['name' => 'Nama'],
                        ['name' => 'Jalur'],
                        ['name' => 'Status'],
                        ['name' => '', 'className' => 'w-1']
                    ]"
                >
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
                                    <a href="{{ route('pmb.pendaftaran.show', $pendaftar->encrypted_pendaftaran_id) }}" class="btn btn-icon btn-sm">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-tabler.datatable-client>
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
