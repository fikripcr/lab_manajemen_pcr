@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Detail Lembur" pretitle="HR" />
@endsection

@section('content')
    <div class="row row-cards">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Informasi Lembur</h3>
                    <div class="card-actions">
                         @php 
                            $status = strtolower($lembur->status_approval);
                            $badgeColor = [
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'pending'  => 'warning',
                                'diajukan' => 'warning'
                            ][$status] ?? 'secondary';
                         @endphp
                         <span class="badge bg-{{ $badgeColor }} text-white">
                            {{ ucfirst($lembur->status_approval) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-vcenter">
                        <tr>
                            <th width="150" class="text-secondary">Judul</th>
                            <td>: {{ $lembur->judul }}</td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Pengusul</th>
                            <td>: {{ $lembur->pengusul?->latestDataDiri?->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Tanggal</th>
                            <td>: {{ $lembur->tgl_pelaksanaan?->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Waktu</th>
                            <td>: {{ $lembur->jam_mulai }} - {{ $lembur->jam_selesai }} ({{ floor($lembur->durasi_menit / 60) }} jam {{ $lembur->durasi_menit % 60 }} menit)</td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Uraian</th>
                            <td>: {{ $lembur->uraian_pekerjaan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-secondary">Alasan</th>
                            <td>: {{ $lembur->alasan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header font-weight-bold">
                    <h3 class="card-title">Pegawai yang Lembur</h3>
                </div>
                <div class="card-table">
                    <x-tabler.datatable-client
                        id="table-pegawai-lembur"
                        :columns="[
                            ['name' => 'No', 'class' => 'w-1'],
                            ['name' => 'Nama'],
                            ['name' => 'Catatan']
                        ]"
                    >
                        @forelse($lembur->pegawais as $index => $pegawai)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex py-1 align-items-center">
                                        <span class="avatar me-2" style="background-image: url({{ $pegawai->latestDataDiri->foto_url ?? '' }})"></span>
                                        <div class="flex-fill">
                                            <div class="font-weight-medium">{{ $pegawai->latestDataDiri?->nama }}</div>
                                            <div class="text-secondary small">{{ $pegawai->latestDataDiri?->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $pegawai->pivot->catatan ?? '-' }}</td>
                            </tr>
                        @empty
                        @endforelse
                    </x-tabler.datatable-client>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Approval</h3>
                </div>
                <div class="list-group list-group-flush" style="max-height: 320px; overflow-y: auto;">
                    @forelse($lembur->approvals as $approval)
                        @php
                            $hStatus = strtolower($approval->status);
                            $statusIcons = [
                                'approved' => ['icon' => 'ti-check', 'color' => 'text-success'],
                                'rejected' => ['icon' => 'ti-x',     'color' => 'text-danger'],
                                'pending'  => ['icon' => 'ti-clock', 'color' => 'text-warning'],
                                'diajukan' => ['icon' => 'ti-file-text', 'color' => 'text-secondary'],
                            ];
                            $ic = $statusIcons[$hStatus] ?? ['icon' => 'ti-info-circle', 'color' => 'text-muted'];
                        @endphp
                        <div class="list-group-item py-2">
                            <div class="d-flex align-items-start gap-2">
                                <i class="ti {{ $ic['icon'] }} {{ $ic['color'] }} mt-1" style="font-size: 1rem; flex-shrink:0"></i>
                                <div class="flex-fill">
                                    <div class="fw-medium text-sm">{{ ucfirst($approval->status) }}</div>
                                    <div class="text-muted" style="font-size: 0.78rem;">
                                        {{ $approval->pejabat ?? 'System' }} &bull; {{ $approval->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    @if($approval->keterangan)
                                        <div class="mt-1 px-2 py-1 bg-light rounded small text-secondary fst-italic">
                                            "{{ $approval->keterangan }}"
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center py-3 text-muted small fst-italic">
                            Belum ada riwayat approval
                        </div>
                    @endforelse
                </div>
            </div>

            @if($lembur->status_approval == 'Diajukan' || $lembur->status_approval == 'pending')
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-3">Proses Approval</h4>
                    <form class="ajax-form" action="{{ route('hr.lembur.approve', $lembur->encrypted_lembur_id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <x-tabler.form-input name="pejabat" label="Nama Pejabat" value="{{ auth()->user()->name }}" required="true" placeholder="Nama Pejabat" />
                        </div>
                        <div class="mb-3">
                            <x-tabler.form-textarea name="keterangan" label="Keterangan / Komentar" rows="2" placeholder="Tambahkan catatan jika ada..." />
                        </div>
                        
                        <input type="hidden" name="status" id="approval_status_val" value="approved">

                        <div class="d-grid gap-2">
                             <x-tabler.button type="submit" class="btn-success w-100" onclick="$('#approval_status_val').val('approved')" icon="ti ti-check" text="Terima Pengajuan" />
                             <div class="row g-2">
                                <div class="col-6">
                                    <x-tabler.button type="submit" class="btn-warning w-100 btn-sm" onclick="$('#approval_status_val').val('pending')" icon="ti ti-clock" text="Hold" />
                                </div>
                                <div class="col-6">
                                    <x-tabler.button type="submit" class="btn-danger w-100 btn-sm" onclick="$('#approval_status_val').val('rejected')" icon="ti ti-x" text="Tolak" />
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

@push('scripts')
<script>
    // Simple script to handle button clicks setting the status value
    document.querySelectorAll('button[type="submit"][name="status"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default submission to set value first
            const form = this.closest('form');
            const statusInput = form.querySelector('input[name="status"]');
            statusInput.value = this.value;

            // Trigger submit via standard way or let ajax-form handle it
            // Since class is ajax-form, we just need to ensure the click passes the value.
            // Actually, for ajax-form usually simpler to let standard submit happen.
            // The onclick inline handler above might be enough or we remove e.preventDefault()

            // Let's rely on the onclick inline attribute I added in HTML: onclick="this.form.status.value='...'"
            // So we can remove this script block if standard form submission works.
            // However, with ajax-form listener, it might serialize the form.
            // Hidden input is safest.
            form.requestSubmit(this);
        });
    });
</script>
@endpush
