@extends('layouts.admin.app')

@section('header')
    <x-sys.page-header title="Edit Lab" pretitle="Laboratorium">
        <x-slot:actions>
            <x-sys.button type="back" :href="route('labs.index')" />
        </x-slot:actions>
    </x-sys.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-admin.flash-message />

                    <form action="{{ route('labs.update', $lab->encrypted_lab_id) }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="name">Lab Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $lab->name) }}"
                                       placeholder="Computer Lab A" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="location">Location</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                       id="location" name="location" value="{{ old('location', $lab->location) }}"
                                       placeholder="Building A, Floor 2" required>
                                @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="capacity">Capacity</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                       id="capacity" name="capacity" value="{{ old('capacity', $lab->capacity) }}"
                                       placeholder="30" min="1" required>
                                @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="description">Description</label>
                            <div class="col-sm-10">
                                <x-admin.editor id="description" name="description" :value="old('description', $lab->description)" height="300" />
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Existing Media Section -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Current Images</label>
                            <div class="col-sm-10">
                                 @if ($lab->getMedia('lab_images')->count() > 0)
                                    <div class="row g-3">
                                        @foreach ($lab->getMedia('lab_images') as $media)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="card h-100 shadow-none border">
                                                    <img src="{{ $media->getUrl() }}" class="card-img-top" alt="{{ $media->name }}" style="height: 150px; object-fit: cover;">
                                                    <div class="card-footer bg-transparent p-2 d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">{{ round($media->size / 1024, 2) }} KB</small>
                                                        <div class="btn-group">
                                                            <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-icon btn-sm btn-ghost-primary" title="View">
                                                                <i class="ti ti-eye"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted italic">No images uploaded yet.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Media Upload Section -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Upload New Images</label>
                            <div class="col-sm-10">
                                <div class="border rounded p-3 bg-light-lt">
                                    <div id="media-upload-section">
                                        <div class="mb-3">
                                            <label class="form-label">Select Images</label>
                                            <input type="file" class="form-control" name="lab_images[]" multiple accept="image/*" id="lab_images_input">
                                            <small class="text-muted">You can select multiple images to upload</small>
                                        </div>

                                        <div id="media-files-container">
                                            <!-- Previews will be added here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-sys.button type="submit" text="Perbarui Lab" />
                                <x-sys.button type="cancel" :href="route('labs.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('lab_images_input');
            const container = document.getElementById('media-files-container');

            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    container.innerHTML = ''; // Clear previous entries

                    if (this.files.length > 0) {
                        Array.from(this.files).forEach((file, index) => {
                            if (!file.type.match('image.*')) return; // Only process images

                            const mediaFormHtml = `
                                <div class="card mb-2 border shadow-none">
                                    <div class="card-body p-2">
                                        <div class="row align-items-center g-3">
                                            <div class="col-auto">
                                                <img src="${URL.createObjectURL(file)}" class="rounded" alt="Preview" style="width: 80px; height: 60px; object-fit: cover;">
                                            </div>
                                            <div class="col">
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control form-control-sm" name="media_titles[]" placeholder="Image Title" value="${file.name.replace(/\.[^/.]+$/, '')}" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control form-control-sm" name="media_descriptions[]" placeholder="Description" />
                                                    </div>
                                                </div>
                                                <div class="mt-1 small text-muted">Size: ${(file.size/1024).toFixed(2)} KB</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                            container.insertAdjacentHTML('beforeend', mediaFormHtml);
                        });
                    }
                });
            }
        });
    </script>
@endsection
