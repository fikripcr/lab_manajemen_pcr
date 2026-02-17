@extends('layouts.admin.app')

@section('title', 'Lab Dashboard')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Overview
                </div>
                <h2 class="page-title">
                    Dashboard Lab
                </h2>
            </div>
        </div>
    </div>

    <div class="page-body">
        
        <!-- Stats Widgets -->
        <div class="row row-cards mb-4">
            <div class="col-sm-6 col-lg-2">
                <div class="card card-sm shadow-sm border-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar shadow-sm"><i class="ti ti-devices fs-2"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-bold h3 mb-0">
                                    {{ $stats['pc_assignment'] }}
                                </div>
                                <div class="text-muted small">
                                    PC Assignments
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-2">
                <div class="card card-sm shadow-sm border-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-danger text-white avatar shadow-sm"><i class="ti ti-tool fs-2"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-bold h3 mb-0">
                                    {{ $stats['laporan_kerusakan_open'] }}
                                </div>
                                <div class="text-muted small text-truncate">
                                    Kerusakan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm shadow-sm border-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar shadow-sm"><i class="ti ti-download fs-2"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-bold h3 mb-0">
                                    {{ $stats['software_pending'] }}
                                </div>
                                <div class="text-muted small">
                                    Software Requests
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-2">
                <div class="card card-sm shadow-sm border-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-info text-white avatar shadow-sm"><i class="ti ti-calendar-event fs-2"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-bold h3 mb-0">
                                    {{ $stats['kegiatan_today'] }}
                                </div>
                                <div class="text-muted small">
                                    Kegiatan Lab
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm shadow-sm border-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-green text-white avatar shadow-sm"><i class="ti ti-archive fs-2"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-bold h3 mb-0">
                                    {{ $stats['total_inventaris'] }}
                                </div>
                                <div class="text-muted small">
                                    Total Inventaris
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-items-center mb-2">
            <div class="col">
                <h4 class="fw-bold mb-0"><i class="ti ti-chart-bar me-2 text-primary"></i> Statistik Inventaris & Tim Per Lab</h4>
            </div>
        </div>

        <div class="row g-2 mb-3">
            @foreach($labs as $lab)
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-3 d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span class="avatar avatar-sm bg-blue-lt shadow-sm me-2"><i class="ti ti-building"></i></span>
                            <div class="text-truncate">
                                <h4 class="card-title mb-0 fw-bold text-truncate">{{ $lab->name }}</h4>
                                <div class="text-muted small text-truncate">
                                    <i class="ti ti-map-pin me-1"></i>{{ $lab->location ?? 'No Location' }}
                                </div>
                            </div>
                        </div>

                        <div class="row g-1 mb-2">
                            <div class="col-6">
                                <div class="border rounded p-1 text-center bg-light">
                                    <div class="text-muted" style="font-size: 0.7rem;">Inventaris</div>
                                    <div class="h4 mb-0 fw-bold text-primary">{{ $lab->lab_inventaris_count }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-1 text-center bg-light">
                                    <div class="text-muted" style="font-size: 0.7rem;">Tim</div>
                                    <div class="h4 mb-0 fw-bold text-success">{{ $lab->lab_teams_count }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto pt-2 border-top text-center">
                            <a href="{{ route('lab.labs.show', $lab->lab_id) }}" class="small fw-bold text-primary text-decoration-none">
                                <i class="ti ti-eye me-1"></i>Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mb-3">
            {{ $labs->links() }}
        </div>

        <div class="row row-cards">
            <!-- Latest Laporan Kerusakan -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Laporan Kerusakan Terbaru</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Alat</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latest_laporan as $laporan)
                                <tr>
                                    <td class="w-75">
                                        <div class="font-weight-medium text-truncate" style="max-width: 400px;">
                                            {{ $laporan->inventaris->nama_alat ?? '-' }}
                                        </div>
                                        <div class="text-muted small text-truncate" style="max-width: 400px;">{{ $laporan->deskripsi_kerusakan }}</div>
                                    </td>
                                    <td class="text-nowrap">
                                        @php
                                            $badges = ['open' => 'danger', 'in_progress' => 'warning', 'resolved' => 'success', 'closed' => 'secondary', 'pending' => 'danger'];
                                            $color = $badges[$laporan->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}-lt px-2 py-1">{{ ucfirst($laporan->status) }}</span>
                                    </td>
                                    <td class="text-nowrap text-muted">
                                        {{ $laporan->created_at->diffForHumans() }}
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('lab.laporan-kerusakan.show', encryptId($laporan->laporan_kerusakan_id)) }}" class="btn btn-icon btn-sm btn-ghost-secondary">
                                            <i class="bx bx-show fs-3"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada laporan terbaru</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Upcoming Activities -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Kegiatan Mendatang</h3>
                    </div>
                    <div class="list-group list-group-flush list-group-hoverable">
                        @forelse($latest_kegiatan as $kegiatan)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-md bg-blue-lt fw-bold">{{ substr($kegiatan->lab->name ?? 'L', 0, 1) }}</span>
                                </div>
                                <div class="col">
                                    <a href="{{ route('lab.kegiatan.show', encryptId($kegiatan->kegiatan_id)) }}" class="text-reset d-block font-weight-medium text-truncate" style="max-width: 300px;">
                                        {{ $kegiatan->nama_kegiatan }}
                                    </a>
                                    <div class="d-flex align-items-center text-muted small mt-1">
                                        <span class="text-primary font-weight-bold me-2">{{ $kegiatan->lab->name ?? 'Laboratorium' }}</span>
                                        <span class="text-muted">| {{ $kegiatan->tanggal->format('d M Y') }} | {{ $kegiatan->jam_mulai->format('H:i') }}</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-{{ $kegiatan->status == 'approved' ? 'success' : 'warning' }}-lt px-2 py-1">
                                        {{ ucfirst($kegiatan->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item text-center text-muted py-3">
                            Tidak ada kegiatan mendatang
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
