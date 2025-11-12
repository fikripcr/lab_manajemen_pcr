@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Forms /</span> Create {{ ucfirst($type) }}
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New {{ ucfirst($type) }}</h5>
                </div>
                <div class="card-body">
                    @include('components.flash-message')
                    
                    <form action="{{ route($type.'.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label" for="judul">Title</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                   id="judul" name="judul" value="{{ old('judul') }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="isi">Content</label>
                            <textarea class="form-control @error('isi') is-invalid @enderror" 
                                      id="isi" name="isi" rows="6" required>{{ old('isi') }}</textarea>
                            @error('isi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="jenis">Type</label>
                            <select class="form-select @error('jenis') is-invalid @enderror" 
                                    id="jenis" name="jenis" required>
                                <option value="pengumuman" {{ old('jenis', $type) == 'pengumuman' ? 'selected' : '' }}>
                                    Pengumuman
                                </option>
                                <option value="berita" {{ old('jenis', $type) == 'berita' ? 'selected' : '' }}>
                                    Berita
                                </option>
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="penulis_id">Author</label>
                            <select class="form-select @error('penulis_id') is-invalid @enderror" 
                                    id="penulis_id" name="penulis_id" required>
                                <option value="">Select Author</option>
                                @foreach($penulisOptions as $penulis)
                                    <option value="{{ $penulis->id }}" {{ old('penulis_id') == $penulis->id ? 'selected' : '' }}>
                                        {{ $penulis->name }} ({{ $penulis->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('penulis_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input @error('is_published') is-invalid @enderror" 
                                   id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Publish {{ ucfirst($type) }}</label>
                            @error('is_published')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-start gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Create {{ ucfirst($type) }}
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