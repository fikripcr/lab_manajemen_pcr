@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Lab Details /</span> {{ $lab->name }}
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <a href="{{ route('labs.edit', $lab) }}" class="btn btn-primary me-2">
                            <i class='bx bx-edit me-1'></i> Edit
                        </a>
                        <form action="{{ route('labs.destroy', $lab) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this lab? All related data will be affected.')">
                                <i class='bx bx-trash me-1'></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <div class="button-wrapper">
                            <h4 class="mb-1">{{ $lab->name }}</h4>
                            <p class="mb-1">{{ $lab->location }}</p>
                            <span class="badge bg-label-info me-1">{{ $lab->capacity }} Seats</span>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="mb-2">Basic Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name</strong></td>
                                    <td>{{ $lab->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Location</strong></td>
                                    <td>{{ $lab->location }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Capacity</strong></td>
                                    <td><span class="badge bg-label-info">{{ $lab->capacity }} Seats</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="mb-2">Description</h6>
                            <p>{{ $lab->description ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Lab Media Section -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="mb-2">Gambar Laboratorium</h6>
                            @if($lab->getMediaByCollection('lab_images')->count() > 0)
                                <div class="row">
                                    @foreach($lab->getMediaByCollection('lab_images') as $media)
                                        <div class="col-md-4 col-lg-3 mb-3">
                                            <div class="card h-100">
                                                <img src="{{ asset('storage/' . $media->file_path) }}"
                                                     class="card-img-top"
                                                     alt="{{ $media->file_name }}"
                                                     style="height: 150px; object-fit: cover;">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ $media->file_name }}</h6>
                                                    <p class="card-text small">{{ $media->file_size }} bytes</p>
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

                    <div class="mt-2">
                        <a href="{{ route('labs.index') }}" class="btn btn-secondary">
                            <i class='bx bx-arrow-back me-1'></i> Kembali ke Daftar Lab
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
