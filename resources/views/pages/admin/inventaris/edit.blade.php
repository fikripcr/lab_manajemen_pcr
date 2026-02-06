@extends('layouts.admin.app')

@section('header')
    <x-sys.page-header title="Edit Inventory" pretitle="Inventory">
        <x-slot:actions>
            <x-sys.button type="back" :href="route('inventaris.index')" />
        </x-slot:actions>
    </x-sys.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-admin.flash-message />

                    <form action="{{ route('inventaris.update', $inventory) }}" method="POST" class="ajax-form">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="lab_id">Lab</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('lab_id') is-invalid @enderror"
                                        id="lab_id" name="lab_id" required>
                                    <option value="">Select Lab</option>
                                    @foreach($labs as $lab)
                                    <option value="{{ $lab->lab_id }}" {{ old('lab_id', $inventory->lab_id) == $lab->lab_id ? 'selected' : '' }}>
                                        {{ $lab->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('lab_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="nama_alat">Equipment Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nama_alat') is-invalid @enderror"
                                       id="nama_alat" name="nama_alat" value="{{ old('nama_alat', $inventory->nama_alat) }}"
                                       placeholder="e.g., Laptop, Microscope, etc." required>
                                @error('nama_alat')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="jenis_alat">Type</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('jenis_alat') is-invalid @enderror"
                                       id="jenis_alat" name="jenis_alat" value="{{ old('jenis_alat', $inventory->jenis_alat) }}"
                                       placeholder="e.g., Electronic, Chemical, Equipment" required>
                                @error('jenis_alat')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="kondisi_terakhir">Condition</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('kondisi_terakhir') is-invalid @enderror"
                                        id="kondisi_terakhir" name="kondisi_terakhir" required>
                                    <option value="">Select Condition</option>
                                    <option value="Baik" {{ old('kondisi_terakhir', $inventory->kondisi_terakhir) == 'Baik' ? 'selected' : '' }}>Good</option>
                                    <option value="Rusak Ringan" {{ old('kondisi_terakhir', $inventory->kondisi_terakhir) == 'Rusak Ringan' ? 'selected' : '' }}>Minor Damage</option>
                                    <option value="Rusak Berat" {{ old('kondisi_terakhir', $inventory->kondisi_terakhir) == 'Rusak Berat' ? 'selected' : '' }}>Major Damage</option>
                                    <option value="Tidak Dapat Digunakan" {{ old('kondisi_terakhir', $inventory->kondisi_terakhir) == 'Tidak Dapat Digunakan' ? 'selected' : '' }}>Cannot Be Used</option>
                                </select>
                                @error('kondisi_terakhir')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="tanggal_pengecekan">Last Check Date</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control @error('tanggal_pengecekan') is-invalid @enderror"
                                       id="tanggal_pengecekan" name="tanggal_pengecekan"
                                       value="{{ old('tanggal_pengecekan', $inventory->tanggal_pengecekan ? $inventory->tanggal_pengecekan->format('Y-m-d') : '') }}" required>
                                @error('tanggal_pengecekan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-sys.button type="submit" text="Update Inventory" />
                                <x-sys.button type="cancel" :href="route('inventaris.index')" />
                            </div>
                        </div>
                    </form>
@endsection
