@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Forms /</span> Edit {{ ucfirst($type) }}
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit {{ ucfirst($type) }}</h5>
                </div>
                <div class="card-body">
                    @include('components.flash-message')

                    <form action="{{ route($type.'.update', $pengumuman) }}" method="POST"  enctype="multipart/form-data" >
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label" for="judul">Title</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                   id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="isi">Content</label>
                            <x-tinymce.editor
                                id="isi"
                                name="isi"
                                :value="old('isi', $pengumuman->isi)"
                                height="400"
                                required
                            />
                            @error('isi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cover Image Section -->
                        <div class="mb-3">
                            <label class="form-label" for="cover_image">Cover Image</label>
                            <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                                   id="cover_image" name="cover_image" accept="image/*">
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload a new cover image (current will be replaced if a file is selected).</div>

                            @if($pengumuman->getFirstMediaByCollection('info_cover'))
                                <div class="mt-2">
                                    <p>Current Cover Image:</p>
                                    <img src="{{ asset('storage/' . $pengumuman->getFirstMediaByCollection('info_cover')->file_path) }}"
                                         alt="Current Cover Image" class="img-thumbnail" style="max-height: 200px;">
                                    <p class="mt-1">
                                        <small class="text-muted">
                                            {{ $pengumuman->getFirstMediaByCollection('info_cover')->file_name }}
                                            ({{ number_format($pengumuman->getFirstMediaByCollection('info_cover')->file_size / 1024, 2) }} KB)
                                        </small>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Attachments Section -->
                        <div class="mb-3">
                            <label class="form-label" for="attachments">Attachments</label>
                            <input type="file" class="form-control @error('attachments') is-invalid @enderror"
                                   id="attachments" name="attachments[]" multiple accept="*/*">
                            @error('attachments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload additional attachment files.</div>

                            @if($pengumuman->getMediaByCollection('info_attachment')->count() > 0)
                                <div class="mt-2">
                                    <p>Current Attachments:</p>
                                    <ul class="list-group">
                                        @foreach($pengumuman->getMediaByCollection('info_attachment') as $attachment)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $attachment->file_name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ number_format($attachment->file_size / 1024, 2) }} KB | {{ $attachment->mime_type }}</small>
                                                </div>
                                                <a href="{{ asset('storage/' . $attachment->file_path) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                    View
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input @error('is_published') is-invalid @enderror"
                                   id="is_published" name="is_published" value="1" {{ old('is_published', $pengumuman->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Publish {{ ucfirst($type) }}</label>
                            @error('is_published')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" name="jenis" value="{{ $type }}">

                        <div class="d-flex justify-content-start gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Update {{ ucfirst($type) }}
                            </button>
                            <a href="{{ route($type.'.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


