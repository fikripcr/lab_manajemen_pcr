@extends('layouts.admin.app')

@section('header')
    <x-sys.page-header :title="'Create ' . ucfirst($type)" :pretitle="ucfirst($type)">
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
                    <x-tabler.flash-message />

                    <form action="{{ route($type . '.store') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="judul">Title</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                       id="judul" name="judul" value="{{ old('judul') }}" required>
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="isi">Content</label>
                            <div class="col-sm-10">
                                <x-tabler.editor id="isi" name="isi" :value="old('isi')" height="400" />
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
                                <div class="form-hint">Upload a cover image for this {{ strtolower($type) }}.</div>
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
                                <div class="form-hint">Upload related attachments/files for this {{ strtolower($type) }}.</div>
                                @error('attachments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <label class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                                    <span class="form-check-label">Publish {{ ucfirst($type) }}</span>
                                </label>
                                @error('is_published')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <input type="hidden" name="jenis" value="{{ $type }}">

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-sys.button type="submit" text="Create" />
                                <x-sys.button type="cancel" :href="route($type . '.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
