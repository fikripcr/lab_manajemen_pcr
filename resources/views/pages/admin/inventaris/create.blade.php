@extends('layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Create New Inventory" pretitle="Inventory">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('inventaris.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form action="{{ route('inventaris.store') }}" method="POST" class="ajax-form">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="lab_id">Lab</label>
                            <div class="col-sm-10">
                                <x-form.select2
                                    id="lab_id"
                                    name="lab_id"
                                    :options="$labs->pluck('name', 'lab_id')->toArray()"
                                    :selected="old('lab_id')"
                                    placeholder="Select Lab"
                                    required="true"
                                />
                                @error('lab_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="nama_alat">Equipment Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nama_alat') is-invalid @enderror"
                                       id="nama_alat" name="nama_alat" value="{{ old('nama_alat') }}"
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
                                       id="jenis_alat" name="jenis_alat" value="{{ old('jenis_alat') }}"
                                       placeholder="e.g., Electronic, Chemical, Equipment" required>
                                @error('jenis_alat')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="kondisi_terakhir">Condition</label>
                            <div class="col-sm-10">
                                <x-form.select2
                                    id="kondisi_terakhir"
                                    name="kondisi_terakhir"
                                    :options="[
                                        'Baik' => 'Good',
                                        'Rusak Ringan' => 'Minor Damage',
                                        'Rusak Berat' => 'Major Damage',
                                        'Tidak Dapat Digunakan' => 'Cannot Be Used'
                                    ]"
                                    :selected="old('kondisi_terakhir')"
                                    placeholder="Select Condition"
                                    required="true"
                                />
                                @error('kondisi_terakhir')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="tanggal_pengecekan">Last Check Date</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control @error('tanggal_pengecekan') is-invalid @enderror"
                                       id="tanggal_pengecekan" name="tanggal_pengecekan"
                                       value="{{ old('tanggal_pengecekan') ?? date('Y-m-d') }}" required>
                                @error('tanggal_pengecekan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" text="Create Inventory" />
                                <x-tabler.button type="cancel" :href="route('inventaris.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

