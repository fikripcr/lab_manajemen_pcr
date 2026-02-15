@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Tambah Lab Baru" pretitle="Laboratorium">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.labs.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form action="{{ route('lab.labs.store') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="name">Lab Name</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input name="name" placeholder="Computer Lab A" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="location">Location</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input name="location" placeholder="Building A, Floor 2" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="capacity">Capacity</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input type="number" name="capacity" placeholder="30" min="1" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="description">Description</label>
                            <div class="col-sm-10">
                                <x-tabler.form-textarea type="editor" id="description" name="description" :value="old('description')" height="300" class="mb-0" />
                            </div>
                        </div>

                        <!-- Media Upload Section -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Lab Images</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input type="file" name="lab_images[]" id="lab_images" class="filepond-input mb-0" multiple data-allow-multiple="true" accept="image/*" help="Upload photos of the lab (multiple allowed)." />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Attachments</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input type="file" name="lab_attachments[]" id="lab_attachments" class="filepond-input mb-0" multiple data-allow-multiple="true" help="Upload documents or other attachments (multiple allowed)." />
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" />
                                <x-tabler.button type="cancel" :href="route('lab.labs.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
