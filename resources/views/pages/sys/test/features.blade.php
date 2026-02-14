@extends('layouts.sys.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <div class="page-pretitle">System Test</div>
        <h2 class="page-title">JS Library Features</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="{{ route('sys.test.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                Back
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
    <div class="row row-cards">
        <div class="col-lg-6">
            
            {{-- Flatpickr Date Picker --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-calendar me-2"></i> Flatpickr Date Picker
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="datePicker" class="form-label">Date Picker</label>
                            <input type="text" id="datePicker" class="form-control" placeholder="Select date">
                        </div>
                        <div class="col-md-6">
                            <label for="dateTimePicker" class="form-label">Date & Time</label>
                            <input type="text" id="dateTimePicker" class="form-control" placeholder="Select date & time">
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="rangePicker" class="form-label">Date Range</label>
                            <input type="text" id="rangePicker" class="form-control" placeholder="Select range">
                        </div>
                        <div class="col-md-6">
                            <label for="multiplePicker" class="form-label">Multiple Dates</label>
                            <input type="text" id="multiplePicker" class="form-control" placeholder="Select multiple">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Choices.js Test --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-list-check me-2"></i> Select2 Advanced Select
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <x-tabler.form-select 
                                id="singleSelect" 
                                name="singleSelect" 
                                label="Single Select" 
                                placeholder="Select an option" 
                                type="select2"
                                :options="['option1'=>'Option 1', 'option2'=>'Option 2', 'option3'=>'Option 3', 'option4'=>'Option 4']" 
                            />
                        </div>
                        <div class="col-md-6">
                            <x-tabler.form-select 
                                id="multiSelect" 
                                name="multiSelect" 
                                label="Multi Select" 
                                placeholder="Select multiple options" 
                                multiple
                                type="select2"
                                :options="['option1'=>'Option 1', 'option2'=>'Option 2', 'option3'=>'Option 3', 'option4'=>'Option 4', 'option5'=>'Option 5']" 
                            />
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="searchSelect" class="form-label">API Search (Single)</label>
                            <select id="searchSelect" class="form-select"></select>
                        </div>
                        <div class="col-md-6">
                            <label for="searchSelectMulti" class="form-label">API Search (Multiple)</label>
                            <select id="searchSelectMulti" class="form-select" multiple></select>
                        </div>
                    </div>

                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-primary" id="getSelect2Value">
                            <i class="ti ti-code me-1"></i> Get Values
                        </button>
                    </div>

                    <div class="mt-3">
                        <div class="form-label">Selection Preview</div>
                        <div id="select2Preview" class="border p-2 bg-light rounded small text-muted font-monospace">
                            No values selected yet.
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-6">

            {{-- FilePond Section --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-upload me-2"></i> FilePond Upload
                    </h3>
                </div>
                <div class="card-body">
                    <label for="filepond" class="form-label">Upload Files</label>
                    <input type="file" id="filepond" class="form-control" multiple>
                    <div class="form-hint">Supports multiple file upload with preview.</div>
                </div>
            </div>

            {{-- HugeRTE Editor Test --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-edit me-2"></i> HugeRTE Editor
                    </h3>
                </div>
                <div class="card-body">
                    <form id="tinymceTestForm">
                        <div class="mb-3">
                            <x-tabler.editor id="editorContent" name="isi" :value="old('isi')" height="250" required />
                        </div>
                        <div class="btn-list">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Submit
                            </button>
                            <button type="button" class="btn btn-secondary" id="getContentBtn">
                                <i class="ti ti-eye me-1"></i> Get Content
                            </button>
                        </div>
                    </form>

                    <div class="mt-3">
                        <div class="form-label">Content Preview</div>
                        <div id="contentPreview" class="border p-3 bg-light rounded" style="min-height: 100px;"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-bell me-2"></i> SweetAlert2 Utilities
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h4 class="card-subtitle mb-3">Basic Alerts</h4>
                            <div class="btn-list">
                                <button type="button" class="btn btn-success" id="showSuccessBtn">Success</button>
                                <button type="button" class="btn btn-danger" id="showErrorBtn">Error</button>
                                <button type="button" class="btn btn-warning" id="showWarningBtn">Warning</button>
                                <button type="button" class="btn btn-info" id="showInfoBtn">Info</button>
                                <button type="button" class="btn btn-secondary" id="showLoadingBtn">Loading</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="card-subtitle mb-3">Advanced Interactions</h4>
                            <div class="btn-list">
                                <button type="button" class="btn btn-outline-success" id="showConfirmationBtn">Confirm</button>
                                <button type="button" class="btn btn-outline-danger" id="showDeleteConfirmationBtn">Delete</button>
                                <button type="button" class="btn btn-outline-primary" id="showFormErrorsBtn">Form Errors</button>
                                <button type="button" class="btn btn-outline-info" id="handleAjaxResponseBtn">Ajax Response</button>
                                <button type="button" class="btn btn-outline-warning" id="showBulkActionBtn">Bulk Action</button>
                                <button type="button" class="btn btn-outline-dark" id="showHtmlContentBtn">HTML Content</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            // Mock permission data for Select2 AJAX demo
            const MOCK_PERMISSIONS = [
                { id: 'view_dashboard', text: 'View Dashboard' },
                { id: 'edit_users', text: 'Edit Users' },
                { id: 'delete_users', text: 'Delete Users' },
                { id: 'view_reports', text: 'View Reports' },
                { id: 'create_posts', text: 'Create Posts' },
                { id: 'manage_roles', text: 'Manage Roles' }
            ];

            // Lazy load form features individually
            try {
                await Promise.all([
                    typeof window.loadFlatpickr === 'function' ? window.loadFlatpickr() : Promise.resolve(),
                    typeof window.loadSelect2 === 'function' ? window.loadSelect2() : Promise.resolve(),
                    typeof window.loadFilePond === 'function' ? window.loadFilePond() : Promise.resolve()
                ]);
                console.log('Form features loaded successfully');
            } catch (error) {
                console.error('Error loading form features:', error);
                return;
            }

            // Initialize Select2 (This fixes the API Search examples)
            if (typeof $.fn.select2 !== 'undefined') {
                // Search Select with AJAX (simulated)
                $('#searchSelect').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Search for permissions...',
                    allowClear: true,
                    width: '100%',
                    ajax: {
                        delay: 250,
                        transport: function(params, success, failure) {
                            setTimeout(() => {
                                const searchTerm = params.data.term || '';
                                const filtered = MOCK_PERMISSIONS.filter(item =>
                                    item.text.toLowerCase().includes(searchTerm.toLowerCase())
                                );
                                success({ results: filtered });
                            }, 300);
                        }
                    }
                });

                // Multi Select with AJAX
                $('#searchSelectMulti').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Search for permissions...',
                    allowClear: true,
                    width: '100%',
                    multiple: true,
                    ajax: {
                        delay: 250,
                        transport: function(params, success) {
                            setTimeout(() => {
                                const searchTerm = params.data.term || '';
                                const filtered = MOCK_PERMISSIONS.filter(item =>
                                    item.text.toLowerCase().includes(searchTerm.toLowerCase())
                                );
                                success({ results: filtered });
                            }, 300);
                        }
                    }
                });

                // Get Values Handler
                const getValBtn = document.getElementById('getSelect2Value');
                if(getValBtn){
                    getValBtn.addEventListener('click', function() {
                        const singleValue = $('#singleSelect').val();
                        const multiValues = $('#multiSelect').val();
                        const searchValue = $('#searchSelect').val();
                        const searchMultiValue = $('#searchSelectMulti').val();

                        const preview = document.getElementById('select2Preview');
                        preview.innerHTML = `
                            <strong>Single Select Value:</strong> ${singleValue || 'None'}<br>
                            <strong>Multi Select Values:</strong> ${Array.isArray(multiValues) ? multiValues.join(', ') : multiValues || 'None'}<br>
                            <strong>Search Select Value:</strong> ${searchValue || 'None'}<br>
                            <strong>Search Multi Select Values:</strong> ${Array.isArray(searchMultiValue) ? searchMultiValue.join(', ') : searchMultiValue || 'None'}<br>
                        `;
                    });
                }
            } else {
                console.error('Select2 is not loaded');
            }

            // Flatpickr Initialization
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

            // SweetAlert Utilities Test Event Listeners
            if (typeof showSuccessMessage !== 'undefined') {
                const btnSuccess = document.getElementById('showSuccessBtn');
                if(btnSuccess) btnSuccess.addEventListener('click', function() { showSuccessMessage('Success!', 'This is a success message.'); });

                const btnError = document.getElementById('showErrorBtn');
                if(btnError) btnError.addEventListener('click', function() { showErrorMessage('Error!', 'This is an error message.'); });

                const btnWarn = document.getElementById('showWarningBtn');
                if(btnWarn) btnWarn.addEventListener('click', function() { showWarningMessage('Warning!', 'This is a warning message.'); });

                const btnInfo = document.getElementById('showInfoBtn');
                if(btnInfo) btnInfo.addEventListener('click', function() { showInfoMessage('Info', 'This is an info message.'); });

                const btnLoad = document.getElementById('showLoadingBtn');
                if(btnLoad) btnLoad.addEventListener('click', function() {
                    const loadingAlert = showLoadingMessage('Processing...', 'Please wait while we process your request.');
                    setTimeout(() => { Swal.close(); }, 3000);
                });

                const btnConfirm = document.getElementById('showConfirmationBtn');
                if(btnConfirm) btnConfirm.addEventListener('click', function() {
                    showConfirmation('Are you sure?', 'Do you want to proceed with this action?').then((result) => {
                        if (result.isConfirmed) {
                            showSuccessMessage('Confirmed!', 'You confirmed the action.');
                        } else {
                            showInfoMessage('Cancelled', 'Action was cancelled.');
                        }
                    });
                });

                const btnDelete = document.getElementById('showDeleteConfirmationBtn');
                if(btnDelete) btnDelete.addEventListener('click', function() {
                    showDeleteConfirmation('Are you sure?', 'This action cannot be undone!', 'Yes, delete it!').then((result) => {
                        if (result.isConfirmed) {
                             showSuccessMessage('Deleted!', 'Item has been deleted.');
                        } else {
                            showInfoMessage('Cancelled', 'Item was not deleted.');
                        }
                    });
                });

                const btnErrors = document.getElementById('showFormErrorsBtn');
                if(btnErrors) btnErrors.addEventListener('click', function() {
                    showFormErrors(['The name field is required.', 'The email field must be a valid email address.', 'The password must be at least 8 characters.']);
                });

                const btnAjax = document.getElementById('handleAjaxResponseBtn');
                if(btnAjax) btnAjax.addEventListener('click', function() {
                    handleAjaxResponse({ success: true, title: 'Operation Successful!', message: 'Your request was processed successfully.', data: { id: 123 } },
                        function(response) { console.log('Success callback executed with data:', response.data); },
                        function(response) { console.log('Error callback executed with data:', response); }
                    );
                });

                const btnBulk = document.getElementById('showBulkActionBtn');
                if(btnBulk) btnBulk.addEventListener('click', function() {
                    showBulkActionConfirmation('Delete', 5, 'user').then((result) => {
                        if (result.isConfirmed) { showSuccessMessage('Success!', '5 users have been deleted.'); }
                    });
                });

                const btnHtml = document.getElementById('showHtmlContentBtn');
                if(btnHtml) btnHtml.addEventListener('click', function() {
                    showInfoMessage('HTML Content', '<p>This alert contains <strong>HTML content</strong>!</p><ul><li>It supports lists</li><li><em>Formatting</em></li><li>And other HTML elements</li></ul>');
                });
            } else {
                console.error('SweetAlert utilities are not loaded');
            }
        });
    </script>
@endpush
