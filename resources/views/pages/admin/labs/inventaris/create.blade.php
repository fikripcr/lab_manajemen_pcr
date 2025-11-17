@extends('layouts.admin.app')

@section('title', 'Tambah Inventaris ke Lab: ' . $lab->name)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Tambah Inventaris ke Lab: {{ $lab->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('labs.inventaris.store', $lab->lab_id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="inventaris_id">Pilih Inventaris</label>
                            <select class="form-select @error('inventaris_id') is-invalid @enderror" id="inventaris_id" name="inventaris_id" required>
                                <option value="">-- Pilih Inventaris --</option>
                                @foreach($inventarisOptions as $inventaris)
                                    <option value="{{ $inventaris->inventaris_id }}" {{ old('inventaris_id') == $inventaris->inventaris_id ? 'selected' : '' }}>
                                        {{ $inventaris->nama_alat }} ({{ $inventaris->jenis_alat }})
                                    </option>
                                @endforeach
                            </select>
                            @error('inventaris_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="no_series">No Series (Opsional)</label>
                            <input type="text" class="form-control @error('no_series') is-invalid @enderror" id="no_series" name="no_series" value="{{ old('no_series') }}" placeholder="Masukkan nomor seri atau kode tambahan">
                            @error('no_series')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="keterangan">Keterangan (Opsional)</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" placeholder="Tambahkan keterangan tambahan">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('labs.inventaris.index', $lab->lab_id) }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection