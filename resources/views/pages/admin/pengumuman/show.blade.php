@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">{{ ucfirst($pengumuman->jenis) }} Details /</span> {{ $pengumuman->judul }}
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $pengumuman->judul }}</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route($pengumuman->jenis.'.edit', $pengumuman) }}" class="btn btn-primary">
                            <i class="bx bx-edit me-1"></i> Edit
                        </a>
                        <a href="{{ route($pengumuman->jenis.'.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @include('components.flash-message')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Title:</h6>
                            <p class="mb-0">{{ $pengumuman->judul }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Type:</h6>
                            <p class="mb-0">
                                <span class="badge bg-label-{{ $pengumuman->jenis == 'pengumuman' ? 'primary' : 'info' }}">
                                    {{ ucfirst($pengumuman->jenis) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Author:</h6>
                            <p class="mb-0">{{ $pengumuman->penulis ? $pengumuman->penulis->name : 'System' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Status:</h6>
                            <p class="mb-0">
                                <span class="badge bg-label-{{ $pengumuman->is_published ? 'success' : 'warning' }}">
                                    {{ $pengumuman->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Created:</h6>
                            <p class="mb-0">{{ $pengumuman->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Last Updated:</h6>
                            <p class="mb-0">{{ $pengumuman->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Content:</h6>
                        <div class="border p-3 rounded bg-light">
                            <p class="mb-0">{!! nl2br(e($pengumuman->isi)) !!}</p>
                        </div>
                    </div>

                    @if($pengumuman->is_published && $pengumuman->published_at)
                        <div class="mb-3">
                            <h6 class="text-muted">Published Date:</h6>
                            <p class="mb-0">{{ $pengumuman->published_at->format('d M Y H:i') }}</p>
                        </div>
                    @endif

                    <div class="mt-4">
                        <form action="{{ route($pengumuman->jenis.'.destroy', $pengumuman) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this {{ $pengumuman->jenis }}?')">
                                <i class="bx bx-trash me-1"></i> Delete {{ ucfirst($pengumuman->jenis) }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
