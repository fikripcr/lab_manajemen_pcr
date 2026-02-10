@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Edit Lab" pretitle="Laboratorium">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.labs.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form action="{{ route('lab.labs.update', $lab->encrypted_lab_id) }}" method="POST" enctype="multipart/form-data" class="ajax-form">
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
                                <x-tabler.editor id="description" name="description" :value="old('description', $lab->description)" height="300" />
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

                        <!-- Existing Attachments Section -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Current Attachments</label>
                            <div class="col-sm-10">
                                 @if ($lab->getMedia('lab_attachments')->count() > 0)
                                    <ul class="list-group">
                                        @foreach ($lab->getMedia('lab_attachments') as $media)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>{{ $media->file_name }}</span>
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-ghost-secondary">Download</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted italic">No attachments uploaded yet.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Media Upload Section -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Upload New Images</label>
                            <div class="col-sm-10">
                                <input type="file" class="filepond-input" 
                                       id="lab_images" name="lab_images[]" multiple 
                                       data-allow-multiple="true" accept="image/*">
                                <div class="form-hint">Upload new photos to add to this lab.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Upload New Attachments</label>
                            <div class="col-sm-10">
                                <input type="file" class="filepond-input" 
                                       id="lab_attachments" name="lab_attachments[]" multiple 
                                       data-allow-multiple="true">
                                <div class="form-hint">Upload new documents to add to this lab.</div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" text="Perbarui Lab" />
                                <x-tabler.button type="cancel" :href="route('lab.labs.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        if (typeof window.loadFilePond === 'function') {
            const FilePond = await window.loadFilePond();

            // Lab Images
            const imagesInput = document.querySelector('#lab_images');
            if(imagesInput) {
                FilePond.create(imagesInput, {
                    storeAsFile: true,
                    allowMultiple: true,
                    labelIdle: 'Drag & Drop new lab photos',
                    acceptedFileTypes: ['image/*'],
                    imagePreviewHeight: 150,
                });
            }

            // Lab Attachments
            const attachmentsInput = document.querySelector('#lab_attachments');
            if(attachmentsInput) {
                FilePond.create(attachmentsInput, {
                    storeAsFile: true,
                    allowMultiple: true,
                    labelIdle: 'Drag & Drop new documents',
                });
            }
        }
    });
</script>
@endpush
