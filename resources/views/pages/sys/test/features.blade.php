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
        {{-- Left Column: Form Inputs --}}
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
                        <i class="ti ti-list-check me-2"></i> Choices.js Advanced Select
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="singleSelect" class="form-label">Single Select</label>
                            <select id="singleSelect" class="form-select">
                                <option value="">Select an option</option>
                                <option value="option1">Option 1</option>
                                <option value="option2">Option 2</option>
                                <option value="option3">Option 3</option>
                                <option value="option4">Option 4</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="multiSelect" class="form-label">Multi Select</label>
                            <select id="multiSelect" class="form-select" multiple>
                                <option value="option1">Option 1</option>
                                <option value="option2">Option 2</option>
                                <option value="option3">Option 3</option>
                                <option value="option4">Option 4</option>
                                <option value="option5">Option 5</option>
                            </select>
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
                        <button type="button" class="btn btn-primary" id="getChoicesValue">
                            <i class="ti ti-code me-1"></i> Get Values
                        </button>
                    </div>

                    <div class="mt-3">
                        <div class="form-label">Selection Preview</div>
                        <div id="choicesPreview" class="border p-2 bg-light rounded small text-muted font-monospace">
                            No values selected yet.
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right Column: Rich Content --}}
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

            {{-- TinyMCE Editor Test --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-edit me-2"></i> TinyMCE Editor
                    </h3>
                </div>
                <div class="card-body">
                    <form id="tinymceTestForm">
                        <div class="mb-3">
                            <x-sys.editor id="editorContent" name="isi" :value="old('isi')" height="250" required />
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

        {{-- Bottom Full Width: Alerts --}}
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
            // Lazy load form features individually
            try {
                await Promise.all([
                    typeof window.loadFlatpickr === 'function' ? window.loadFlatpickr() : Promise.resolve(),
                    typeof window.loadChoices === 'function' ? window.loadChoices() : Promise.resolve(),
                    typeof window.loadFilePond === 'function' ? window.loadFilePond() : Promise.resolve()
                ]);
                console.log('Form features loaded successfully');
            } catch (error) {
                console.error('Error loading form features:', error);
                return;
            }

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

            // Initialize Choices.js after checking dependencies
            let singleSelect, multiSelect, searchSelect, searchSelectMulti;

            if (typeof Choices !== 'undefined') {
                singleSelect = new Choices('#singleSelect', {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Search for an option...',
                    shouldSort: false,
                });

                multiSelect = new Choices('#multiSelect', {
                    removeItemButton: true,
                    editItems: true,
                    allowHTML: true,
                    searchPlaceholderValue: 'Search for options...',
                });


                    searchSelect = new Choices('#searchSelect', {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Search for permissions...',
                    loadingText: 'Loading permissions...',
                    noResultsText: 'No permissions found',
                    noChoicesText: 'No permissions to choose from',
                    itemSelectText: 'Press to select',
                });

                searchSelectMulti = new Choices('#searchSelectMulti', {
                    removeItemButton: true,
                    searchEnabled: true,
                    searchPlaceholderValue: 'Search for permissions...',
                    loadingText: 'Loading permissions...',
                    noResultsText: 'No permissions found',
                    noChoicesText: 'No permissions to choose from',
                    itemSelectText: 'Press to select',
                });
            } else {
                console.error('Choices.js is not loaded');
            }

            // Add event listener to get selected values
            if (typeof Choices !== 'undefined') {
                document.getElementById('getChoicesValue').addEventListener('click', function() {
                    const singleValue = singleSelect.getValue(true);
                    const multiValues = multiSelect.getValue(true);
                    const searchValue = searchSelect.getValue(true);
                    const searchMultiValue = searchSelectMulti.getValue(true);

                    const preview = document.getElementById('choicesPreview');
                    preview.innerHTML = `
                        <strong>Single Select Value:</strong> ${singleValue || 'None'}<br>
                        <strong>Multi Select Values:</strong> ${Array.isArray(multiValues) ? multiValues.join(', ') : multiValues || 'None'}<br>
                        <strong>Search Select Value:</strong> ${searchValue || 'None'}<br>
                        <strong>Search Multi Select Values:</strong> ${Array.isArray(searchMultiValue) ? searchMultiValue.join(', ') : searchMultiValue || 'None'}<br>
                    `;
                });
            }

            // Initialize searchSelect and setup event listeners
            async function initSearchSelect() {
                if (typeof axios !== 'undefined') {
                    // --- Single Select Configuration ---
                    if (typeof searchSelect !== 'undefined') {
                        // Initially set placeholder option
                        searchSelect.setChoices([
                            {
                                value: '',
                                label: 'Search for permissions...',
                                disabled: true
                            }
                        ], 'value', 'label', true);

                        // Listen to the search event from Choices.js
                        searchSelect.passedElement.element.addEventListener('search', async function(event) {
                            handleSearch(event, searchSelect);
                        });
                    }

                    // --- Multi Select Configuration ---
                    if (typeof searchSelectMulti !== 'undefined') {
                        // Initially set placeholder option - Note: for multi-select we might not need an initial placeholder option as strict as single select if it causes issues with selection, assuming placeholder attribute handles display.
                        // But to be consistent with API search behavior:
                         searchSelectMulti.setChoices([
                            {
                                value: '',
                                label: 'Search for permissions...',
                                disabled: true
                            }
                        ], 'value', 'label', true);


                        searchSelectMulti.passedElement.element.addEventListener('search', async function(event) {
                             handleSearch(event, searchSelectMulti);
                        });
                    }

                } else {
                    console.error('Axios is not available');
                }
            }

            async function handleSearch(event, choicesInstance) {
                const searchTerm = event.detail.value;

                if (!searchTerm) {
                    choicesInstance.setChoices([
                        {
                            value: '',
                            label: 'Search for permissions...',
                            disabled: true
                        }
                    ], 'value', 'label', true);
                    return;
                }

                if (searchTerm.length < 2) {
                    // Don't clear choices immediately for better UX while typing, or clear if you strictly want min 2 chars
                     choicesInstance.setChoices([
                        {
                            value: '',
                            label: 'Type 2+ characters...',
                            disabled: true
                        }
                    ], 'value', 'label', true);
                    return;
                }

                try {
                    // Use internal permission search API
                    const response = await axios.get('/api/permissions/search', {
                        params: {
                            q: searchTerm,
                            limit: 20
                        }
                    });

                    if (response.data.success && response.data.data.length > 0) {
                        const permissions = response.data.data;

                        // Format for Choices.js
                        const newOptions = permissions.map(permission => ({
                            value: permission.value,
                            label: permission.label
                        }));

                        choicesInstance.setChoices(newOptions, 'value', 'label', true); // true = replaceChoices
                    } else {
                        choicesInstance.setChoices([
                            {
                                value: '',
                                label: 'No permissions found',
                                disabled: true
                            }
                        ], 'value', 'label', true);
                    }
                } catch (error) {
                    console.error('Error fetching permissions:', error);
                    choicesInstance.setChoices([
                        {
                            value: '',
                            label: 'Error loading permissions',
                            disabled: true
                        }
                    ], 'value', 'label', true);
                }
            }

            // Tambahkan pengecekan bahwa semua elemen dan dependencies tersedia
            if (typeof Choices !== 'undefined') {
                // Initialize searchSelect with API search capability
                initSearchSelect();
            } else {
                console.error('Choices.js is not loaded');
            }

            // SweetAlert Utilities Test Event Listeners
            if (typeof showSuccessMessage !== 'undefined') {
                document.getElementById('showSuccessBtn').addEventListener('click', function() {
                    showSuccessMessage('Success!', 'This is a success message.');
                });

                document.getElementById('showErrorBtn').addEventListener('click', function() {
                    showErrorMessage('Error!', 'This is an error message.');
                });

                document.getElementById('showWarningBtn').addEventListener('click', function() {
                    showWarningMessage('Warning!', 'This is a warning message.');
                });

                document.getElementById('showInfoBtn').addEventListener('click', function() {
                    showInfoMessage('Info', 'This is an info message.');
                });

                document.getElementById('showLoadingBtn').addEventListener('click', function() {
                    const loadingAlert = showLoadingMessage('Processing...', 'Please wait while we process your request.');

                    // Close loading after 3 seconds
                    setTimeout(() => {
                        Swal.close();
                    }, 3000);
                });

                document.getElementById('showConfirmationBtn').addEventListener('click', function() {
                    showConfirmation('Are you sure?', 'Do you want to proceed with this action?').then((result) => {
                        if (result.isConfirmed) {
                            showSuccessMessage('Confirmed!', 'You confirmed the action.');
                        } else {
                            showInfoMessage('Cancelled', 'Action was cancelled.');
                        }
                    });
                });

                document.getElementById('showDeleteConfirmationBtn').addEventListener('click', function() {
                    showDeleteConfirmation('Are you sure?', 'This action cannot be undone!', 'Yes, delete it!').then((result) => {
                        if (result.isConfirmed) {
                            showSuccessMessage('Deleted!', 'Item has been deleted.');
                        } else {
                            showInfoMessage('Cancelled', 'Item was not deleted.');
                        }
                    });
                });

                document.getElementById('showFormErrorsBtn').addEventListener('click', function() {
                    const errors = [
                        'The name field is required.',
                        'The email field must be a valid email address.',
                        'The password must be at least 8 characters.'
                    ];
                    showFormErrors(errors);
                });

                document.getElementById('handleAjaxResponseBtn').addEventListener('click', function() {
                    // Simulate a successful AJAX response
                    const successResponse = {
                        success: true,
                        title: 'Operation Successful!',
                        message: 'Your request was processed successfully.',
                        data: { id: 123 }
                    };

                    handleAjaxResponse(successResponse,
                        function(response) {
                            console.log('Success callback executed with data:', response.data);
                        },
                        function(response) {
                            console.log('Error callback executed with data:', response);
                        }
                    );

                    // To see error handling, uncomment below after success is shown
                    /*
                    setTimeout(() => {
                        const errorResponse = {
                            success: false,
                            title: 'Operation Failed!',
                            message: 'There was an issue processing your request.',
                            error: 'Internal server error'
                        };

                        handleAjaxResponse(errorResponse);
                    }, 2000);
                    */
                });

                document.getElementById('showBulkActionBtn').addEventListener('click', function() {
                    showBulkActionConfirmation('Delete', 5, 'user').then((result) => {
                        if (result.isConfirmed) {
                            showSuccessMessage('Success!', '5 users have been deleted.');
                        }
                    });
                });

                document.getElementById('showHtmlContentBtn').addEventListener('click', function() {
                    showInfoMessage('HTML Content', '<p>This alert contains <strong>HTML content</strong>!</p><ul><li>It supports lists</li><li><em>Formatting</em></li><li>And other HTML elements</li></ul>');
                });
            } else {
                console.error('SweetAlert utilities are not loaded');
            }

        });
    </script>
@endpush
