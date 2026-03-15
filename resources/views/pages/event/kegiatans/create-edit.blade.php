@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $kegiatan->exists ? 'Edit Kegiatan' : 'Tambah Kegiatan' }}" pretitle="Kegiatan">
    <x-slot:actions>
        <x-tabler.button href="{{ route('Kegiatan.Kegiatans.index') }}" type="back" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <div class="row justify-content-center">
            <div class="col-md-10">
                <x-tabler.card>
                    <form class="ajax-form" action="{{ $kegiatan->exists ? route('Kegiatan.Kegiatans.update', $kegiatan->encrypted_event_id) : route('Kegiatan.Kegiatans.store') }}" method="POST">
                        @csrf
                        @if($kegiatan->exists) @method('PUT') @endif
                        <x-tabler.card-body>
                            
                            <div class="mb-3">
                                <x-tabler.form-input 
                                    name="judul_kegiatan" 
                                    label="Judul Kegiatan" 
                                    placeholder="Masukkan judul Kegiatan"
                                    value="{{ $kegiatan->judul_kegiatan }}"
                                    required="true"
                                />
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="jenis_kegiatan" 
                                        label="Jenis Kegiatan" 
                                        placeholder="Contoh: Seminar, Workshop, Lomba"
                                        value="{{ $kegiatan->jenis_kegiatan }}"
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
                                            <option value="{{ $user->id }}" {{ $kegiatan->pic_user_id == $user->id ? 'selected' : '' }}>
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
                                        value="{{ $kegiatan->tanggal_mulai?->format('Y-m-d') }}"
                                        required="true"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-tabler.form-input 
                                        name="tanggal_selesai" 
                                        label="Tanggal Selesai (Opsional)" 
                                        type="date"
                                        value="{{ $kegiatan->tanggal_selesai?->format('Y-m-d') }}"
                                    />
                                </div>
                            </div>

                            <div class="mt-3">
                                <x-tabler.form-input 
                                    name="lokasi" 
                                    label="Lokasi" 
                                    placeholder="Tempat pelaksanaan Kegiatan"
                                    value="{{ $kegiatan->lokasi }}"
                                />
                            </div>

                            <div class="mt-3">
                                <x-tabler.form-textarea 
                                    name="deskripsi" 
                                    label="Deskripsi Kegiatan"
                                    placeholder="Keterangan singkat mengenai Kegiatan"
                                    rows="5"
                                >{{ $kegiatan->deskripsi }}</x-tabler.form-textarea>
                            </div>
                        </x-tabler.card-body>
                        <x-tabler.card-footer class="text-end">
                            <x-tabler.button type="submit" text="Simpan Kegiatan" class="btn-primary" />
                        </x-tabler.card-footer>
                    </form>
                </x-tabler.card>
            </div>
        </div>
@endsection
