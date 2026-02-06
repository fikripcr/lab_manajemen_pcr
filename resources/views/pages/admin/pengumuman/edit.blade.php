@extends('layouts.admin.app')

@section('header')
    <x-sys.page-header :title="'Edit ' . ucfirst($type)" pretitle="Announcement">
        <x-slot:actions>
            <x-sys.button type="back" :href="route($type . '.index')" />
        </x-slot:actions>
    </x-sys.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-admin.flash-message />

                    <form action="{{ route($type . '.update', $pengumuman->encrypted_pengumuman_id) }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="judul">Title</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                       id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required>
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="isi">Content</label>
                            <div class="col-sm-10">
                                <x-admin.editor id="isi" name="isi" :value="old('isi', $pengumuman->isi)" height="400" />
                                @error('isi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="cover_image">Cover Image</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                                       id="cover_image" name="cover_image" accept="image/*">
                                <div class="form-hint text-muted">Upload a new cover image to replace the current one.</div>
                                
                                @if ($pengumuman->hasMedia('info_cover'))
                                    <div class="mt-2">
                                        <img src="{{ $pengumuman->getFirstMediaUrl('info_cover', 'medium') }}" alt="Current Cover" class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                @endif

                                @error('cover_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="attachments">Attachments</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control @error('attachments') is-invalid @enderror" 
                                       id="attachments" name="attachments[]" multiple accept="*/*">
                                <div class="form-hint text-muted">Upload additional files (current ones will be kept).</div>

                                @if ($pengumuman->hasMedia('info_attachment'))
                                    <div class="mt-2">
                                        <ul class="list-group list-group-flush border rounded">
                                            @foreach ($pengumuman->getMedia('info_attachment') as $attachment)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>{{ $attachment->file_name }}</span>
                                                    <a href="{{ $attachment->getUrl() }}" class="btn btn-sm btn-ghost-secondary" target="_blank">View</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @error('attachments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <label class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published', $pengumuman->is_published) ? 'checked' : '' }}>
                                    <span class="form-check-label">Publish {{ ucfirst($type) }}</span>
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="jenis" value="{{ $type }}">

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-sys.button type="submit" :text="'Update ' . ucfirst($type)" />
                                <x-sys.button type="cancel" :href="route($type . '.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
