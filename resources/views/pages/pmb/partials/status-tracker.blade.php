{{-- STATUS TRACKER PARTIAL --}}
@php
    $statusSteps = [
        'Draft' => [
            'title' => 'Pendaftaran Dibuat',
            'description' => 'Pendaftaran Anda telah berhasil dibuat',
            'icon' => 'ti ti-user-plus',
            'completed' => true
        ],
        'Menunggu_Verifikasi_Bayar' => [
            'title' => 'Menunggu Verifikasi Pembayaran',
            'description' => 'Silakan upload bukti pembayaran dan tunggu verifikasi',
            'icon' => 'ti ti-cash',
            'completed' => in_array($pendaftaran->status_terkini, ['Menunggu_Verifikasi_Bayar', 'Menunggu_Verifikasi_Berkas', 'Revisi_Berkas', 'Siap_Ujian', 'Selesai_Ujian', 'Lulus', 'Tidak_Lulus', 'Daftar_Ulang'])
        ],
        'Menunggu_Verifikasi_Berkas' => [
            'title' => 'Menunggu Verifikasi Berkas',
            'description' => 'Berkas Anda sedang diverifikasi oleh tim PMB',
            'icon' => 'ti ti-file-text',
            'completed' => in_array($pendaftaran->status_terkini, ['Menunggu_Verifikasi_Berkas', 'Revisi_Berkas', 'Siap_Ujian', 'Selesai_Ujian', 'Lulus', 'Tidak_Lulus', 'Daftar_Ulang'])
        ],
        'Revisi_Berkas' => [
            'title' => 'Berkas Perlu Direvisi',
            'description' => 'Ada beberapa berkas yang perlu diperbaiki',
            'icon' => 'ti ti-edit',
            'completed' => in_array($pendaftaran->status_terkini, ['Revisi_Berkas', 'Siap_Ujian', 'Selesai_Ujian', 'Lulus', 'Tidak_Lulus', 'Daftar_Ulang'])
        ],
        'Siap_Ujian' => [
            'title' => 'Siap Mengikuti Ujian',
            'description' => 'Anda sudah bisa mengikuti ujian CBT',
            'icon' => 'ti ti-clock-hour-4',
            'completed' => in_array($pendaftaran->status_terkini, ['Siap_Ujian', 'Selesai_Ujian', 'Lulus', 'Tidak_Lulus', 'Daftar_Ulang'])
        ],
        'Selesai_Ujian' => [
            'title' => 'Ujian Selesai',
            'description' => 'Anda telah menyelesaikan ujian',
            'icon' => 'ti ti-check',
            'completed' => in_array($pendaftaran->status_terkini, ['Selesai_Ujian', 'Lulus', 'Tidak_Lulus', 'Daftar_Ulang'])
        ],
        'Lulus' => [
            'title' => 'Selamat! Anda Lulus',
            'description' => 'Selamat! Anda dinyatakan lulus',
            'icon' => 'ti ti-trophy',
            'completed' => $pendaftaran->status_terkini === 'Lulus'
        ],
        'Tidak_Lulus' => [
            'title' => 'Tidak Lulus',
            'description' => 'Mohon maaf, Anda belum lulus',
            'icon' => 'ti ti-x',
            'completed' => $pendaftaran->status_terkini === 'Tidak_Lulus'
        ],
        'Daftar_Ulang' => [
            'title' => 'Daftar Ulang',
            'description' => 'Silakan lakukan daftar ulang untuk konfirmasi',
            'icon' => 'ti ti-refresh',
            'completed' => $pendaftaran->status_terkini === 'Daftar_Ulang'
        ]
    ];
@endphp

<div class="timeline">
    @foreach($statusSteps as $statusKey => $step)
        <div class="timeline-item">
            <div class="timeline-point timeline-point-{{ $step['completed'] ? 'success' : 'secondary' }}">
                @if($step['completed'])
                    <i class="ti {{ $step['icon'] }}"></i>
                @else
                    <i class="ti {{ $step['icon'] }} opacity-50"></i>
                @endif
            </div>
            <div class="timeline-content">
                <div class="timeline-time">
                    @if($step['completed'])
                        <i class="ti ti-check text-success me-1"></i>
                        Selesai
                    @else
                        <i class="ti ti-clock text-muted me-1"></i>
                        Menunggu
                    @endif
                </div>
                <div class="timeline-title {{ $step['completed'] ? '' : 'text-muted' }}">
                    {{ $step['title'] }}
                    @if($pendaftaran->status_terkini === $statusKey)
                        <span class="badge bg-primary ms-2">Current</span>
                    @endif
                </div>
                <div class="timeline-body text-muted">{{ $step['description'] }}</div>
                
                {{-- Action buttons for current step --}}
                @if($pendaftaran->status_terkini === $statusKey)
                    <div class="mt-3">
                        @switch($statusKey)
                            @case('Menunggu_Verifikasi_Bayar')
                                <a href="{{ route('pmb.camaba.payment') }}" class="btn btn-primary btn-sm">
                                    <i class="ti ti-upload me-1"></i>
                                    Upload Bukti Pembayaran
                                </a>
                                @break
                            
                            @case('Menunggu_Verifikasi_Berkas')
                                <a href="{{ route('pmb.camaba.upload') }}" class="btn btn-primary btn-sm">
                                    <i class="ti ti-file-upload me-1"></i>
                                    Upload Dokumen
                                </a>
                                @break
                            
                            @case('Revisi_Berkas')
                                <a href="{{ route('pmb.camaba.upload') }}" class="btn btn-warning btn-sm">
                                    <i class="ti ti-edit me-1"></i>
                                    Perbaiki Berkas
                                </a>
                                @break
                            
                            @case('Siap_Ujian')
                                <a href="{{ route('pmb.camaba.exam-card') }}" class="btn btn-info btn-sm">
                                    <i class="ti ti-id me-1"></i>
                                    Lihat Kartu Ujian
                                </a>
                                @break
                            
                            @case('Lulus')
                                <a href="{{ route('pmb.camaba.registration') }}" class="btn btn-success btn-sm">
                                    <i class="ti ti-refresh me-1"></i>
                                    Daftar Ulang
                                </a>
                                @break
                        @endswitch
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
