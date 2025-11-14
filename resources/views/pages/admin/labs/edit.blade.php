@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Edit Lab</h4>

    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('labs.update', $lab) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="name">Lab Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $lab->name) }}"
                                       placeholder="Computer Lab A" >
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="location">Location</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                       id="location" name="location" value="{{ old('location', $lab->location) }}"
                                       placeholder="Building A, Floor 2" >
                                @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="capacity">Capacity</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                       id="capacity" name="capacity" value="{{ old('capacity', $lab->capacity) }}"
                                       placeholder="30" min="1" >
                                @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="description">Description</label>
                            <div class="col-sm-10">
                                <x-tinymce.editor id="description" name="description" :value="old('description', $lab->description)" height="300" />
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Existing Media Section -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Gambar Saat Ini</label>
                            <div class="col-sm-10">
                                @if($lab->getMediaByCollection('lab_images')->count() > 0)
                                    <div class="row mb-3">
                                        @foreach($lab->getMediaByCollection('lab_images') as $media)
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <img src="{{ asset('storage/' . $media->file_path) }}"
                                                         class="card-img-top"
                                                         alt="{{ $media->file_name }}"
                                                         style="height: 120px; object-fit: cover;">
                                                    <div class="card-body p-2">
                                                        <h6 class="card-title small">{{ Str::limit($media->file_name, 20) }}</h6>
                                                        @php
                                                            $labMedia = $lab->labMedia()->where('media_id', $media->id)->first();
                                                        @endphp
                                                        @if($labMedia)
                                                            <p class="card-text small">{{ Str::limit($labMedia->keterangan, 30) }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="card-footer p-2">
                                                        <small class="text-muted">{{ round($media->file_size / 1024, 2) }} KB</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">Belum ada gambar yang diunggah</p>
                                @endif
                            </div>
                        </div>

                        <!-- Media Upload Section -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Upload Gambar</label>
                            <div class="col-sm-10">
                                <div class="border rounded p-3 bg-light">
                                    <div id="media-upload-section">
                                        <div class="mb-3">
                                            <label class="form-label">Pilih Gambar</label>
                                            <input type="file" class="form-control" name="media_files[]" multiple accept="image/*">
                                            <small class="text-muted">Pilih satu atau lebih gambar untuk diunggah</small>
                                        </div>

                                        <div id="media-files-container">
                                            <!-- Dynamic form fields will be added here by JavaScript when files are selected -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i> Perbarui Lab
                                </button>
                                <a href="{{ route('labs.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-arrow-back me-1"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const fileInput = document.querySelector('input[name="media_files[]"]');
        const container = document.getElementById('media-files-container');

        fileInput.addEventListener('change', function() {
            container.innerHTML = ''; // Clear previous entries

            if (this.files.length > 0) {
                Array.from(this.files).forEach((file, index) => {
                    if (!file.type.match('image.*')) return; // Only process images

                    const mediaFormHtml = `
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <img src="${URL.createObjectURL(file)}" class="img-thumbnail" alt="Preview">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Judul Gambar</label>
                                                <input type="text" class="form-control" name="media_titles[]" placeholder="Judul Gambar" value="${file.name.replace(/\.[^/.]+$/, '')}" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Keterangan</label>
                                                <input type="text" class="form-control" name="media_descriptions[]" placeholder="Keterangan Gambar" />
                                            </div>
                                        </div>
                                        <small class="text-muted">Ukuran: ${(file.size/1024).toFixed(2)} KB</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    container.insertAdjacentHTML('beforeend', mediaFormHtml);
                });
            }
        });
    </script>
@endsection
