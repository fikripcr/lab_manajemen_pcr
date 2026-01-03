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
            <div class="card mb-4">
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

            {{-- Choices.js Test --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Choices.js Test</h5>
                </div>
                <div class="card-body">
                    <div class="row">
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

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="searchSelect" class="form-label">Search Permissions by API (Single)</label>
                            <select id="searchSelect" class="form-select"></select>
                        </div>
                        <div class="col-md-6">
                            <label for="searchSelectMulti" class="form-label">Search Permissions by API (Multiple)</label>
                            <select id="searchSelectMulti" class="form-select" multiple></select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-primary" id="getChoicesValue">Get Selected Values</button>
                    </div>

                    <div class="mt-3">
                        <h6>Selection Preview:</h6>
                        <div id="choicesPreview" class="border p-3 bg-light"></div>
                    </div>
                </div>
            </div>

            {{-- SweetAlert Utilities Test --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">SweetAlert Utilities Test</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Basic Alerts</h6>
                            <button type="button" class="btn btn-primary mb-2" id="showSuccessBtn">Success Message</button>
                            <button type="button" class="btn btn-danger mb-2" id="showErrorBtn">Error Message</button>
                            <button type="button" class="btn btn-warning mb-2" id="showWarningBtn">Warning Message</button>
                            <button type="button" class="btn btn-info mb-2" id="showInfoBtn">Info Message</button>
                            <button type="button" class="btn btn-secondary mb-2" id="showLoadingBtn">Loading Message</button>
                        </div>
                        <div class="col-md-6">
                            <h6>Confirmation & Advanced Alerts</h6>
                            <button type="button" class="btn btn-success mb-2" id="showConfirmationBtn">Confirmation</button>
                            <button type="button" class="btn btn-danger mb-2" id="showDeleteConfirmationBtn">Delete Confirmation</button>
                            <button type="button" class="btn btn-primary mb-2" id="showFormErrorsBtn">Form Errors</button>
                            <button type="button" class="btn btn-info mb-2" id="handleAjaxResponseBtn">Handle AJAX Response</button>
                            <button type="button" class="btn btn-warning mb-2" id="showBulkActionBtn">Bulk Action Confirmation</button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>Custom Content Example:</h6>
                        <button type="button" class="btn btn-primary" id="showHtmlContentBtn">Alert with HTML Content</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            // Lazy load form features first
            if (typeof window.loadFormFeatures === 'function') {
                await window.loadFormFeatures();
                console.log('Form features loaded successfully');
            } else {
                console.error('loadFormFeatures is not available');
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
