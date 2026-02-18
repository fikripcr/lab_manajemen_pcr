@extends('layouts.tabler.app')

@section('title', 'Detail Surat Bebas Lab')

@section('content')
<div class="container-xl">
    <x-tabler.page-header title="Detail Pengajuan" :pretitle="'#' . $surat->surat_bebas_lab_id">
        <x-slot:actions>
            <x-tabler.button type="back" href="{{ route('lab.surat-bebas.index') }}" />
        </x-slot:actions>
    </x-tabler.page-header>

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
                                <x-tabler.button href="#" class="btn-success mt-2" icon="bx bx-download" text="Download PDF" />
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Approval History --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Approval</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pejabat</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($surat->approvals as $approval)
                                    <tr>
                                        <td>{{ $approval->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ $approval->pejabat }}</td>
                                        <td>
                                            @php
                                                $hBadges = [
                                                    'pending' => 'warning',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger',
                                                ];
                                                $hColor = $hBadges[$approval->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $hColor }}">{{ ucfirst($approval->status) }}</span>
                                        </td>
                                        <td>{{ $approval->keterangan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Belum ada riwayat approval.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                                <x-tabler.form-textarea name="remarks" label="Catatan (Optional)" rows="3" placeholder="Alasan..." />
                                <div class="d-flex gap-2">
                                    <x-tabler.button type="submit" name="status" value="approved" class="btn-success w-100" icon="bx bx-check" text="Approve" />
                                    <x-tabler.button type="submit" name="status" value="rejected" class="btn-danger w-100" icon="bx bx-x" text="Reject" />
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
