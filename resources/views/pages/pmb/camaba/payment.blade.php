@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Pembayaran Pendaftaran" pretitle="PMB">
    <x-slot:actions>
        <x-tabler.button type="back" href="{{ route('pmb.camaba.dashboard') }}" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

        <div class="row row-cards">
            <div class="col-md-7">
                <x-tabler.card>
                    <x-tabler.card-header title="Metode Pembayaran" icon="ti ti-credit-card" />
                    <x-tabler.card-body>
                        <div class="mb-4">
                            <h4 class="mb-2">Biaya Pendaftaran</h4>
                            <div class="h3 text-primary">Rp {{ number_format($pendaftaran->total_bayar, 0, ',', '.') }}</div>
                            <p class="text-muted small">Silakan pilih metode pembayaran di bawah ini untuk melanjutkan pendaftaran.</p>
                        </div>

                        {{-- Virtual Account Options --}}
                        <div class="mb-3">
                            <label class="form-label">Virtual Account</label>
                            <div class="row g-2">
                                <div class="col-6 col-sm-4 col-md-3">
                                    <label class="form-selectgroup-item w-100">
                                        <input type="radio" name="payment_method" value="briva" class="form-selectgroup-input">
                                        <span class="form-selectgroup-label d-flex align-items-center p-3">
                                            <span class="me-3">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/BRI_Logo.svg/1200px-BRI_Logo.svg.png" height="20" alt="BRI">
                                            </span>
                                            <span>BRIVA</span>
                                        </span>
                                    </label>
                                </div>
                                {{-- Add more banks as needed --}}
                            </div>
                        </div>

                        <div class="mt-4 d-grid">
                            <button type="button" class="btn btn-primary btn-lg" id="pay-button">
                                <i class="ti ti-lock me-2"></i>Bayar Sekarang
                            </button>
                        </div>
                    </x-tabler.card-body>
                </x-tabler.card>

                <x-tabler.card class="mt-4">
                    <x-tabler.card-header title="Butuh Bantuan?" />
                    <x-tabler.card-body>
                        <p class="text-muted small">Jika Anda mengalami kendala saat melakukan pembayaran, silakan hubungi pusat bantuan kami.</p>
                        <div class="d-flex align-items-center mt-3">
                            <span class="avatar avatar-sm bg-success-lt me-3">
                                <i class="ti ti-brand-whatsapp fs-2"></i>
                            </span>
                            <div>
                                <div class="font-weight-medium">WhatsApp Support</div>
                                <div class="text-muted small">+62 812-3456-7890</div>
                            </div>
                        </div>
                    </x-tabler.card-body>
                </x-tabler.card>
            </div>

            <div class="col-md-5">
                <x-tabler.card>
                    <x-tabler.card-header title="Konfirmasi Pembayaran" />
                    <x-tabler.card-body>
                        <form action="{{ route('pmb.camaba.confirm-payment', $pendaftaran->encrypted_pendaftaran_id) }}" method="POST" class="ajax-form" enctype="multipart/form-data" data-redirect="true">
                            @csrf
                            
                            <x-tabler.form-input name="bank_asal" label="Bank Asal" placeholder="Contoh: BNI / BRI / Mandiri" required="true" />
                            <x-tabler.form-input name="nama_pengirim" label="Nama Pengirim di Rekening" placeholder="Sesuai nama di struk" required="true" />
                            <x-tabler.form-input type="date" name="tanggal_bayar" label="Tanggal Pembayaran" value="{{ date('Y-m-d') }}" required="true" />
                            
                            <div class="mb-3">
                                <x-tabler.form-input type="file" name="bukti_bayar" label="Bukti Transfer (Gambar/PDF)" accept="image/*,application/pdf" required="true" />
                            </div>

                            <div class="form-footer">
                                <x-tabler.button type="success" class="w-100" text="Kirim Konfirmasi" />
                            </div>
                        </form>
                    </x-tabler.card-body>
                </x-tabler.card>
            </div>
        </div>
@endsection
