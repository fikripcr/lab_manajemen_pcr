@extends('layouts.admin.app')

@section('title', 'Detail Surat Bebas Lab')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Detail Pengajuan #{{ $surat->surat_bebas_lab_id }}
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('lab.surat-bebas.index') }}" class="btn btn-secondary">
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
                        <h3 class="card-title">Informasi Pengajuan</h3>
                        <div class="card-actions">
                            @php
                                $badges = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                ];
                                $color = $badges[$surat->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ ucfirst($surat->status) }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Mahasiswa</div>
                                <div class="datagrid-content">{{ $surat->student->name }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Tanggal Pengajuan</div>
                                <div class="datagrid-content">{{ $surat->created_at->format('d M Y H:i') }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Diproses Oleh</div>
                                <div class="datagrid-content">{{ $surat->approver->name ?? '-' }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Tanggal Proses</div>
                                <div class="datagrid-content">{{ $surat->approved_at ? $surat->approved_at->format('d M Y') : '-' }}</div>
                            </div>
                        </div>

                        <div class="mt-3"></div>
                        
                        @if($surat->remarks)
                        <div class="mb-3">
                            <label class="form-label text-muted">Catatan / Remarks</label>
                            <div class="form-control-plaintext border p-2 rounded bg-light">
                                {{ $surat->remarks }}
                            </div>
                        </div>
                        @endif

                        @if($surat->status == 'approved')
                            <div class="alert alert-success mt-3">
                                <h4><i class="bx bx-check-circle me-1"></i> Surat Bebas Lab Disetujui</h4>
                                <p>Silahkan unduh surat bebas lab anda di bawah ini.</p>
                                <a href="#" class="btn btn-success mt-2"><i class="bx bx-download me-2"></i> Download PDF</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Approval Action</h3>
                    </div>
                    <div class="card-body">
                        @if($surat->status == 'pending')
                            <form action="{{ route('lab.surat-bebas.status', encryptId($surat->surat_bebas_lab_id)) }}" method="POST" class="ajax-form">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Catatan (Optional)</label>
                                    <textarea name="remarks" class="form-control" rows="3" placeholder="Alasan..."></textarea>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" name="status" value="approved" class="btn btn-success w-100">
                                        <i class="bx bx-check me-2"></i> Approve
                                    </button>
                                    <button type="submit" name="status" value="rejected" class="btn btn-danger w-100">
                                        <i class="bx bx-x me-2"></i> Reject
                                    </button>
                                </div>
                            </form>
                        @else
                           <div class="text-center py-4">
                                <p class="text-muted">Status sudah: <strong>{{ ucfirst($surat->status) }}</strong></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
