@if(request()->ajax() || request()->has('ajax'))
    <x-tabler.form-modal
        title="Tambah Lab Baru"
        route="{{ route('lab.labs.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        <x-tabler.form-input name="name" label="Lab Name" placeholder="Computer Lab A" required />

        <x-tabler.form-input name="location" label="Location" placeholder="Building A, Floor 2" required />

        <x-tabler.form-input type="number" name="capacity" label="Capacity" placeholder="30" min="1" required />

        <x-tabler.form-textarea type="editor" id="description_modal" name="description" label="Description" :value="old('description')" height="300" />

        <!-- Media Upload Section -->
        <x-tabler.form-input type="file" name="lab_images[]" id="lab_images_modal" label="Lab Images" class="filepond-input" multiple data-allow-multiple="true" accept="image/*" help="Upload photos of the lab (multiple allowed)." />

        <x-tabler.form-input type="file" name="lab_attachments[]" id="lab_attachments_modal" label="Attachments" class="filepond-input" multiple data-allow-multiple="true" help="Upload documents or other attachments (multiple allowed)." />
    </x-tabler.form-modal>
@else
    @extends('layouts.admin.app')

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

                            <x-tabler.form-input name="name" label="Lab Name" placeholder="Computer Lab A" required />

                            <x-tabler.form-input name="location" label="Location" placeholder="Building A, Floor 2" required />

                            <x-tabler.form-input type="number" name="capacity" label="Capacity" placeholder="30" min="1" required />

                            <x-tabler.form-textarea type="editor" id="description" name="description" label="Description" :value="old('description')" height="300" />

                            <!-- Media Upload Section -->
                            <x-tabler.form-input type="file" name="lab_images[]" id="lab_images" label="Lab Images" class="filepond-input" multiple data-allow-multiple="true" accept="image/*" help="Upload photos of the lab (multiple allowed)." />

                            <x-tabler.form-input type="file" name="lab_attachments[]" id="lab_attachments" label="Attachments" class="filepond-input" multiple data-allow-multiple="true" help="Upload documents or other attachments (multiple allowed)." />

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
@endif
