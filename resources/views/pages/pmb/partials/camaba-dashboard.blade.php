@if(!$pendaftaran)
    <div class="row row-cards">
        <div class="col-12">
            <x-tabler.card class="card-md">
                <div class="card-status-top bg-primary"></div>
                <x-tabler.card-body class="text-center py-5">
                    <img src="{{ asset('static/illustrations/undraw_sign_in_re_o58h.svg') }}" height="128" class="mb-n2" alt="">
                    <h1 class="mt-4">Selamat Datang di Portal PMB!</h1>
                    <p class="text-muted">Anda belum memiliki pendaftaran aktif. Silakan mulai pendaftaran Anda sekarang.</p>
                    @if($periodeAktif)
                        <div class="mt-3">
                            <x-tabler.button href="{{ route('pmb.camaba.register') }}" class="btn-primary btn-lg" icon="ti ti-user-plus" text="Mulai Pendaftaran ({{ $periodeAktif->nama_periode }})" />
                        </div>
                    @else
                        <div class="alert alert-warning mt-3">
                            <i class="ti ti-alert-triangle me-2"></i>
                            Mohon maaf, saat ini pendaftaran belum dibuka.
                        </div>
                    @endif
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
    </div>
@else
    {{-- HAS REGISTRATION --}}
    <div class="row row-cards mb-4">
        {{-- Status Card --}}
        <div class="col-md-6 col-lg-3">
            <x-tabler.card>
                <x-tabler.card-body>
                    <div class="d-flex align-items-center">
                        <div class="subheader">No. Pendaftaran</div>
                    </div>
                    <div class="h1 mb-3">{{ $pendaftaran->no_pendaftaran }}</div>
                    <div class="d-flex">
                        <span class="badge {{ getStatusBadgeClass($pendaftaran->status_terkini) }}">
                            {{ str_replace('_', ' ', $pendaftaran->status_terkini) }}
                        </span>
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>

        {{-- Periode Info --}}
        <div class="col-md-6 col-lg-3">
            <x-tabler.card>
                <x-tabler.card-body>
                    <div class="d-flex align-items-center">
                        <div class="subheader">Periode</div>
                    </div>
                    <div class="h3 mb-3">{{ $pendaftaran->periode->nama_periode }}</div>
                    <div class="text-muted">{{ $pendaftaran->jalur->nama_jalur }}</div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>

        {{-- Payment Status --}}
        <div class="col-md-6 col-lg-3">
            <x-tabler.card>
                <x-tabler.card-body>
                    <div class="d-flex align-items-center">
                        <div class="subheader">Pembayaran</div>
                    </div>
                    <div class="h3 mb-3">
                        @if($pendaftaran->pembayaran->where('jenis_bayar', 'Formulir')->first())
                            {{ $pendaftaran->pembayaran->where('jenis_bayar', 'Formulir')->first()->status_verifikasi }}
                        @else
                            Belum Bayar
                        @endif
                    </div>
                    <div class="text-muted">Rp. {{ number_format($pendaftaran->jalur->biaya_pendaftaran, 0, ',', '.') }}</div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>

        {{-- Documents Status --}}
        <div class="col-md-6 col-lg-3">
            <x-tabler.card>
                <x-tabler.card-body>
                    <div class="d-flex align-items-center">
                        <div class="subheader">Dokumen</div>
                    </div>
                    <div class="h3 mb-3">
                        {{ $pendaftaran->dokumenUpload->count() }} / {{ $pendaftaran->dokumenUpload->count() }}
                    </div>
                    <div class="text-muted">
                        @php
                            $totalDocs = $pendaftaran->dokumenUpload->count();
                            $verifiedDocs = $pendaftaran->dokumenUpload->where('status_verifikasi', 'Valid')->count();
                        @endphp
                        @if($totalDocs > 0)
                            {{ round(($verifiedDocs / $totalDocs) * 100) }}% Terverifikasi
                        @else
                            Belum Upload
                        @endif
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
    </div>

    {{-- Status Progress --}}
    <div class="row row-cards mb-4">
        <div class="col-12">
            <x-tabler.card>
                <x-tabler.card-header title="Progress Pendaftaran" />
                <x-tabler.card-body>
                    @include('pmb.partials.status-tracker', ['pendaftaran' => $pendaftaran])
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
    </div>



    {{-- Recent Notifications --}}
    <div class="row row-cards">
        <div class="col-12">
            <x-tabler.card>
                <x-tabler.card-header title="Notifikasi Terbaru" />
                <x-tabler.card-body class="p-0">
                    @php
                        $riwayat = $pendaftaran->riwayat()->latest()->limit(5)->get();
                    @endphp
                    @if($riwayat->count() > 0)
                        <div class="timeline">
                            @foreach($riwayat as $item)
                                <div class="timeline-item">
                                    <div class="timeline-point timeline-point-{{ getTimelineColor($item->status_baru) }}"></div>
                                    <div class="timeline-content">
                                        <div class="timeline-time">{{ $item->waktu_kejadian->diffForHumans() }}</div>
                                        <div class="timeline-title">{{ getStatusLabel($item->status_baru) }}</div>
                                        @if($item->keterangan)
                                            <div class="timeline-body text-muted">{{ $item->keterangan }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <x-tabler.empty-state title="Belum ada notifikasi" text="Status pendaftaran Anda akan muncul di sini." />
                    @endif
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
    </div>
@endif

@php
    function getStatusBadgeClass($status) {
        $classes = [
            'Draft' => 'bg-secondary text-white',
            'Menunggu_Verifikasi_Bayar' => 'bg-warning text-white',
            'Menunggu_Verifikasi_Berkas' => 'bg-warning text-white',
            'Revisi_Berkas' => 'bg-orange text-white',
            'Siap_Ujian' => 'bg-info text-white',
            'Selesai_Ujian' => 'bg-blue text-white',
            'Lulus' => 'bg-success text-white',
            'Tidak_Lulus' => 'bg-danger text-white',
            'Daftar_Ulang' => 'bg-purple text-white'
        ];
        
        return $classes[$status] ?? 'bg-primary';
    }

    function getTimelineColor($status) {
        $colors = [
            'Draft' => 'secondary',
            'Menunggu_Verifikasi_Bayar' => 'warning',
            'Menunggu_Verifikasi_Berkas' => 'warning',
            'Revisi_Berkas' => 'orange',
            'Siap_Ujian' => 'info',
            'Selesai_Ujian' => 'blue',
            'Lulus' => 'success',
            'Tidak_Lulus' => 'danger',
            'Daftar_Ulang' => 'purple'
        ];
        
        return $colors[$status] ?? 'primary';
    }

    function getStatusLabel($status) {
        $labels = [
            'Draft' => 'Pendaftaran Dibuat',
            'Menunggu_Verifikasi_Bayar' => 'Menunggu Verifikasi Pembayaran',
            'Menunggu_Verifikasi_Berkas' => 'Menunggu Verifikasi Berkas',
            'Revisi_Berkas' => 'Berkas Perlu Direvisi',
            'Siap_Ujian' => 'Siap Mengikuti Ujian',
            'Selesai_Ujian' => 'Ujian Selesai',
            'Lulus' => 'Selamat! Anda Lulus',
            'Tidak_Lulus' => 'Mohon Maaf, Anda Tidak Lulus',
            'Daftar_Ulang' => 'Daftar Ulang Dibuka'
        ];
        
        return $labels[$status] ?? $status;
    }
@endphp
