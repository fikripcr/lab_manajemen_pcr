@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Create New Inventory</h4>

    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-body">
                    <x-admin.flash-message />

                    <form action="{{ route('inventaris.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="lab_id">Lab</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('lab_id') is-invalid @enderror"
                                        id="lab_id" name="lab_id" >
                                    <option value="">Select Lab</option>
                                    @foreach($labs as $lab)
                                    <option value="{{ $lab->lab_id }}" {{ old('lab_id') == $lab->lab_id ? 'selected' : '' }}>
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
                            <label class="col-sm-2 col-form-label" for="nama_alat">Equipment Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nama_alat') is-invalid @enderror"
                                       id="nama_alat" name="nama_alat" value="{{ old('nama_alat') }}"
                                       placeholder="e.g., Laptop, Microscope, etc." >
                                @error('nama_alat')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="jenis_alat">Type</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('jenis_alat') is-invalid @enderror"
                                       id="jenis_alat" name="jenis_alat" value="{{ old('jenis_alat') }}"
                                       placeholder="e.g., Electronic, Chemical, Equipment" >
                                @error('jenis_alat')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="kondisi_terakhir">Condition</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('kondisi_terakhir') is-invalid @enderror"
                                        id="kondisi_terakhir" name="kondisi_terakhir" >
                                    <option value="">Select Condition</option>
                                    <option value="Baik" {{ old('kondisi_terakhir') == 'Baik' ? 'selected' : '' }}>Good</option>
                                    <option value="Rusak Ringan" {{ old('kondisi_terakhir') == 'Rusak Ringan' ? 'selected' : '' }}>Minor Damage</option>
                                    <option value="Rusak Berat" {{ old('kondisi_terakhir') == 'Rusak Berat' ? 'selected' : '' }}>Major Damage</option>
                                    <option value="Tidak Dapat Digunakan" {{ old('kondisi_terakhir') == 'Tidak Dapat Digunakan' ? 'selected' : '' }}>Cannot Be Used</option>
                                </select>
                                @error('kondisi_terakhir')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="tanggal_pengecekan">Last Check Date</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control @error('tanggal_pengecekan') is-invalid @enderror"
                                       id="tanggal_pengecekan" name="tanggal_pengecekan"
                                       value="{{ old('tanggal_pengecekan') ?? date('Y-m-d') }}" >
                                @error('tanggal_pengecekan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i> Create Inventory
                                </button>
                                <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-arrow-back me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
