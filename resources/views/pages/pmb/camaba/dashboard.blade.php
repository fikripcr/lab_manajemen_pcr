@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Dashboard Calon Mahasiswa</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            @if(!$pendaftaran)
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
                                        Mulai Pendaftaran ({{ $periodeAktif->nama_periode }})
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning mt-3">
                                    Mohon maaf, saat ini pendaftaran belum dibuka.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                {{-- Status Tracker --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Status Pendaftaran: <strong>{{ $pendaftaran->no_pendaftaran }}</strong></h3>
                            <div class="card-actions">
                                <span class="badge {{ $pendaftaran->status_terkini == 'Lulus' ? 'bg-success' : ($pendaftaran->status_terkini == 'Tidak_Lulus' ? 'bg-danger' : 'bg-primary') }}">
                                    {{ str_replace('_', ' ', $pendaftaran->status_terkini) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="steps steps-green mb-3">
                                <a href="#" class="step-item active">Registrasi</a>
                                <a href="#" class="step-item {{ in_array($pendaftaran->status_terkini, ['Draft']) ? '' : 'active' }}">Pembayaran</a>
                                <a href="#" class="step-item {{ in_array($pendaftaran->status_terkini, ['Draft', 'Menunggu_Verifikasi_Bayar']) ? '' : 'active' }}">Berkas</a>
                                <a href="#" class="step-item {{ in_array($pendaftaran->status_terkini, ['Lulus', 'Tidak_Lulus']) ? 'active' : '' }}">Hasil</a>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h4>Data Pendaftaran</h4>
                                    <table class="table table-sm table-borderless">
                                        <tr><td width="35%">Jalur</td><td>: {{ $pendaftaran->jalur->nama_jalur }}</td></tr>
                                        <tr><td>Periode</td><td>: {{ $pendaftaran->periode->nama_periode }}</td></tr>
                                        <tr><td>Pilihan Prodi</td><td>: 
                                            <ol class="ps-3 mb-0">
                                                @foreach($pendaftaran->pilihanProdi as $p)
                                                    <li>{{ $p->orgUnit->name ?? '-' }}</li>
                                                @endforeach
                                            </ol>
                                        </td></tr>
                                        @if($pendaftaran->status_terkini == 'Lulus')
                                            <tr><td><strong>Diterima di</strong></td><td>: <span class="badge bg-success text-white">{{ $pendaftaran->orgUnitDiterima->name ?? '-' }}</span></td></tr>
                                            <tr><td><strong>NIM Akhir</strong></td><td>: <strong>{{ $pendaftaran->nim_final }}</strong></td></tr>
                                        @endif
                                    </table>
                                </div>
                                <div class="col-md-6 text-center">
                                    @include('pages.pmb.camaba._status_info', ['pendaftaran' => $pendaftaran])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
