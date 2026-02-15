@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

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

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="judul">Title</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input name="judul" value="{{ old('judul') }}" required class="mb-0" />
                            </div>
                        </div>

                            <div class="col-sm-10">
                                <x-tabler.form-textarea type="editor" id="isi" name="isi" label="Content" :value="old('isi')" height="400" required="true" class="mb-0" />
                            </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="cover_image">Cover Image</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input type="file" id="cover_image" name="cover" accept="image/*" help="Upload a cover image for this {{ strtolower($type) }}." class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="attachments">Attachments</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input type="file" id="attachments" name="attachments[]" multiple="true" help="Upload related attachments/files for this {{ strtolower($type) }}." class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.form-checkbox name="is_published" value="1" label="Publish {{ ucfirst($type) }}" :checked="old('is_published')" switch />
                            </div>
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
