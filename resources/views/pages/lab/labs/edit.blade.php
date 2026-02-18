@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Ubah Lab" pretitle="Laboratorium">
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

                        <x-tabler.form-input name="name" label="Lab Name" value="{{ $lab->name }}" placeholder="Computer Lab A" required />

                        <x-tabler.form-input name="location" label="Location" value="{{ $lab->location }}" placeholder="Building A, Floor 2" required />

                        <x-tabler.form-input type="number" name="capacity" label="Capacity" value="{{ $lab->capacity }}" placeholder="30" min="1" required />

                        <x-tabler.form-textarea type="editor" id="description" name="description" label="Description" :value="old('description', $lab->description)" height="300" />

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
                                                            <x-tabler.button type="button" class="btn-icon btn-sm btn-ghost-primary" href="{{ $media->getUrl() }}" target="_blank" title="View" icon="ti ti-eye" />
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
                                                <x-tabler.button type="button" class="btn-sm btn-ghost-secondary" href="{{ $media->getUrl() }}" target="_blank" text="Download" />
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted italic">No attachments uploaded yet.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Media Upload Section -->
                        <x-tabler.form-input type="file" name="lab_images[]" id="lab_images" label="Upload New Images" class="filepond-input" multiple data-allow-multiple="true" accept="image/*" help="Upload new photos to add to this lab." />

                        <x-tabler.form-input type="file" name="lab_attachments[]" id="lab_attachments" label="Upload New Attachments" class="filepond-input" multiple data-allow-multiple="true" help="Upload new documents to add to this lab." />

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" />
                                <x-tabler.button type="cancel" :href="route('lab.labs.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
