@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Pembayaran Pendaftaran" pretitle="PMB">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pmb.camaba.dashboard') }}" class="btn-outline-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Instruksi Pembayaran</h3></div>
                    <div class="card-body">
                        <div class="mb-4">
                            <p>Silakan lakukan transfer biaya pendaftaran ke rekening berikut:</p>
                            <div class="p-3 bg-light rounded text-center mb-3">
                                <h2 class="mb-1 text-primary">Bank Mandiri (VA)</h2>
                                <h1 class="mb-0">88998 {{ Auth::user()->id }}</h1>
                                <div class="text-muted">A.N. {{ Auth::user()->name }}</div>
                            </div>
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <div class="text-muted small">Total Tagihan</div>
                                    <h2 class="text-danger">Rp {{ number_format($pendaftaran->jalur->biaya_pendaftaran, 0, ',', '.') }}</h2>
                                </div>
                            </div>
                        </div>

                        <h4>Langkah-langkah:</h4>
                        <ol>
                            <li>Gunakan ATM/Mobile Banking Bank Mandiri atau Bank Lain.</li>
                            <li>Pilih menu Bayar > Pendidikan > Politeknik Caltex Riau.</li>
                            <li>Masukkan Nomor Virtual Account di atas.</li>
                            <li>Pastikan nominal dan nama sesuai.</li>
                            <li>Simpan struk/bukti transfer Anda.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Konfirmasi Pembayaran</h3></div>
                    <div class="card-body">
                        <form action="{{ route('pmb.camaba.confirm-payment', $pendaftaran->encrypted_pendaftaran_id) }}" method="POST" class="ajax-form" enctype="multipart/form-data" data-redirect="true">
                            @csrf
                            
                            <x-tabler.form-input name="bank_asal" label="Bank Asal" placeholder="Contoh: BNI / BRI / Mandiri" required="true" />
                            <x-tabler.form-input name="nama_pengirim" label="Nama Pengirim di Rekening" placeholder="Sesuai nama di struk" required="true" />
                            <x-tabler.form-input type="date" name="tanggal_bayar" label="Tanggal Pembayaran" value="{{ date('Y-m-d') }}" required="true" />
                            
                            <div class="mb-3">
                                <x-tabler.form-input type="file" name="bukti_bayar" label="Bukti Transfer (Gambar/PDF)" accept="image/*,application/pdf" required="true" />
                            </div>

                            <div class="form-footer">
                                <x-tabler.button type="submit" class="btn-success w-100" text="Kirim Konfirmasi" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
