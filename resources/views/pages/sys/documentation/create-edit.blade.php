@if(request()->ajax() || request()->has('ajax'))
    <x-tabler.form-modal
        title="Edit Documentation: {{ $page }}"
        route="{{ route('sys.documentation.update', $page) }}"
        method="PUT"
        submitText="Simpan Perubahan"
        submitIcon="ti-device-floppy"
    >
        <x-tabler.form-textarea type="editor" id="content" name="content" label="Konten" :value="old('content', $content)" height="500" />
    </x-tabler.form-modal>
@else
    @extends('layouts.tabler.app')

    @section('header')
        <x-tabler.page-header title="Edit Documentation: {{ $page }}">
            <x-slot:actions>
                <x-tabler.button type="back" :href="route('sys.documentation.show', $page)" class="me-2" />
            </x-slot:actions>
        </x-tabler.page-header>
    @endsection

    @section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-tabler.flash-message />

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Content Editor</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('sys.documentation.update', $page) }}" class="ajax-form">
                    @csrf
                    @method('PUT')

                    <x-tabler.form-textarea type="editor" id="content" name="content" label="Content" :value="old('content', $content)" height="500" />

                    <div class="d-flex justify-content-end gap-2">
                        <x-tabler.button type="back" :href="route('sys.documentation.show', $page)" />
                        <x-tabler.button type="submit" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
@endif
