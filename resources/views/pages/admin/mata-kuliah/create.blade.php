@extends('layouts.admin.app')

@section('header')
    <x-sys.page-header title="Create New Mata Kuliah" pretitle="Mata Kuliah">
        <x-slot:actions>
            <x-sys.button type="back" :href="route('mata-kuliah.index')" />
        </x-slot:actions>
    </x-sys.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form method="POST" action="{{ route('mata-kuliah.store') }}" class="ajax-form">
                        @csrf

                        <div class="row mb-3">
                            <label for="kode_mk" class="col-sm-2 col-form-label required">Kode MK</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('kode_mk') is-invalid @enderror"
                                       id="kode_mk" name="kode_mk"
                                       value="{{ old('kode_mk') }}"
                                       placeholder="e.g. IF101" required>
                                @error('kode_mk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nama_mk" class="col-sm-2 col-form-label required">Nama MK</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nama_mk') is-invalid @enderror"
                                       id="nama_mk" name="nama_mk"
                                       value="{{ old('nama_mk') }}"
                                       placeholder="e.g. Pemrograman Web" required>
                                @error('nama_mk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="sks" class="col-sm-2 col-form-label required">SKS</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control @error('sks') is-invalid @enderror"
                                       id="sks" name="sks"
                                       value="{{ old('sks', 3) }}"
                                       min="1" max="6" required>
                                @error('sks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-sys.button type="submit" text="Save" />
                                <x-sys.button type="cancel" :href="route('mata-kuliah.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
