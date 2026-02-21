@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header :title="$page->exists ? 'Edit Halaman' : 'Buat Halaman Baru'" pretitle="CMS">
    <x-slot:actions>
        <a href="javascript:void(0)" onclick="history.back()" class="btn btn-secondary d-none d-sm-inline-block">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <form action="{{ $page->exists ? route('shared.public-page.update', $page->encrypted_page_id) : route('shared.public-page.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($page->exists)
                @method('PUT')
            @endif

            <div class="row row-cards">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <x-tabler.form-input
                                name="title"
                                label="Judul Halaman"
                                :value="$page->title"
                                placeholder="Masukkan judul halaman..."
                                required
                            />

                            <div class="mb-3">
                                <x-tabler.form-textarea
                                    name="content"
                                    label="Konten"
                                    type="editor"
                                    rows="20"
                                    :value="$page->content"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-selectgroup">
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="is_published" value="1" class="form-selectgroup-input" {{ old('is_published', $page->is_published ?? false) ? 'checked' : '' }}>
                                        <span class="form-selectgroup-label text-success">
                                            <i class="ti ti-check me-1"></i> Published
                                        </span>
                                    </label>
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="is_published" value="0" class="form-selectgroup-input" {{ !old('is_published', $page->is_published ?? false) ? 'checked' : '' }}>
                                        <span class="form-selectgroup-label text-warning">
                                            <i class="ti ti-file me-1"></i> Draft
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-input
                                    name="main_image"
                                    type="file"
                                    label="Gambar Utama"
                                    accept="image/*"
                                    help="Maksimal 5MB. Format: JPG, PNG, WEBP."
                                />
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-input
                                    name="attachments[]"
                                    type="file"
                                    label="File Pendukung"
                                    multiple
                                    help="Maksimal 10MB per file. Bisa upload banyak file sekaligus."
                                />
                            </div>

                             <x-tabler.form-textarea
                                name="meta_desc"
                                label="Meta Description (SEO)"
                                :value="$page->meta_desc"
                                rows="3"
                            />

                            <x-tabler.form-input
                                name="meta_keywords"
                                label="Meta Keywords (SEO)"
                                :value="$page->meta_keywords"
                                placeholder="Keyword 1, Keyword 2..."
                            />

                            <div class="mt-4">
                                <x-tabler.button
                                    type="submit"
                                    class="w-100 btn-primary"
                                    icon="ti ti-device-floppy"
                                    text="Simpan Halaman"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
@endsection

@push('scripts')
<script>
    // Additional scripts if needed
</script>
@endpush
