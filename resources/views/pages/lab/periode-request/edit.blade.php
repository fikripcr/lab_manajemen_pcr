@extends('layouts.admin.app')

@section('header')
    <x-tabler.page-header :title="'Edit Periode: ' . $period->nama_periode" pretitle="Software Request">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.periode-request.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('lab.periode-request.update', encryptId($period->periodsoftreq_id)) }}" method="POST" class="card ajax-form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <x-tabler.flash-message />

                    <div class="mb-3">
                        <label class="form-label required">Semester</label>
                        <select name="semester_id" class="form-select @error('semester_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Semester --</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->semester_id }}" {{ old('semester_id', $period->semester_id) == $semester->semester_id ? 'selected' : '' }}>
                                    {{ $semester->tahun_ajaran }} - {{ $semester->semester }}
                                </option>
                            @endforeach
                        </select>
                        @error('semester_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Nama Periode</label>
                        <input type="text" name="nama_periode" class="form-control @error('nama_periode') is-invalid @enderror" value="{{ old('nama_periode', $period->nama_periode) }}" placeholder="Misal: Periode Ganjil 2024/2025" required>
                        @error('nama_periode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $period->start_date instanceof \DateTime ? $period->start_date->format('Y-m-d') : $period->start_date) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $period->end_date instanceof \DateTime ? $period->end_date->format('Y-m-d') : $period->end_date) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $period->is_active) ? 'checked' : '' }}>
                            <span class="form-check-label">Set as Active Period</span>
                        </label>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Update Periode</button>
                    <x-tabler.button type="cancel" :href="route('lab.periode-request.index')" />
                </div>
            </form>
        </div>
    </div>
@endsection
