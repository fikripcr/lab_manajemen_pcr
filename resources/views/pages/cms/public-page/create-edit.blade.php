@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header :title="$page->exists ? 'Edit Halaman' : 'Buat Halaman Baru'" pretitle="CMS">
    <x-slot:actions>
        <x-tabler.button type="back" class="d-none d-sm-inline-block" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <form action="{{ $page->exists ? route('cms.public-page.update', $page->encrypted_page_id) : route('cms.public-page.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($page->exists)
                @method('PUT')
            @endif

            <div class="row row-cards">
                <div class="col-lg-8">
                    <x-tabler.card>
                        <x-tabler.card-body>
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
                                    id="content"
                                    label="Konten"
                                    rows="20"
                                    :value="$page->content"
                                />
                            </div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>
                
                <div class="col-lg-4">
                    <x-tabler.card>
                        <x-tabler.card-body>
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
                                    class="filepond-input"
                                    help="Maksimal 5MB. Format: JPG, PNG, WEBP."
                                />
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-input
                                    name="attachments[]"
                                    type="file"
                                    label="File Pendukung"
                                    multiple
                                    class="filepond-input"
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
                                    class="w-100"
                                    text="Simpan Halaman"
                                />
                            </div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>
            </div>
        </form>
@endsection

@push('scripts')
<script>
    if (window.loadHugeRTE) {
        window.loadHugeRTE('#content', { 
            height: 600,
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    }
</script>
@endpush
