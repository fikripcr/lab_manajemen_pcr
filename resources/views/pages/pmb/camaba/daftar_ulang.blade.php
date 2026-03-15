@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Daftar Ulang" pretitle="PMB">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pmb.camaba.index') }}" type="back" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <x-tabler.card>
            <x-tabler.card-header title="Formulir Daftar Ulang" />
            <x-tabler.card-body>
                <div class="alert alert-success">
                    <h4><i class="ti ti-check"></i> Selamat!</h4>
                    <p>Anda dinyatakan <strong>LULUS</strong> seleksi. Silakan lengkapi data untuk daftar ulang.</p>
                </div>

                <form action="{{ route('pmb.camaba.process-daftar-ulang', $camaba->encrypted_camaba_id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <x-tabler.form-input label="Nama Lengkap" type="text" value="{{ $camaba->user->name }}" readonly="true" />
                    </div>

                    <div class="mb-3">
                        <x-tabler.form-input label="NIK" type="text" value="{{ $camaba->nik }}" readonly="true" />
                    </div>

                    <div class="mb-3">
                        <x-tabler.form-input label="No. Pendaftaran" type="text" value="{{ $pendaftaran->no_pendaftaran }}" readonly="true" />
                    </div>

                    <div class="mb-3">
                        <x-tabler.form-input label="Program Studi Diterima" type="text" value="{{ $pendaftaran->orgUnitDiterima->name ?? '-' }}" readonly="true" />
                    </div>

                    <div class="mb-3">
                        <x-tabler.form-input name="nim_final" label="NIM (Nomor Induk Mahasiswa)" type="text" :required="true" 
                               placeholder="Masukkan NIM Anda" value="{{ old('nim_final') }}" />
                        <div class="form-hint">NIM harus unik. Contoh: 2024TI001</div>
                    </div>

                    <div class="mt-4">
                        <x-tabler.button type="submit" icon="ti ti-check" text="Proses Daftar Ulang" />
                    </div>
                </form>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
</div>
@endsection
