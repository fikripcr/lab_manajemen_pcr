@extends('layouts.admin.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Edit Peserta Rapat
            </h2>
            <div class="text-muted mt-1">Pemutu / Meeting / Peserta / Edit</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('pemutu.rapat.show', $peserta->rapat) }}" class="btn btn-secondary d-none d-sm-inline-block">
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
            <h3 class="card-title">Edit Data Peserta</h3>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <form class="ajax-form" action="{{ route('pemutu.rapat.peserta.update', $peserta) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <x-tabler.form-select 
                            name="user_id" 
                            label="Peserta" 
                            type="select2" 
                            :options="$users->pluck('name', 'id')->toArray()"
                            :selected="old('user_id', $peserta->user_id)" 
                            placeholder="Pilih peserta" 
                            required="true" 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-tabler.form-input 
                            name="jabatan" 
                            label="Jabatan" 
                            type="text" 
                            value="{{ old('jabatan', $peserta->jabatan) }}"
                            placeholder="Masukkan jabatan" 
                            required="true" 
                        />
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('pemutu.rapat.show', $peserta->rapat) }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Batal
                    </a>
                    <x-tabler.button type="submit" text="Simpan Perubahan" />
                </div>
            </form>
        </div>
    </div>
@endsection
