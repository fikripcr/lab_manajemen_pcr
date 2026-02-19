@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header :title="$pengumuman->exists ? 'Edit ' . ucfirst($type) : 'Buat ' . ucfirst($type) . ' Baru'" :pretitle="ucfirst($type)">
    <x-slot:actions>
        <a href="javascript:void(0)" onclick="history.back()" class="btn btn-secondary d-none d-sm-inline-block">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <form action="{{ $pengumuman->exists ? route('lab.pengumuman.update', $pengumuman->encrypted_pengumuman_id) : route('lab.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($pengumuman->exists)
                @method('PUT')
            @endif
            <input type="hidden" name="jenis" value="{{ $type }}">

            <div class="row row-cards">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <x-tabler.form-input
                                name="judul"
                                label="Judul"
                                :value="$pengumuman->judul"
                                placeholder="Masukkan judul..."
                                required
                            />

                            <div class="mb-3">
                                <x-tabler.form-textarea
                                    name="isi"
                                    label="Konten"
                                    type="editor"
                                    rows="20"
                                    :value="$pengumuman->isi"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-selectgroup">
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="is_published" value="1" class="form-selectgroup-input" {{ old('is_published', $pengumuman->is_published ?? false) ? 'checked' : '' }}>
                                        <span class="form-selectgroup-label text-success">
                                            <i class="ti ti-check me-1"></i> Published
                                        </span>
                                    </label>
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="is_published" value="0" class="form-selectgroup-input" {{ !old('is_published', $pengumuman->is_published ?? false) ? 'checked' : '' }}>
                                        <span class="form-selectgroup-label text-warning">
                                            <i class="ti ti-file me-1"></i> Draft
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-input
                                    name="cover"
                                    type="file"
                                    label="Gambar Utama (Cover)"
                                    accept="image/*"
                                    help="Maksimal 5MB. Format: JPG, PNG, WEBP."
                                />
                                @if($pengumuman->cover_url)
                                    <div class="mt-2">
                                        <img src="{{ $pengumuman->cover_url }}" class="rounded" style="max-height: 100px;">
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-input
                                    name="attachments[]"
                                    type="file"
                                    label="File Pendukung (Lampiran)"
                                    multiple
                                    help="Maksimal 10MB per file. Bisa upload banyak file sekaligus."
                                />
                            </div>

                            {{-- Penulis Option if needed, user didn't explicitly ask but it was in controller --}}
                            {{-- Ignoring for now as it defaults to auth user usually, checking controller store/update --}}

                            <div class="mt-4">
                                <x-tabler.button
                                    type="submit"
                                    class="w-100 btn-primary"
                                    icon="ti ti-device-floppy"
                                    text="Simpan"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
