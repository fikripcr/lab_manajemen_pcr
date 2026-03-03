@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header :title="$pengumuman->exists ? 'Edit ' . ucfirst($type) : 'Buat ' . ucfirst($type) . ' Baru'" :pretitle="ucfirst($type)">
    <x-slot:actions>
        <x-tabler.button type="back" class="d-none d-sm-inline-block" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <form action="{{ $pengumuman->exists ? route('lab.pengumuman.update', $pengumuman->encrypted_pengumuman_id) : route('lab.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($pengumuman->exists)
                @method('PUT')
            @endif
            <input type="hidden" name="jenis" value="{{ $type }}">

            <div class="row row-cards">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <x-tabler.form-input
                                name="judul"
                                label="Judul"
                                :value="old('judul', $pengumuman->judul ?? '')"
                                placeholder="Masukkan judul..."
                                required
                            />

                            <div class="mb-3">
                                <x-tabler.form-textarea
                                    name="isi"
                                    id="isi"
                                    label="Konten"
                                    type="editor"
                                    rows="20"
                                    :value="old('isi', $pengumuman->isi ?? '')"
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
                                        <input type="radio" name="is_published" value="1" class="form-selectgroup-input" {{ old('is_published', $pengumuman->is_published ?? false) ? 'checked' : '' }}>
                                        <span class="form-selectgroup-label text-success">
                                            <i class="ti ti-check me-1"></i> Published
                                        </span>
                                    </label>
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="is_published" value="0" class="form-selectgroup-input" {{ !old('is_published', $pengumuman->is_published ?? false) ? 'checked' : '' }}>
                                        <span class="form-selectgroup-label text-warning">
                                            <i class="ti ti-file me-1"></i> Draft
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-input
                                    name="image_url"
                                    label="URL Gambar (Cover)"
                                    :value="old('image_url', $pengumuman->image_url ?? '')"
                                    placeholder="https://example.com/image.jpg"
                                    help="Opsional. Jika diisi, gambar ini akan diprioritaskan daripada upload file."
                                />
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-input
                                    name="cover"
                                    type="file"
                                    label="Gambar Utama (Cover)"
                                    accept="image/*"
                                    help="Maksimal 5MB. Format: JPG, PNG, WEBP."
                                />
                                @if($pengumuman->has_image)
                                    <div class="mt-2 text-center">
                                        <img src="{{ $pengumuman->cover_url }}" class="rounded shadow-sm border" style="max-height: 120px; width: 100%; object-fit: cover;">
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-input
                                    name="attachments[]"
                                    type="file"
                                    label="File Pendukung (Lampiran)"
                                    multiple
                                    help="Maksimal 10MB per file. Bisa upload banyak file sekaligus."
                                />
                            </div>

                            {{-- Penulis Option if needed, user didn't explicitly ask but it was in controller --}}
                            {{-- Ignoring for now as it defaults to auth user usually, checking controller store/update --}}

                            <div class="mt-4">
                                <x-tabler.button
                                    type="submit"
                                    class="w-100"
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
    // HugeRTE is auto-initialized by x-tabler.form-textarea with type="editor"
</script>
@endpush
