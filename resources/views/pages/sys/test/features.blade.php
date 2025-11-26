@extends('layouts.sys.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">System Test /</span> JS Library Features</h4>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Flatpickr Date Picker Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Flatpickr Date Picker</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="datePicker" class="form-label">Date Picker</label>
                            <input type="text" id="datePicker" class="form-control" placeholder="Select date">
                        </div>
                        <div class="col-md-6">
                            <label for="dateTimePicker" class="form-label">Date & Time Picker</label>
                            <input type="text" id="dateTimePicker" class="form-control" placeholder="Select date and time">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="rangePicker" class="form-label">Date Range Picker</label>
                            <input type="text" id="rangePicker" class="form-control" placeholder="Select date range">
                        </div>
                        <div class="col-md-6">
                            <label for="multiplePicker" class="form-label">Multiple Dates Picker</label>
                            <input type="text" id="multiplePicker" class="form-control" placeholder="Select multiple dates">
                        </div>
                    </div>
                </div>
            </div>

            <!-- FilePond Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">FilePond File Upload</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="filepond" class="form-label">Upload Files</label>
                        <input type="file" id="filepond" class="form-control" multiple>
                    </div>
                </div>
            </div>

            {{-- TinyMCE Editor Test --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">TinyMCE Editor Test</h5>
                </div>
                <div class="card-body">
                    <form id="tinymceTestForm">
                        <div class="mb-3">
                            <label for="editorContent" class="form-label">Editor Content</label>
                            <x-sys.editor id="editorContent" name="isi" :value="old('isi')" height="200" required />
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" id="getContentBtn">Get Content</button>
                    </form>

                    <div class="mt-3">
                        <h6>Content Preview:</h6>
                        <div id="contentPreview" class="border p-3 bg-light"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr
            flatpickr("#datePicker", {
                dateFormat: "Y-m-d",
            });

            flatpickr("#dateTimePicker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });

            flatpickr("#rangePicker", {
                mode: "range",
                dateFormat: "Y-m-d",
            });

            flatpickr("#multiplePicker", {
                mode: "multiple",
                dateFormat: "Y-m-d",
            });


            const pond = FilePond.create(document.querySelector('#filepond'), {
                allowMultiple: true,
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        // Simulate file upload
                        const timer = setTimeout(() => {
                            load('unique-id-of-file');
                        }, 1000);

                        return {
                            abort: () => {
                                clearTimeout(timer);
                                abort();
                            }
                        };
                    },
                    revert: './revert',
                    restore: './restore/',
                    load: './load/',
                },
                onaddfile: (file) => {
                    console.log('File added:', file);
                },
                onprocessfile: (file) => {
                    console.log('File processed:', file);
                }
            });

            // TinyMCE Editor Test
            try {
                // Get content button
                document.getElementById('getContentBtn').addEventListener('click', function() {
                    const editor = window.tinymce.get('editorContent');
                    if (editor) {
                        const content = editor.getContent();
                        document.getElementById('contentPreview').innerHTML = content;
                        console.log('Content:', content);
                    }
                });

                // Form submission
                document.getElementById('tinymceTestForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const editor = tinymce.get('editorContent');
                    if (editor) {
                        const content = editor.getContent();

                        console.log('Content:', content);

                        alert('Form data captured. Check console for details.');
                    }
                });
            } catch (error) {
                console.error('Error initializing TinyMCE:', error);
            }

        });


    </script>
@endpush
