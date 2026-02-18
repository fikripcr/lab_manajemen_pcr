@if(request()->ajax() || request()->has('ajax'))
    <div class="modal-header">
        <h5 class="modal-title">Detail Request: {{ $softwareRequest->nama_software }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">Nama Software:</h6>
                <p class="mb-0 fw-bold">{{ $softwareRequest->nama_software }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">Dosen:</h6>
                <p class="mb-0">{{ $softwareRequest->dosen ? $softwareRequest->dosen->name : 'Guest' }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">Status:</h6>
                @php
                    $badgeClass = '';
                    $status = $softwareRequest->status;
                    if (in_array($status, ['menunggu_approval', 'pending'])) {
                        $badgeClass = 'bg-warning';
                        $statusText = 'Pending';
                    } elseif (in_array($status, ['disetujui', 'approved'])) {
                        $badgeClass = 'bg-success';
                        $statusText = 'Approved';
                    } elseif (in_array($status, ['ditolak', 'rejected'])) {
                        $badgeClass = 'bg-danger';
                        $statusText = 'Rejected';
                    } else {
                        $badgeClass = 'bg-secondary';
                        $statusText = ucfirst(str_replace('_', ' ', $status));
                    }
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">Tanggal Pengajuan:</h6>
                <p class="mb-0">{{ $softwareRequest->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <div class="mb-3">
            <h6 class="text-muted">Keterangan / Deskripsi:</h6>
            <div class="p-3 border rounded bg-light" style="max-height: 200px; overflow-y: auto;">
                {!! $softwareRequest->deskripsi !!}
            </div>
        </div>

        <h4 class="card-title mt-4 mb-2">Riwayat Approval</h4>
        <div class="table-responsive">
            <table class="table table-vcenter table-mobile-md card-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($softwareRequest->approvals as $approval)
                        <tr>
                            <td>{{ $approval->created_at->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $aStatus = $approval->status;
                                    $aBadge = 'bg-secondary';
                                    if(in_array($aStatus, ['approved', 'disetujui'])) $aBadge = 'bg-success';
                                    elseif(in_array($aStatus, ['rejected', 'ditolak'])) $aBadge = 'bg-danger';
                                    elseif(in_array($aStatus, ['pending', 'menunggu_approval'])) $aBadge = 'bg-warning';
                                @endphp
                                <span class="badge {{ $aBadge }}">{{ ucfirst($aStatus) }}</span>
                            </td>
                            <td>{{ $approval->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada riwayat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(in_array($softwareRequest->status, ['pending', 'menunggu_approval']))
            <div class="mt-4 p-3 border rounded bg-light">
                <h4 class="card-title">Proses Approval</h4>
                <form class="ajax-form" action="{{ route('lab.software-requests.approve', $softwareRequest->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <x-tabler.form-input name="pejabat" label="Nama Pejabat" value="{{ Auth::check() ? Auth::user()->name : '' }}" required="true" />
                    </div>
                    <x-tabler.form-textarea name="keterangan" label="Keterangan / Komentar" rows="2" />
                    <div class="btn-list mt-3">
                        <x-tabler.button type="submit" name="status" value="approved" class="btn-success btn-sm" icon="ti ti-check" text="Setujui" />
                        <x-tabler.button type="submit" name="status" value="rejected" class="btn-danger btn-sm" icon="ti ti-x" text="Tolak" />
                    </div>
                </form>
            </div>
        @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Tutup</button>
        <x-tabler.button type="edit" :href="route('lab.software-requests.edit', $softwareRequest->id)" />
    </div>
@else
    @extends('layouts.admin.app')

    @section('header')
        <x-tabler.page-header :title="'Detail Request: ' . $softwareRequest->nama_software" pretitle="Software Request">
            <x-slot:actions>
                <x-tabler.button type="back" :href="route('lab.software-requests.index')" />
                <x-tabler.button type="edit" :href="route('lab.software-requests.edit', $softwareRequest->id)" />
            </x-slot:actions>
        </x-tabler.page-header>
    @endsection

    @section('content')
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <x-tabler.flash-message />

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Nama Software:</h6>
                                <p class="mb-0 fw-bold">{{ $softwareRequest->nama_software }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Dosen:</h6>
                                <p class="mb-0">{{ $softwareRequest->dosen ? $softwareRequest->dosen->name : 'Guest' }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Status:</h6>
                                @php
                                    $badgeClass = '';
                                    $status = $softwareRequest->status;
                                    if (in_array($status, ['menunggu_approval', 'pending'])) {
                                        $badgeClass = 'bg-warning';
                                        $statusText = 'Pending';
                                    } elseif (in_array($status, ['disetujui', 'approved'])) {
                                        $badgeClass = 'bg-success';
                                        $statusText = 'Approved';
                                    } elseif (in_array($status, ['ditolak', 'rejected'])) {
                                        $badgeClass = 'bg-danger';
                                        $statusText = 'Rejected';
                                    } else {
                                        $badgeClass = 'bg-secondary';
                                        $statusText = ucfirst(str_replace('_', ' ', $status));
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Tanggal Pengajuan:</h6>
                                <p class="mb-0">{{ $softwareRequest->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Versi:</h6>
                                <p class="mb-0">{{ $softwareRequest->versi ?: '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">URL Download:</h6>
                                @if($softwareRequest->url_download)
                                    <a href="{{ $softwareRequest->url_download }}" target="_blank" class="text-truncate d-block">
                                        {{ $softwareRequest->url_download }}
                                    </a>
                                @else
                                    <p class="mb-0">-</p>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted">Mata Kuliah Terkait:</h6>
                            @if($softwareRequest->mataKuliahs->count() > 0)
                                <div class="row">
                                    @foreach($softwareRequest->mataKuliahs as $mataKuliah)
                                        <div class="col-md-6 mb-2">
                                            <span class="badge bg-label-primary me-1">{{ $mataKuliah->kode_mk }} - {{ $mataKuliah->nama_mk }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0">Tidak ada mata kuliah terkait</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted">Keterangan / Deskripsi:</h6>
                            <div class="p-3 border rounded bg-light">
                                {!! $softwareRequest->deskripsi !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Approval History --}}
                <div class="card mb-4">
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
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($softwareRequest->approvals as $approval)
                                    <tr>
                                        <td>{{ $approval->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $approval->pejabat }}</td>
                                        <td>
                                            @php
                                                $aStatus = $approval->status;
                                                $aBadge = 'bg-secondary';
                                                if(in_array($aStatus, ['approved', 'disetujui'])) $aBadge = 'bg-success';
                                                elseif(in_array($aStatus, ['rejected', 'ditolak'])) $aBadge = 'bg-danger';
                                                elseif(in_array($aStatus, ['pending', 'menunggu_approval'])) $aBadge = 'bg-warning';
                                            @endphp
                                            <span class="badge {{ $aBadge }}">{{ ucfirst($aStatus) }}</span>
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

                {{-- Approval Form --}}
                @if(in_array($softwareRequest->status, ['pending', 'menunggu_approval']))
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Proses Approval</h4>
                            <form class="ajax-form" action="{{ route('lab.software-requests.approve', $softwareRequest->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <x-tabler.form-input name="pejabat" label="Nama Pejabat" value="{{ Auth::check() ? Auth::user()->name : '' }}" required="true" placeholder="Nama Pejabat" />
                                </div>
                                    <x-tabler.form-textarea name="keterangan" label="Keterangan / Komentar" rows="3" placeholder="Tambahkan catatan jika ada..." />
                                <div class="btn-list">
                                <div class="row">
                                    <div class="col-md-4">
                                        <x-tabler.button type="submit" name="status" value="approved" class="btn-success w-100" icon="ti ti-check" text="Setujui" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-tabler.button type="submit" name="status" value="pending" class="btn-warning w-100" icon="ti ti-clock" text="Tangguhkan" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-tabler.button type="submit" name="status" value="rejected" class="btn-danger w-100" icon="ti ti-x" text="Tolak" />
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
@endif
