@extends('layouts.assessment.app')

@section('title', $event->judul_Kegiatan . ' · Buku Tamu')

@section('assessment-header'){{-- kosong --}}@endsection

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5 px-3">
<div class="w-100" style="max-width:500px">

    {{-- ── Header ── --}}
    <div class="card rounded-bottom-0 border-bottom-0 mb-3">
        <div class="card-body bg-primary-lt rounded-top-3 rounded-bottom-0 p-4"
             style="background: linear-gradient(135deg, var(--tbl-primary-lt) 0%, var(--tbl-bg-surface) 100%) !important">
            <div class="text-primary fw-bold small text-uppercase mb-2" style="letter-spacing:.08em">
                Buku Tamu Digital
            </div>
            <h2 class="fw-extrabold mb-2" style="font-size:1.6rem; line-height:1.2">
                {{ $event->judul_Kegiatan }}
            </h2>
            <div class="d-flex flex-wrap gap-3 text-muted small">
                @if($event->tanggal_mulai)
                <span>
                    <i class="ti ti-calendar me-1"></i>
                    {{ formatTanggalIndo($event->tanggal_mulai) }}
                    @if($event->tanggal_selesai && $event->tanggal_selesai != $event->tanggal_mulai)
                        &mdash; {{ formatTanggalIndo($event->tanggal_selesai) }}
                    @endif
                </span>
                @endif
                @if($event->lokasi)
                <span><i class="ti ti-map-pin me-1"></i>{{ $event->lokasi }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Body ── --}}
    <div class="card shadow-sm">
        <div class="card-body p-4">

            @if($sukses)
            {{-- ── Sukses ── --}}
            <div class="text-center py-3">
                <span class="avatar avatar-xl bg-success-lt text-success rounded-circle mb-3">
                    <i class="ti ti-circle-check" style="font-size:2rem"></i>
                </span>
                <h3 class="fw-bold mb-1">Terima Kasih!</h3>
                <p class="text-muted mb-4">Kehadiran Anda telah berhasil dicatat.<br>Selamat menikmati acara.</p>
                <x-tabler.button type="button" class="btn-outline-primary px-5" onclick="window.location.reload()" icon="ti ti-user-plus" text="Daftarkan Tamu Lain" />
            </div>

            @else
            {{-- ── Form ── --}}
            <h4 class="fw-bold mb-0">Terima kasih telah hadir berpartisipasi</h4>
            <p class="text-muted small mb-4">Silakan isi data diri untuk keperluan pendataan acara.</p>

            @if($errors->has('error'))
            <div class="alert alert-danger alert-dismissible mb-3">
                <i class="ti ti-alert-circle me-2"></i>{{ $errors->first('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form method="POST" action="{{ route('attendance.store', $hashid) }}" id="form-tamu">
                @csrf

                <div class="mb-3">
                    <label class="form-label required fw-semibold">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ti ti-user"></i></span>
                        <input type="text" name="nama_tamu"
                            class="form-control @error('nama_tamu') is-invalid @enderror"
                            placeholder="Nama lengkap Anda"
                            value="{{ old('nama_tamu') }}" required autofocus>
                        @error('nama_tamu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <x-tabler.form-input name="instansi" label="Instansi" placeholder="Asal instansi" value="{{ old('instansi') }}" />
                    </div>
                    <div class="col-6">
                        <x-tabler.form-input name="jabatan" label="Jabatan" placeholder="Jabatan" value="{{ old('jabatan') }}" />
                    </div>
                </div>

                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="ti ti-device-mobile"></i></span>
                        <x-tabler.form-input name="kontak" label="No. HP / WhatsApp" placeholder="08xxxxxxxxxx" value="{{ old('kontak') }}" />
                    </div>
                </div>

                <x-tabler.button type="submit" class="btn-primary btn-lg w-100 fw-semibold" id="btn-submit">
                    <i class="ti ti-send me-2" id="btn-icon"></i>
                    <span id="btn-text">Daftar Sekarang</span>
                    <span id="btn-spinner" class="d-none">
                        <span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...
                    </span>
                </x-tabler.button>
            </form>
            @endif

        </div>

        <div class="card-footer text-center text-muted small">
            {{ config('app.name') }} &copy; {{ date('Y') }}
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('form-tamu')?.addEventListener('submit', function () {
    document.getElementById('btn-text').classList.add('d-none');
    document.getElementById('btn-icon').classList.add('d-none');
    document.getElementById('btn-spinner').classList.remove('d-none');
    document.getElementById('btn-submit').disabled = true;
});
</script>
@endpush
