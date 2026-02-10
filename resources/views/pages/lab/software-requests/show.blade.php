@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header :title="'Detail Request: ' . $softwareRequest->nama_software" pretitle="Software Request">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('software-requests.index')" />
            <x-tabler.button type="edit" :href="route('software-requests.edit', $softwareRequest->id)" />
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
                                switch ($softwareRequest->status) {
                                    case 'menunggu_approval':
                                        $badgeClass = 'bg-warning';
                                        break;
                                    case 'disetujui':
                                        $badgeClass = 'bg-success';
                                        break;
                                    case 'ditolak':
                                        $badgeClass = 'bg-danger';
                                        break;
                                    default:
                                        $badgeClass = 'bg-secondary';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $softwareRequest->status)) }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Tanggal Pengajuan:</h6>
                            <p class="mb-0">{{ $softwareRequest->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Mata Kuliah Terkait:</h6>
                        @if($softwareRequest->mataKuliahs->count() > 0)
                            <div class="row">
                                @foreach($softwareRequest->mataKuliahs as $mataKuliah)
                                    <div class="col-md-6 mb-2">
                                        <span class="badge bg-label-primary me-1">{{ $mataKuliah->kode }} - {{ $mataKuliah->nama }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">Tidak ada mata kuliah terkait</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Alasan / Keperluan:</h6>
                        <p class="mb-0">{{ $softwareRequest->alasan }}</p>
                    </div>

                    @if($softwareRequest->catatan_admin)
                        <div class="mb-3">
                            <h6 class="text-muted">Catatan Admin:</h6>
                            <p class="mb-0">{{ $softwareRequest->catatan_admin }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
