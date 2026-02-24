@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Daftar Ulang" pretitle="PMB">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pmb.camaba.index') }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulir Daftar Ulang</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h4><i class="ti ti-check"></i> Selamat!</h4>
                    <p>Anda dinyatakan <strong>LULUS</strong> seleksi. Silakan lengkapi data untuk daftar ulang.</p>
                </div>

                <form action="{{ route('pmb.camaba.process-daftar-ulang', $camaba->encrypted_camaba_id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" value="{{ $camaba->user->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIK</label>
                        <input type="text" class="form-control" value="{{ $camaba->nik }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. Pendaftaran</label>
                        <input type="text" class="form-control" value="{{ $pendaftaran->no_pendaftaran }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Program Studi Diterima</label>
                        <input type="text" class="form-control" value="{{ $pendaftaran->orgUnitDiterima->name ?? '-' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">NIM (Nomor Induk Mahasiswa)</label>
                        <input type="text" name="nim_final" class="form-control @error('nim_final') is-invalid @enderror" 
                               placeholder="Masukkan NIM Anda" required 
                               value="{{ old('nim_final') }}">
                        @error('nim_final')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">NIM harus unik. Contoh: 2024TI001</div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-check"></i> Proses Daftar Ulang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
