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
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar"><i class="bx bx-desktop fs-2"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $stats['pc_assignment'] }} Active
                                </div>
                                <div class="text-muted">
                                    PC Assignments
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-danger text-white avatar"><i class="bx bx-wrench fs-2"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $stats['laporan_kerusakan_open'] }} Open
                                </div>
                                <div class="text-muted">
                                    Laporan Kerusakan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar"><i class="bx bx-download fs-2"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $stats['software_pending'] }} Pending
                                </div>
                                <div class="text-muted">
                                    Software Requests
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-info text-white avatar"><i class="bx bx-calendar-event fs-2"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    {{ $stats['kegiatan_today'] }} Today
                                </div>
                                <div class="text-muted">
                                    Kegiatan Lab
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
