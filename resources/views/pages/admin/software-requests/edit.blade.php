@extends('layouts.admin.app')

@section('header')
    <x-tabler.page-header :title="'Edit Request: ' . $softwareRequest->nama_software" pretitle="Software Request">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('software-requests.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('software-requests.update', $softwareRequest->id) }}" method="POST" class="card">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <x-tabler.flash-message />

                    {{-- The original form tag was here, but the instruction implies the outer form should be the main one.
                         Keeping the content structure as close to the original as possible while applying the changes. --}}

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Nama Software:</h6>
                                <p class="mb-0 fw-bold">{{ $softwareRequest->nama_software }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Dosen:</h6>
                                <p class="mb-0">{{ $softwareRequest->dosen ? $softwareRequest->dosen->name : 'Guest' }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Mata Kuliah Terkait:</h6>
                                @if($softwareRequest->mataKuliahs->count() > 0)
                                    <div class="row">
                                        @foreach($softwareRequest->mataKuliahs as $mataKuliah)
                                            <div class="col-md-6 mb-2">
                                                <span class="badge bg-label-primary me-1">{{ $mataKuliah->kode }} - {{ $mataKuliah->nama }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mb-0">Tidak ada mata kuliah terkait</p>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Tanggal Pengajuan:</h6>
                                <p class="mb-0">{{ $softwareRequest->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted">Alasan / Keperluan:</h6>
                            <p class="mb-0">{{ $softwareRequest->alasan }}</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" >
                                    <option value="menunggu_approval" {{ old('status', $softwareRequest->status) === 'menunggu_approval' ? 'selected' : '' }}>
                                        Menunggu Approval
                                    </option>
                                    <option value="disetujui" {{ old('status', $softwareRequest->status) === 'disetujui' ? 'selected' : '' }}>
                                        Disetujui
                                    </option>
                                    <option value="ditolak" {{ old('status', $softwareRequest->status) === 'ditolak' ? 'selected' : '' }}>
                                        Ditolak
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="catatan_admin" class="form-label fw-bold">Catatan Admin</label>
                            <textarea name="catatan_admin" id="catatan_admin" class="form-control @error('catatan_admin') is-invalid @enderror" rows="4" placeholder="Tambahkan catatan untuk dosen...">{{ old('catatan_admin', $softwareRequest->catatan_admin) }}</textarea>
                            @error('catatan_admin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                </div>
                <div class="card-footer text-end">
                    <x-tabler.button type="submit" text="Update Status" />
                    <x-tabler.button type="cancel" :href="route('software-requests.index')" />
                </div>
            </form>
        </div>
    </div>
@endsection
