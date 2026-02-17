@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Edit Entitas Terkait
            </h2>
            <div class="text-muted mt-1">Kegiatan / Meeting / Entitas / Edit</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('Kegiatan.rapat.show', $entitas->rapat) }}" class="btn btn-secondary d-none d-sm-inline-block">
                    <i class="ti ti-arrow-left me-1"></i>
                    Kembali ke Detail Rapat
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Data Entitas</h3>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <form class="ajax-form" action="{{ route('Kegiatan.rapat.entitas.update', $entitas) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="model" 
                            label="Model Entitas" 
                            type="text" 
                            value="{{ old('model', $entitas->model) }}"
                            placeholder="Departemen, Proyek, dll" 
                            required="true" 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="model_id" 
                            label="ID Entitas" 
                            type="number" 
                            value="{{ old('model_id', $entitas->model_id) }}"
                            placeholder="Masukkan ID entitas" 
                            required="true" 
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <x-tabler.form-textarea 
                            name="keterangan" 
                            label="Keterangan" 
                            value="{{ old('keterangan', $entitas->keterangan) }}"
                            placeholder="Masukkan keterangan" 
                            rows="3" 
                        />
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('Kegiatan.rapat.show', $entitas->rapat) }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Batal
                    </a>
                    <x-tabler.button type="submit" text="Simpan Perubahan" />
                </div>
            </form>
        </div>
    </div>
@endsection
