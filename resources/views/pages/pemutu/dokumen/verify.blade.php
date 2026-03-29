@extends('layouts.guest.app')
@section('title', 'Verifikasi Dokumen SPMI')

@section('content')
    <div class="empty border rounded shadow-sm bg-white p-4 p-md-5">
                <div class="empty-icon text-{{ $color }}">
                    <i class="{{ $icon }}" style="font-size: 4rem;"></i>
                </div>
                <p class="empty-title h2 text-{{ $color }} mb-1">{{ $status }}</p>
                <div class="empty-subtitle text-muted mb-4">
                    {{ $desc }}
                </div>
                
                <div class="card card-stacked mb-4 text-start">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-muted small text-uppercase mb-1">Kode Dokumen</label>
                                <div class="font-weight-bold fs-3">{{ $dokumen->kode ?? '-' }}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small text-uppercase mb-1">Judul Dokumen</label>
                                <div class="font-weight-bold text-dark">{{ $dokumen->judul }}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small text-uppercase mb-1">Status Keaktifan</label>
                                <div><span class="badge bg-blue-lt">Master Dokumen Berlaku</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($approvals->count() > 0)
                <div class="text-start mb-2">
                    <h3 class="card-title text-muted text-uppercase small bg-light p-2 rounded mb-3">Daftar Pengesahan</h3>
                    <div class="list-group list-group-flush list-group-hoverable border-0">
                        @foreach($approvals as $approval)
                        <div class="list-group-item px-0 py-2 border-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm rounded bg-{{ $approval->status == 'Approved' ? 'green' : ($approval->status == 'Rejected' ? 'red' : 'orange') }}-lt">
                                        {{ substr($approval->pejabat ?? 'A', 0, 1) }}
                                    </span>
                                </div>
                                <div class="col text-truncate">
                                    <div class="text-body d-block text-truncate fw-bold">{{ $approval->pejabat }}</div>
                                    <div class="text-muted text-truncate mt-n1" style="font-size: 0.75rem;">{{ $approval->jabatan }}</div>
                                </div>
                                <div class="col-auto text-end">
                                    @if($approval->status == 'Approved')
                                        <span class="badge bg-green-lt"><i class="ti ti-check me-1"></i> Disetujui</span>
                                        <div class="text-success small mt-1">{{ $approval->updated_at->format('d/m/Y H:i') }}</div>
                                    @elseif($approval->status == 'Rejected')
                                        <span class="badge bg-red-lt"><i class="ti ti-x me-1"></i> Ditolak</span>
                                    @else
                                        <span class="badge bg-orange-lt">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <div class="empty-action mt-4">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-pill">
                        <i class="ti ti-home me-2"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
            
    <div class="text-center text-muted mt-4 small">
        Sistem Penjaminan Mutu Internal &copy; {{ date('Y') }}
    </div>
@endsection
