@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header :title="'Create ' . ucfirst($type)" :pretitle="ucfirst($type)">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.'.$type . '.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form action="{{ route('lab.'.$type . '.store') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="judul">Title</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input name="judul" value="{{ old('judul') }}" required class="mb-0" />
                            </div>
                        </div>

                            <div class="col-sm-10">
                                <x-tabler.form-textarea type="editor" id="isi" name="isi" label="Content" :value="old('isi')" height="400" required="true" class="mb-0" />
                            </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="cover_image">Cover Image</label>
                            <div class="col-sm-10">
                                <input type="file" class="filepond-input" 
                                       id="cover_image" name="cover" accept="image/*"
                                       data-max-files="1">
                                <div class="form-hint">Upload a cover image for this {{ strtolower($type) }}.</div>
                                @error('cover')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="attachments">Attachments</label>
                            <div class="col-sm-10">
                                <input type="file" class="filepond-input" 
                                       id="attachments" name="attachments[]" multiple 
                                       data-allow-multiple="true">
                                <div class="form-hint">Upload related attachments/files for this {{ strtolower($type) }}.</div>
                                @error('attachments')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
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
                                <x-tabler.button type="submit" text="Simpan" />
                                <x-tabler.button type="cancel" :href="route('lab.'.$type . '.index')" />
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
            
            // Register plugins if needed (loadFilePond already registers ImagePreview)
            
            // Initialize Cover Image (Single)
            const coverInput = document.querySelector('#cover_image');
            if(coverInput) {
                FilePond.create(coverInput, {
                    storeAsFile: true,
                    labelIdle: 'Drag & Drop your cover image or <span class="filepond--label-action">Browse</span>',
                    acceptedFileTypes: ['image/*'],
                    imagePreviewHeight: 170,
                    styleLoadIndicatorPosition: 'center bottom',
                    styleProcessIndicatorPosition: 'right bottom',
                    styleButtonRemoveItemPosition: 'left bottom',
                    styleButtonProcessItemPosition: 'right bottom',
                });
            }

            // Initialize Attachments (Multiple)
            const attachmentInput = document.querySelector('#attachments');
            if(attachmentInput) {
                FilePond.create(attachmentInput, {
                    storeAsFile: true,
                    allowMultiple: true,
                    labelIdle: 'Drag & Drop files or <span class="filepond--label-action">Browse</span>',
                });
            }
        }
    });
</script>
@endpush
