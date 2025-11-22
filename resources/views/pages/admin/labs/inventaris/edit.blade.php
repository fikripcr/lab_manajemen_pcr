@extends('layouts.admin.app')

@section('title', 'Edit Inventaris Lab: ' . $labInventaris->kode_inventaris)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Inventaris pada Lab: {{ $lab->name }}</h4>
                </div>
                <div class="card-body">
                    <x-admin.flash-message />

                    <form action="{{ route('labs.inventaris.update', [$labInventaris->encrypted_lab_id, $labInventaris->encrypted_id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inventaris_id" class="form-label">Nama Alat *</label>
                                    <select
                                        class="form-select @error('inventaris_id') is-invalid @enderror"
                                        id="inventaris_id"
                                        name="inventaris_id"
                                        required
                                    >
                                        <option value="">-- Pilih Inventaris --</option>
                                        @foreach($inventarisList as $item)
                                            <option value="{{ $item->inventaris_id }}" {{ old('inventaris_id', $labInventaris->inventaris_id) == $item->inventaris_id ? 'selected' : '' }}>
                                                {{ $item->nama_alat }} ({{ $item->jenis_alat }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('inventaris_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select
                                        class="form-select @error('status') is-invalid @enderror"
                                        id="status"
                                        name="status"
                                    >
                                        <option value="active" {{ old('status', $labInventaris->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="moved" {{ old('status', $labInventaris->status) == 'moved' ? 'selected' : '' }}>Moved</option>
                                        <option value="inactive" {{ old('status', $labInventaris->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_series" class="form-label">No Series</label>
                                    <input
                                        type="text"
                                        class="form-control @error('no_series') is-invalid @enderror"
                                        id="no_series"
                                        name="no_series"
                                        value="{{ old('no_series', $labInventaris->no_series) }}"
                                        placeholder="Nomor seri atau kode tambahan"
                                    >
                                    @error('no_series')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_penempatan" class="form-label">Tanggal Penempatan</label>
                                    <input
                                        type="date"
                                        class="form-control @error('tanggal_penempatan') is-invalid @enderror"
                                        id="tanggal_penempatan"
                                        name="tanggal_penempatan"
                                        value="{{ old('tanggal_penempatan', $labInventaris->tanggal_penempatan?->format('Y-m-d')) }}"
                                    >
                                    @error('tanggal_penempatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_penghapusan" class="form-label">Tanggal Penghapusan</label>
                                    <input
                                        type="date"
                                        class="form-control @error('tanggal_penghapusan') is-invalid @enderror"
                                        id="tanggal_penghapusan"
                                        name="tanggal_penghapusan"
                                        value="{{ old('tanggal_penghapusan', $labInventaris->tanggal_penghapusan?->format('Y-m-d')) }}"
                                    >
                                    @error('tanggal_penghapusan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea
                                        class="form-control @error('keterangan') is-invalid @enderror"
                                        id="keterangan"
                                        name="keterangan"
                                        rows="3"
                                        placeholder="Tambahkan keterangan tambahan"
                                    >{{ old('keterangan', $labInventaris->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('labs.inventaris.index', $labInventaris->encrypted_lab_id) }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
