@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header :title="'Edit ' . ucfirst($type)" pretitle="Announcement">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.'.$type . '.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form action="{{ route('lab.'.$type . '.update', $pengumuman->encrypted_pengumuman_id) }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                        @csrf
                        @method('PUT')

                        <x-tabler.form-input name="judul" label="Title" value="{{ old('judul', $pengumuman->judul) }}" required />

                        <x-tabler.form-textarea type="editor" id="isi" name="isi" label="Content" :value="old('isi', $pengumuman->isi)" height="400" required="true" />

                        <div class="mb-3">
                            <x-tabler.form-input type="file" id="cover_image" name="cover" label="Cover Image" accept="image/*" help="Upload a new cover image to replace the current one." />
                            
                            @if ($pengumuman->hasMedia('info_cover'))
                                <div class="mt-2 text-center">
                                    <div class="form-label">Current Cover:</div>
                                    <img src="{{ $pengumuman->getFirstMediaUrl('info_cover', 'medium') }}" alt="Current Cover" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <x-tabler.form-input type="file" id="attachments" name="attachments[]" label="Attachments" multiple="true" help="Upload additional files (current ones will be kept)." />

                            @if ($pengumuman->hasMedia('info_attachment'))
                                <div class="mt-2">
                                    <div class="form-label">Current Attachments:</div>
                                    <ul class="list-group list-group-flush border rounded">
                                        @foreach ($pengumuman->getMedia('info_attachment') as $attachment)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>{{ $attachment->file_name }}</span>
                                                <x-tabler.button :href="$attachment->getUrl()" class="btn-sm btn-ghost-secondary" target="_blank" text="View" />
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <x-tabler.form-checkbox name="is_published" value="1" label="Publish {{ ucfirst($type) }}" :checked="old('is_published', $pengumuman->is_published)" switch />
                        </div>

                        <input type="hidden" name="jenis" value="{{ $type }}">

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" :text="'Update ' . ucfirst($type)" />
                                <x-tabler.button type="cancel" :href="route('lab.'.$type . '.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
