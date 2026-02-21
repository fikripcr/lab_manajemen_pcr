<x-tabler.form-modal
    id_form="{{ $lab->exists ? 'editLabForm' : 'createLabForm' }}"
    title="{{ $lab->exists ? 'Ubah Lab' : 'Tambah Lab Baru' }}"
    route="{{ $lab->exists ? route('lab.labs.update', $lab->encrypted_lab_id) : route('lab.labs.store') }}"
    method="{{ $lab->exists ? 'PUT' : 'POST' }}"
    enctype="multipart/form-data"
>
    <x-tabler.form-input 
        name="name" 
        label="Lab Name" 
        value="{{ old('name', $lab->name) }}" 
        placeholder="Computer Lab A" 
        required 
    />

    <x-tabler.form-input 
        name="location" 
        label="Location" 
        value="{{ old('location', $lab->location) }}" 
        placeholder="Building A, Floor 2" 
        required 
    />

    <x-tabler.form-input 
        type="number" 
        name="capacity" 
        label="Capacity" 
        value="{{ old('capacity', $lab->capacity) }}" 
        placeholder="30" 
        min="1" 
        required 
    />

    <x-tabler.form-textarea 
        type="editor" 
        id="{{ $lab->exists ? 'description_edit_modal' : 'description_modal' }}" 
        name="description" 
        label="Deskripsi" 
        :value="old('description', $lab->description)" 
        height="300" 
    />

    @if($lab->exists)
        <!-- Existing Media Section -->
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Current Images</label>
            <div class="col-sm-10">
                 @if ($lab->getMedia('lab_images')->count() > 0)
                    <div class="row g-2">
                        @foreach ($lab->getMedia('lab_images') as $media)
                            <div class="col-md-4">
                                <div class="card h-100 shadow-none border">
                                    <img src="{{ $media->getUrl() }}" class="card-img-top" alt="{{ $media->name }}" style="height: 100px; object-fit: cover;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted italic small">No images uploaded yet.</p>
                @endif
            </div>
        </div>
    @endif

    <!-- Media Upload Section -->
    <x-tabler.form-input 
        type="file" 
        name="lab_images[]" 
        id="{{ $lab->exists ? 'lab_images_edit_modal' : 'lab_images_modal' }}" 
        label="{{ $lab->exists ? 'Upload New Images' : 'Lab Images' }}" 
        class="filepond-input" 
        multiple 
        data-allow-multiple="true" 
        accept="image/*" 
        help="Upload photos of the lab (multiple allowed)." 
    />

    <x-tabler.form-input 
        type="file" 
        name="lab_attachments[]" 
        id="{{ $lab->exists ? 'lab_attachments_edit_modal' : 'lab_attachments_modal' }}" 
        label="{{ $lab->exists ? 'Upload New Attachments' : 'Attachments' }}" 
        class="filepond-input" 
        multiple 
        data-allow-multiple="true" 
        help="Upload documents or other attachments (multiple allowed)." 
    />
</x-tabler.form-modal>
