@extends('layouts.admin.app')

@section('title', 'Detail Laporan Kerusakan')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Detail Laporan #{{ $laporan->laporan_kerusakan_id }}
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.laporan-kerusakan.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="row row-cards">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Kerusakan</h3>
                        <div class="card-actions">
                            @php
                                $badges = [
                                    'open' => 'danger',
                                    'in_progress' => 'warning',
                                    'resolved' => 'success',
                                    'closed' => 'secondary'
                                ];
                                $color = $badges[$laporan->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ $laporan->status }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Lab</label>
                                <p class="fw-bold">{{ $laporan->inventaris && $laporan->inventaris->lab ? $laporan->inventaris->lab->name : '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Inventaris / Alat</label>
                                <p class="fw-bold">{{ $laporan->inventaris ? $laporan->inventaris->nama_alat : '-' }}</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Deskripsi Kerusakan</label>
                            <div class="form-control-plaintext border p-2 rounded bg-light">
                                {{ $laporan->deskripsi_kerusakan }}
                            </div>
                        </div>

                        @if($laporan->foto_sebelum)
                        <div class="mb-3">
                            <label class="form-label text-muted">Bukti Foto</label>
                            <div>
                                <a href="{{ asset('storage/' . $laporan->foto_sebelum) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $laporan->foto_sebelum) }}" class="img-fluid rounded" style="max-height: 300px" alt="Bukti Foto">
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col">
                                <small class="text-muted">Dilaporkan oleh: {{ $laporan->pelapor ? $laporan->pelapor->name : 'Unknown' }}</small>
                            </div>
                            <div class="col-auto">
                                <small class="text-muted">{{ $laporan->created_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tindak Lanjut Teknisi</h3>
                    </div>
                    <div class="card-body">
                        @if($laporan->teknisi_id)
                            <div class="mb-3">
                                <label class="form-label text-muted">Teknisi</label>
                                <div>{{ $laporan->teknisi->name }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Catatan Perbaikan</label>
                                <p>{{ $laporan->catatan_perbaikan ?: '-' }}</p>
                            </div>
                        @else
                            <div class="empty">
                                <div class="empty-icon"><i class="bx bx-time"></i></div>
                                <p class="empty-title">Belum ada tindakan</p>
                                <p class="empty-subtitle text-muted">
                                    Laporan ini belum diproses oleh teknisi lab.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
