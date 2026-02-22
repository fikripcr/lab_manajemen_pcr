<x-tabler.form-modal
    id_form="{{ $lab->exists ? 'editLabForm' : 'createLabForm' }}"
    title="{{ $lab->exists ? 'Ubah Lab' : 'Tambah Lab Baru' }}"
    route="{{ $lab->exists ? route('lab.labs.update', $lab->encrypted_lab_id) : route('lab.labs.store') }}"
    method="{{ $lab->exists ? 'PUT' : 'POST' }}"
    enctype="multipart/form-data"
>
    <div class="row">
        <!-- Kolom Kiri: Informasi Utama -->
        <div class="col-md-7">
            <h3 class="card-title mb-3">Informasi Utama</h3>
            <div class="card card-body">
                <x-tabler.form-input 
                    name="name" 
                    label="Nama Lab" 
                    value="{{ old('name', $lab->name) }}" 
                    placeholder="Computer Lab A" 
                    required 
                />

                <x-tabler.form-input 
                    name="location" 
                    label="Lokasi" 
                    value="{{ old('location', $lab->location) }}" 
                    placeholder="Building A, Floor 2" 
                    required 
                />

                <x-tabler.form-input 
                    type="number" 
                    name="capacity" 
                    label="Kapasitas" 
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
            </div>
        </div>

        <!-- Kolom Kanan: Media & Lampiran -->
        <div class="col-md-5">
            <h3 class="card-title mb-3">Media & Lampiran</h3>
            <div class="card card-body">
                @if($lab->exists)
                    <!-- Existing Media Section -->
                    <div class="mb-3">
                        <label class="form-label">Gambar Saat Ini</label>
                        @if ($lab->getMedia('lab_images')->count() > 0)
                            <div class="row g-2">
                                @foreach ($lab->getMedia('lab_images') as $media)
                                    <div class="col-md-6">
                                        <div class="card h-100 shadow-none border">
                                            <img src="{{ $media->getUrl() }}" class="card-img-top" alt="{{ $media->name }}" style="height: 100px; object-fit: cover;">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted italic small">Belum ada gambar yang diunggah.</p>
                        @endif
                    </div>
                @endif

                <!-- Media Upload Section -->
                <x-tabler.form-input 
                    type="file" 
                    name="lab_images[]" 
                    id="{{ $lab->exists ? 'lab_images_edit_modal' : 'lab_images_modal' }}" 
                    label="{{ $lab->exists ? 'Unggah Gambar Baru' : 'Gambar Lab' }}" 
                    class="filepond-input" 
                    multiple 
                    data-allow-multiple="true" 
                    accept="image/*" 
                    help="Unggah foto lab (bisa lebih dari satu)." 
                />

                <x-tabler.form-input 
                    type="file" 
                    name="lab_attachments[]" 
                    id="{{ $lab->exists ? 'lab_attachments_edit_modal' : 'lab_attachments_modal' }}" 
                    label="{{ $lab->exists ? 'Unggah Lampiran Baru' : 'Lampiran' }}" 
                    class="filepond-input" 
                    multiple 
                    data-allow-multiple="true" 
                    help="Unggah dokumen atau lampiran lainnya (bisa lebih dari satu)." 
                />
            </div>
        </div>
    </div>
</x-tabler.form-modal>
