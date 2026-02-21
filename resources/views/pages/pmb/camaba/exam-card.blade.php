@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Kartu Peserta Ujian PMB" pretitle="PMB">
    <x-slot:actions>
        <x-tabler.button type="button" class="btn-outline-secondary" icon="ti ti-arrow-left" text="Kembali" href="{{ route('pmb.camaba.dashboard') }}" />
        <x-tabler.button type="button" class="btn-primary" onclick="window.print();" icon="ti ti-printer" text="Cetak Kartu" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

        <div class="card card-print">
            <div class="card-body">
                <div class="row align-items-center mb-4 text-center">
                    <div class="col-auto">
                        <img src="{{ asset('static/logo-pcr.png') }}" height="60" alt="">
                    </div>
                    <div class="col">
                        <h2 class="mb-0">POLITEKNIK CALTEX RIAU</h2>
                        <h3 class="mb-0">KARTU PESERTA UJIAN PMB - {{ $pendaftaran->periode->nama_periode }}</h3>
                        <div class="text-muted">Jl. Umban Sari No.1, Rumbai, Pekanbaru</div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-4">
                    <div class="col-8">
                        <table class="table table-sm table-borderless">
                            <tr><td width="30%">No. Pendaftaran</td><td>: <strong>{{ $pendaftaran->no_pendaftaran }}</strong></td></tr>
                            <tr><td>Nama Lengkap</td><td>: {{ $pendaftaran->user->name }}</td></tr>
                            <tr><td>Jalur</td><td>: {{ $pendaftaran->jalur->nama_jalur }}</td></tr>
                            <tr><td>Pilihan 1</td><td>: {{ $pendaftaran->pilihanProdi[0]->orgUnit->name ?? '-' }}</td></tr>
                            <tr><td>Pilihan 2</td><td>: {{ $pendaftaran->pilihanProdi[1]->orgUnit->name ?? '-' }}</td></tr>
                        </table>

                        <div class="mt-4 p-3 border rounded">
                            <h4 class="mb-2">JADWAL UJIAN:</h4>
                            @if($pendaftaran->pesertaUjian)
                                <table class="table table-sm table-borderless mb-0">
                                    <tr><td width="30%">Sesi</td><td>: {{ $pendaftaran->pesertaUjian->sesiUjian->nama_sesi }}</td></tr>
                                    <tr><td>Waktu</td><td>: {{ formatTanggalIndo($pendaftaran->pesertaUjian->sesiUjian->waktu_mulai) }}</td></tr>
                                    <tr><td>Lokasi</td><td>: {{ $pendaftaran->pesertaUjian->sesiUjian->lokasi }}</td></tr>
                                    <tr><td>No. Meja</td><td>: {{ $pendaftaran->pesertaUjian->nomor_meja ?? '-' }}</td></tr>
                                </table>
                            @else
                                <div class="text-muted">Jadwal belum ditentukan. Silakan cek berkala.</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pendaftaran->user->name) }}&size=150" class="img-thumbnail" alt="Foto">
                        </div>
                        <div class="small">Scan untuk Validasi</div>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $pendaftaran->no_pendaftaran }}" alt="QR Code">
                    </div>
                </div>

                <div class="mt-5 small text-muted">
                    <strong>Peraturan Ujian:</strong>
                    <ol class="ps-3">
                        <li>Peserta wajib membawa kartu ujian ini (cetak).</li>
                        <li>Peserta wajib hadir 15 menit sebelum ujian dimulai.</li>
                        <li>Peserta wajib membawa identitas diri (KTP/Kartu Pelajar).</li>
                    </ol>
                </div>
            </div>
        </div>
<style type="text/css">
    @media print {
        .btn-list, .page-header, .navbar, .footer { display: none !important; }
        .card { border: 1px solid #000; box-shadow: none; }
        body { background: #fff; }
    }
</style>
@endsection
