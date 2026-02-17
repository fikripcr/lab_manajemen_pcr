{{-- CAMABA DASHBOARD PARTIAL --}}
@php
    $user = auth()->user();
    $pendaftaran = App\Models\Pmb\Pendaftaran::with(['periode', 'jalur', 'pilihanProdi.orgUnit', 'orgUnitDiterima', 'dokumenUpload.jenisDokumen', 'pembayaran'])
        ->where('user_id', $user->id)
        ->latest()
        ->first();
@endphp

@if(!$pendaftaran)
    {{-- NO REGISTRATION YET --}}
    <div class="row row-cards">
        <div class="col-12">
            <div class="card card-md">
                <div class="card-status-top bg-primary"></div>
                <div class="card-body text-center py-5">
                    <img src="{{ asset('static/illustrations/undraw_sign_in_re_o58h.svg') }}" height="128" class="mb-n2" alt="">
                    <h1 class="mt-4">Selamat Datang di Portal PMB!</h1>
                    <p class="text-muted">Anda belum memiliki pendaftaran aktif. Silakan mulai pendaftaran Anda sekarang.</p>
                    @if($periodeAktif)
                        <div class="mt-3">
                            <a href="{{ route('pmb.camaba.register') }}" class="btn btn-primary btn-lg">
                                <i class="ti ti-user-plus me-2"></i>
                                Mulai Pendaftaran ({{ $periodeAktif->nama_periode }})
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning mt-3">
                            <i class="ti ti-alert-triangle me-2"></i>
                            Mohon maaf, saat ini pendaftaran belum dibuka.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    {{-- HAS REGISTRATION --}}
    <div class="row row-cards mb-4">
        {{-- Status Card --}}
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">No. Pendaftaran</div>
                    </div>
                    <div class="h1 mb-3">{{ $pendaftaran->no_pendaftaran }}</div>
                    <div class="d-flex">
                        <span class="badge {{ getStatusBadgeClass($pendaftaran->status_terkini) }}">
                            {{ str_replace('_', ' ', $pendaftaran->status_terkini) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Periode Info --}}
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Periode</div>
                    </div>
                    <div class="h3 mb-3">{{ $pendaftaran->periode->nama_periode }}</div>
                    <div class="text-muted">{{ $pendaftaran->jalur->nama_jalur }}</div>
                </div>
            </div>
        </div>

        {{-- Payment Status --}}
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
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
                </div>
            </div>
        </div>

        {{-- Documents Status --}}
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
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
                </div>
            </div>
        </div>
    </div>

    {{-- Status Progress --}}
    <div class="row row-cards mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Progress Pendaftaran</h3>
                </div>
                <div class="card-body">
                    @include('pmb.partials.status-tracker', ['pendaftaran' => $pendaftaran])
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi Cepat</h3>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        {{-- Upload Documents --}}
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('pmb.camaba.upload') }}" class="btn btn-outline-primary w-100">
                                <i class="ti ti-upload me-2"></i>
                                Upload Dokumen
                            </a>
                        </div>

                        {{-- Payment --}}
                        @if(!$pendaftaran->pembayaran->where('jenis_bayar', 'Formulir')->where('status_verifikasi', 'Lunas')->first())
                            <div class="col-md-6 col-lg-3">
                                <a href="{{ route('pmb.camaba.payment') }}" class="btn btn-outline-success w-100">
                                    <i class="ti ti-cash me-2"></i>
                                    Konfirmasi Pembayaran
                                </a>
                            </div>
                        @endif

                        {{-- Exam Card --}}
                        @if(in_array($pendaftaran->status_terkini, ['Siap_Ujian', 'Selesai_Ujian']))
                            <div class="col-md-6 col-lg-3">
                                <a href="{{ route('pmb.camaba.exam-card') }}" class="btn btn-outline-info w-100">
                                    <i class="ti ti-id me-2"></i>
                                    Kartu Ujian
                                </a>
                            </div>
                        @endif

                        {{-- View Details --}}
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('pmb.pendaftaran.show', $pendaftaran->hashid) }}" class="btn btn-outline-secondary w-100">
                                <i class="ti ti-eye me-2"></i>
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Notifications --}}
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Notifikasi Terbaru</h3>
                </div>
                <div class="card-body">
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
                        <div class="empty">
                            <div class="empty-img"><img src="{{ asset('images/illustrations/undraw_empty_xct9.svg') }}" height="128" alt=""></div>
                            <p class="empty-title">Belum ada notifikasi</p>
                            <p class="empty-subtitle text-muted">Status pendaftaran Anda akan muncul di sini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@php
    function getStatusBadgeClass($status) {
        $classes = [
            'Draft' => 'bg-secondary',
            'Menunggu_Verifikasi_Bayar' => 'bg-warning',
            'Menunggu_Verifikasi_Berkas' => 'bg-warning',
            'Revisi_Berkas' => 'bg-orange',
            'Siap_Ujian' => 'bg-info',
            'Selesai_Ujian' => 'bg-blue',
            'Lulus' => 'bg-success',
            'Tidak_Lulus' => 'bg-danger',
            'Daftar_Ulang' => 'bg-purple'
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
