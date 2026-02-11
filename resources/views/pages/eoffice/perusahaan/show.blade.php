@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="{{ $perusahaan->nama_perusahaan }}" pretitle="Detail Perusahaan">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-arrow-left" text="Kembali" class="btn-link" 
            onclick="window.history.back()" />
        <x-tabler.button type="button" icon="ti ti-pencil" text="Edit" class="btn-primary ajax-modal-btn" 
            data-url="{{ route('eoffice.perusahaan.edit', $perusahaan->perusahaan_id) }}" data-modal-title="Edit Perusahaan" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Dasar</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Nama Perusahaan</label>
                    <div class="form-control-plaintext fw-bold">{{ $perusahaan->nama_perusahaan }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Kategori</label>
                    <div class="form-control-plaintext">
                        <span class="badge bg-blue-lt">{{ $perusahaan->kategori->nama_kategori ?? '-' }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Kota</label>
                    <div class="form-control-plaintext">{{ $perusahaan->kota ?? '-' }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Telepon</label>
                    <div class="form-control-plaintext">{{ $perusahaan->telp ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Alamat & Lokasi</h3>
            </div>
            <div class="card-body">
                <p>{{ $perusahaan->alamat ?? 'Alamat tidak tersedia.' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
