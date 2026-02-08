@extends('layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Tambah Lab Baru" pretitle="Laboratorium">
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

                    <form action="{{ route('lab.labs.store') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="name">Lab Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}"
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
                                       id="location" name="location" value="{{ old('location') }}"
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
                                       id="capacity" name="capacity" value="{{ old('capacity') }}"
                                       placeholder="30" min="1" required>
                                @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="description">Description</label>
                            <div class="col-sm-10">
                                <x-tabler.editor id="description" name="description" :value="old('description')" height="300" />
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Media Upload Section -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Lab Images</label>
                            <div class="col-sm-10">
                                <input type="file" class="filepond-input" 
                                       id="lab_images" name="lab_images[]" multiple 
                                       data-allow-multiple="true" accept="image/*">
                                <div class="form-hint">Upload photos of the lab (multiple allowed).</div>
                                @error('lab_images')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Attachments</label>
                            <div class="col-sm-10">
                                <input type="file" class="filepond-input" 
                                       id="lab_attachments" name="lab_attachments[]" multiple 
                                       data-allow-multiple="true">
                                <div class="form-hint">Upload documents or other attachments (multiple allowed).</div>
                                @error('lab_attachments')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" text="Tambah Lab" />
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
                    labelIdle: 'Drag & Drop lab photos',
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
                    labelIdle: 'Drag & Drop documents',
                });
            }
        }
    });
</script>
@endpush
