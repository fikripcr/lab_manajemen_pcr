@if(request()->ajax() || request()->has('ajax'))
    <x-tabler.form-modal
        :title="'Create ' . ucfirst($type)"
        route="{{ route('lab.'.$type . '.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        <x-tabler.form-input name="judul" label="Title" value="{{ old('judul') }}" required />

        <x-tabler.form-textarea type="editor" id="isi_modal" name="isi" label="Content" :value="old('isi')" height="400" required="true" />

        <x-tabler.form-input type="file" id="cover_image_modal" name="cover" label="Cover Image" accept="image/*" help="Upload a cover image for this {{ strtolower($type) }}." />

        <x-tabler.form-input type="file" id="attachments_modal" name="attachments[]" label="Attachments" multiple="true" help="Upload related attachments/files for this {{ strtolower($type) }}." />

        <div class="mb-3">
            <x-tabler.form-checkbox name="is_published" value="1" label="Publish {{ ucfirst($type) }}" :checked="old('is_published')" switch />
        </div>

        <input type="hidden" name="jenis" value="{{ $type }}">
    </x-tabler.form-modal>
@else
    @extends('layouts.admin.app')

    @section('header')
        <x-tabler.page-header :title="'Create ' . ucfirst($type)" :pretitle="ucfirst($type)">
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

                        <form action="{{ route('lab.'.$type . '.store') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                            @csrf

                            <x-tabler.form-input name="judul" label="Title" value="{{ old('judul') }}" required />

                            <x-tabler.form-textarea type="editor" id="isi" name="isi" label="Content" :value="old('isi')" height="400" required="true" />

                            <x-tabler.form-input type="file" id="cover_image" name="cover" label="Cover Image" accept="image/*" help="Upload a cover image for this {{ strtolower($type) }}." />

                            <x-tabler.form-input type="file" id="attachments" name="attachments[]" label="Attachments" multiple="true" help="Upload related attachments/files for this {{ strtolower($type) }}." />

                            <div class="mb-3">
                                <x-tabler.form-checkbox name="is_published" value="1" label="Publish {{ ucfirst($type) }}" :checked="old('is_published')" switch />
                            </div>

                            <input type="hidden" name="jenis" value="{{ $type }}">

                            <div class="row mt-4">
                                <div class="col-sm-10 offset-sm-2">
                                    <x-tabler.button type="submit" text="Simpan" />
                                    <x-tabler.button type="cancel" :href="route('lab.'.$type . '.index')" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif
