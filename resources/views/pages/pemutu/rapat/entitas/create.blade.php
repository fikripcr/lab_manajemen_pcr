@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Tambah Entitas Terkait
            </h2>
            <div class="text-muted mt-1">Pemutu / Meeting / Entitas / Tambah</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('pemutu.rapat.show', $rapat) }}" class="btn btn-secondary d-none d-sm-inline-block">
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
            <h3 class="card-title">Form Tambah Entitas Terkait</h3>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <form class="ajax-form" action="{{ route('pemutu.rapat.entitas.store', $rapat) }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="model" 
                            label="Model Entitas" 
                            type="text" 
                            value="{{ old('model') }}"
                            placeholder="Departemen, Proyek, dll" 
                            required="true" 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="model_id" 
                            label="ID Entitas" 
                            type="number" 
                            value="{{ old('model_id') }}"
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
                            value="{{ old('keterangan') }}"
                            placeholder="Masukkan keterangan" 
                            rows="3" 
                        />
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('pemutu.rapat.show', $rapat) }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Batal
                    </a>
                    <x-tabler.button type="submit" text="Simpan Entitas" />
                </div>
            </form>
        </div>
    </div>
@endsection
