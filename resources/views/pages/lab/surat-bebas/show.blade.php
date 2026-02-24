@extends('layouts.tabler.app')

@section('title', 'Detail Surat Bebas Lab')

@section('content')
    <x-tabler.page-header title="Detail Pengajuan" :pretitle="'#' . $surat->surat_bebas_lab_id">
        <x-slot:actions>
            <x-tabler.button type="back" href="{{ route('lab.surat-bebas.index') }}" />
        </x-slot:actions>
    </x-tabler.page-header>

        <div class="row row-cards">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pengajuan</h3>
                        <div class="card-actions">
                            @php
                                $badges = [
                                    'pending' => 'warning',
                                    'tangguhkan' => 'info',
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
                                <div class="datagrid-content">{{ $surat->latestApproval?->pejabat ?? '-' }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Tanggal Proses</div>
                                <div class="datagrid-content">{{ $surat->latestApproval?->created_at ? $surat->latestApproval->created_at->format('d M Y') : '-' }}</div>
                            </div>
                        </div>

                        <div class="mt-3"></div>

                        @if($surat->latestApproval?->catatan)
                        <div class="mb-3">
                            <label class="form-label text-muted">Catatan</label>
                            <div class="form-control-plaintext border p-2 rounded bg-light">
                                {{ $surat->latestApproval->catatan }}
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
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Approval Action</h3>
                    </div>
                    <div class="card-body">
                        @if(in_array($surat->status, ['pending', 'tangguhkan']))
                            <form action="{{ route('lab.surat-bebas.status', encryptId($surat->surat_bebas_lab_id)) }}" method="POST" class="ajax-form">
                                @csrf
                                <x-tabler.form-textarea name="catatan" label="Catatan (Optional)" rows="3" placeholder="Alasan..." />
                                <div class="d-flex gap-2">
                                    <x-tabler.button type="submit" name="status" value="approved" class="btn-success w-100" icon="bx bx-check" text="Approve" />
                                    <x-tabler.button type="submit" name="status" value="tangguhkan" class="btn-warning w-100" icon="bx bx-time" text="Tangguhkan" />
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

        <div class="row row-cards mt-1">
            <div class="col-12">
                <x-tabler.approval-history :approvals="$surat->approvals" />
            </div>
        </div>
@endsection
