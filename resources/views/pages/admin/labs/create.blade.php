@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Create New Lab</h4>

    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-body">
                    <x-admin.flash-message />

                    <form action="{{ route('labs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="name">Lab Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}"
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
                                       id="location" name="location" value="{{ old('location') }}"
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
                                       id="capacity" name="capacity" value="{{ old('capacity') }}"
                                       placeholder="30" min="1" >
                                @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="description">Description</label>
                            <div class="col-sm-10">
                                <x-admin.editor id="description" name="description" :value="old('description')" height="300" />
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Note for New Lab Creation -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Gambar Lab</label>
                            <div class="col-sm-10">
                                <div class="border rounded p-3 bg-light">
                                    <p class="mb-0">Belum ada gambar untuk lab ini. Anda bisa menambahkan gambar setelah lab dibuat atau sekarang.</p>
                                </div>
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
                                    <i class="bx bx-save me-1"></i> Buat Lab
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

@endsection
