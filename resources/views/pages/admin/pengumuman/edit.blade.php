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
                    <x-flash-message />

                    <form action="{{ route($type . '.update', encryptId($pengumuman->pengumuman_id)) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label" for="judul">Title</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="isi">Content</label>
                            <x-tinymce.editor id="isi" name="isi" :value="old('isi', $pengumuman->isi)" height="400" required />
                        </div>

                        <!-- Cover Image Section -->
                        <div class="mb-3">
                            <label class="form-label" for="cover">Cover Image</label>
                            <input type="file" class="form-control @error('cover') is-invalid @enderror" id="cover" name="cover" accept="image/*">

                            <div class="form-text">Upload a new cover image (current will be replaced if a file is selected).</div>

                            @if ($pengumuman->hasMedia('cover'))
                                <div class="mt-2">
                                    <p>Current Cover Image:</p>
                                    <img src="{{ $pengumuman->getFirstMediaUrl('cover', 'medium') }}" alt="Current Cover Image" class="img-thumbnail">
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="attachments">Attachments</label>
                            <input type="file" class="form-control @error('attachments') is-invalid @enderror" id="attachments" name="attachments[]" multiple accept="*/*">

                            <div class="form-text">Upload additional attachment files.</div>

                            @if ($pengumuman->hasMedia('attachments'))
                                <div class="mt-2">
                                    <p>Current Attachments:</p>
                                    <ul class="list-group">
                                        @foreach ($pengumuman->getMedia('attachments') as $attachment)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $attachment->file_name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $attachment->human_readable_size }}</small>
                                                </div>
                                                <a href="{{ $attachment->getUrl() }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                    View
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input @error('is_published') is-invalid @enderror" id="is_published" name="is_published" value="1" {{ old('is_published', $pengumuman->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Publish {{ ucfirst($type) }}</label>
                        </div>

                        <input type="hidden" name="jenis" value="{{ $type }}">

                        <div class="d-flex justify-content-start gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Update {{ ucfirst($type) }}
                            </button>
                            <a href="{{ route($type . '.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
