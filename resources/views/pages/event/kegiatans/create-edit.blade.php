@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="{{ $Kegiatan->exists ? 'Edit Kegiatan' : 'Tambah Kegiatan' }}" pretitle="Kegiatan">
    <x-slot:actions>
        <x-tabler.button href="{{ route('Kegiatan.Kegiatans.index') }}" class="btn-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <form class="ajax-form" action="{{ $Kegiatan->exists ? route('Kegiatan.Kegiatans.update', $Kegiatan->hashid) : route('Kegiatan.Kegiatans.store') }}" method="POST">
                        @csrf
                        @if($Kegiatan->exists) @method('PUT') @endif
                        <div class="card-body">
                            <x-tabler.flash-message />
                            
                            <div class="mb-3">
                                <x-tabler.form-input 
                                    name="judul_Kegiatan" 
                                    label="Judul Kegiatan" 
                                    placeholder="Masukkan judul Kegiatan"
                                    value="{{ $Kegiatan->judul_Kegiatan }}"
                                    required="true"
                                />
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="jenis_Kegiatan" 
                                        label="Jenis Kegiatan" 
                                        placeholder="Contoh: Seminar, Workshop, Lomba"
                                        value="{{ $Kegiatan->jenis_Kegiatan }}"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-tabler.form-select 
                                        name="pic_user_id" 
                                        label="PIC User"
                                        placeholder="Pilih PIC"
                                    >
                                        <option value="">-- Pilih PIC --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $Kegiatan->pic_user_id == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </x-tabler.form-select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="tanggal_mulai" 
                                        label="Tanggal Mulai" 
                                        type="date"
                                        value="{{ $Kegiatan->tanggal_mulai?->format('Y-m-d') }}"
                                        required="true"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="tanggal_selesai" 
                                        label="Tanggal Selesai (Opsional)" 
                                        type="date"
                                        value="{{ $Kegiatan->tanggal_selesai?->format('Y-m-d') }}"
                                    />
                                </div>
                            </div>

                            <div class="mt-3">
                                <x-tabler.form-input 
                                    name="lokasi" 
                                    label="Lokasi" 
                                    placeholder="Tempat pelaksanaan Kegiatan"
                                    value="{{ $Kegiatan->lokasi }}"
                                />
                            </div>

                            <div class="mt-3">
                                <x-tabler.form-textarea 
                                    name="deskripsi" 
                                    label="Deskripsi Kegiatan"
                                    placeholder="Keterangan singkat mengenai Kegiatan"
                                    rows="5"
                                >{{ $Kegiatan->deskripsi }}</x-tabler.form-textarea>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <x-tabler.button type="submit" text="Simpan Kegiatan" class="btn-primary" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
