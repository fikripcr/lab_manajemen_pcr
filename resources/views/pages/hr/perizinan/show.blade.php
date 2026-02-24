@extends('layouts.tabler.app')

@section('title', 'Detail Pengajuan Izin')

@section('content')
<x-tabler.page-header title="Detail Pengajuan Izin" pretitle="HR & Kepegawaian">
    <x-slot:actions>
        <div class="btn-list">
            <x-tabler.button type="button" icon="ti ti-arrow-left" text="Kembali" href="{{ route('hr.perizinan.index') }}" class="btn-secondary" />
        </div>
    </x-slot:actions>
</x-tabler.page-header>

<div class="row row-cards">
    <div class="col-lg-7">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Informasi Pengajuan</h3>
                <div class="card-actions">
                    @php 
                        $status = $perizinan->status;
                        $badgeColor = [
                            'Draft'    => 'secondary',
                            'Diajukan' => 'warning',
                            'Pending'  => 'warning',
                            'Approved' => 'success',
                            'Rejected' => 'danger',
                        ][$status] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $badgeColor }} text-white">
                        {{ $status }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-vcenter">
                    <tr>
                        <th width="180" class="text-secondary font-weight-bold">Pegawai</th>
                        <td>: {{ $perizinan->pengusulPegawai?->latestDataDiri->inisial }} - {{ $perizinan->pengusulPegawai?->latestDataDiri->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary font-weight-bold">Jenis Izin</th>
                        <td>: {{ $perizinan->jenisIzin?->nama }} ({{ $perizinan->jenisIzin?->kategori }})</td>
                    </tr>
                    <tr>
                        <th class="text-secondary font-weight-bold">Waktu / Tanggal</th>
                        <td>: 
                            {{ $perizinan->tgl_awal?->format('d/m/Y') }} 
                            @if($perizinan->tgl_akhir && $perizinan->tgl_akhir != $perizinan->tgl_awal)
                                s/d {{ $perizinan->tgl_akhir?->format('d/m/Y') }}
                            @endif
                            @if($perizinan->jam_awal)
                                <br><small class="text-muted ms-2">Jam: {{ $perizinan->jam_awal }} - {{ $perizinan->jam_akhir }}</small>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-secondary font-weight-bold">Keterangan</th>
                        <td>: {{ $perizinan->keterangan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary font-weight-bold">Pekerjaan Ditinggalkan</th>
                        <td>: {{ $perizinan->pekerjaan_ditinggalkan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-secondary font-weight-bold">Alamat Izin</th>
                        <td>: {{ $perizinan->alamat_izin ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Riwayat Approval</h3>
            </div>
            <div class="list-group list-group-flush list-group-hoverable">
                @forelse ($perizinan->approvalHistory as $history)
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                @php
                                    $statusIcons = [
                                        'Approved' => 'ti-check text-success',
                                        'Rejected' => 'ti-x text-danger',
                                        'Pending' => 'ti-clock text-warning',
                                        'Diajukan' => 'ti-file-text text-secondary',
                                        'Draft' => 'ti-file-pencil text-secondary'
                                    ];
                                    $icon = $statusIcons[$history->status] ?? 'ti-info-circle';
                                @endphp
                                <i class="ti {{ $icon }} fs-2"></i>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">{{ $history->status }}</div>
                                <div class="text-muted small">
                                    @if($history->pejabat)
                                        Oleh: {{ $history->pejabat }} ({{ $history->jenis_jabatan }})
                                    @else
                                        Dibuat oleh System
                                    @endif
                                    <br>
                                    <span class="text-secondary">{{ $history->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($history->keterangan)
                                    <div class="mt-2 p-2 bg-light rounded-2 small text-secondary italic">
                                        "{{ $history->keterangan }}"
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-center py-4 text-muted small italic">Belum ada riwayat approval.</div>
                @endforelse
            </div>
        </div>

        @if($perizinan->status == 'Diajukan' || $perizinan->status == 'Draft' || $perizinan->status == 'Pending')
        <div class="card shadow-sm border-top border-warning border-3">
            <div class="card-body">
                <h4 class="card-title mb-3">Proses Approval</h4>
                <form class="ajax-form" action="{{ route('hr.perizinan.approve', $perizinan->encrypted_perizinan_id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-tabler.form-input name="pejabat" label="Nama Pejabat" value="{{ auth()->user()->pegawai?->latestDataDiri?->nama ?? auth()->user()->name }}" required="true" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-tabler.form-input name="jenis_jabatan" label="Jabatan Penyetuju" value="{{ auth()->user()->pegawai?->latestJabatanStruktural?->nama ?? 'Atasan' }}" required="true" />
                        </div>
                    </div>
                    <x-tabler.form-textarea name="keterangan" label="Keterangan / Komentar" rows="2" placeholder="Tambahkan catatan jika ada..." />
                    
                    <input type="hidden" name="status" id="approval_status_val" value="Approved">

                    <div class="d-grid gap-2 mt-3">
                        <x-tabler.button type="submit" class="btn-success w-100" onclick="$('#approval_status_val').val('Approved')" icon="ti ti-check" text="Terima Pengajuan" />
                        <div class="row g-2">
                            <div class="col-6">
                                <x-tabler.button type="submit" class="btn-warning w-100 btn-sm" onclick="$('#approval_status_val').val('Pending')" icon="ti ti-clock" text="Tangguhkan" />
                            </div>
                            <div class="col-6">
                                <x-tabler.button type="submit" class="btn-danger w-100 btn-sm" onclick="$('#approval_status_val').val('Rejected')" icon="ti ti-x" text="Tolak" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

