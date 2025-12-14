@extends('layouts.sys.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Others /</span> Test Features</h4>
    </div>

    <div class="row">
        <!-- Test Email Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3  bg-opacity-10">
                        <i class="bx bx-envelope bx-lg text-primary"></i>
                    </div>
                    <h5 class="card-title">Test Email</h5>
                    <p class="card-text text-muted">Send a notification via email to <b>({{ auth()->user()->email }})</b></p>
                    <button type="button" class="btn btn-primary" onclick="testEmail()">
                        Send Test Email
                    </button>
                </div>
            </div>
        </div>

        <!-- Test Notification Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3  bg-opacity-10">
                        <i class="bx bx-bell bx-lg text-success"></i>
                    </div>
                    <h5 class="card-title">Test Notification</h5>
                    <p class="card-text text-muted">Send a notification via database to <b>({{ auth()->user()->email }})</b></p>
                    <button type="button" class="btn btn-success" onclick="testNotification()">
                        Send Test Notification
                    </button>
                </div>
            </div>
        </div>

        <!-- Test JS Library Features Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3  bg-opacity-10">
                        <i class="bx bx-code bx-lg text-info"></i>
                    </div>
                    <h5 class="card-title">Test JS Library Features</h5>
                    <p class="card-text text-muted">Test various JS library features such as Flatpicker, TinyMCE, etc.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('sys.test.features') }}" class="btn btn-info">
                            Test JS Features
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test PDF Export Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3  bg-opacity-10">
                        <i class="bx bx-file bx-lg text-danger"></i>
                    </div>
                    <h5 class="card-title">Test PDF Export <a href="https://github.com/barryvdh/laravel-dompdf" target="_blank" class="ms-1 text-decoration-none" title="PDF Documentation"><i class="bx bx-link-external"></i></a></h5>
                    <p class="card-text text-muted">Generate a test PDF report with sample system data and QR code</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-danger" onclick="testPdfExport()">
                            Generate Test PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Excel Export Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3  bg-opacity-10">
                        <i class="bx bx-file-blank bx-lg text-success"></i>
                    </div>
                    <h5 class="card-title">Test Excel Export <a href="https://docs.laravel-excel.com/" target="_blank" class="ms-1 text-decoration-none" title="Excel Documentation"><i class="bx bx-link-external"></i></a></h5>
                    <p class="card-text text-muted">Generate a test Excel report with sample system data and QR code</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" onclick="testExcelExport()">
                            Generate Test Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Word Export Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3  bg-opacity-10">
                        <i class="bx bxl-microsoft bx-lg text-primary"></i>
                    </div>
                    <h5 class="card-title">Test Word Export <a href="https://phpword.readthedocs.io/" target="_blank" class="ms-1 text-decoration-none" title="Word Documentation"><i class="bx bx-link-external"></i></a></h5>
                    <p class="card-text text-muted">Generate a test Word document with sample system data and QR code</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" onclick="testWordExport()">
                            Generate Test Word
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test QR Code Generator Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3  bg-opacity-10">
                        <i class="bx bx-qr bx-lg text-secondary"></i>
                    </div>
                    <h5 class="card-title">Test QR Code Generator <a href="https://github.com/SimpleSoftwareIO/simple-qrcode" target="_blank" class="ms-1 text-decoration-none" title="QR Code Documentation"><i class="bx bx-link-external"></i></a></h5>
                    <p class="card-text text-muted">Generate QR codes with system data</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('sys.test.qrcode') }}" class="btn btn-secondary">
                            Test QR Code Features
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test DOCX Template Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3  bg-opacity-10">
                        <i class="bx bxl-microsoft bx-lg text-primary"></i>
                    </div>
                    <h5 class="card-title">Test DOCX Template <a href="https://phpword.readthedocs.io/" target="_blank" class="ms-1 text-decoration-none" title="PHPWord Documentation"><i class="bx bx-link-external"></i></a></h5>
                    <p class="card-text text-muted">Upload DOCX template with variables</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" onclick="testDocxTemplate()">
                            Upload & Process DOCX
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function testEmail() {
            Swal.fire({
                title: 'Send Test Email?',
                text: 'Are you sure you want to send a test email to your account?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, send it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoadingMessage('Sending email...');

                    axios.post('{{ route('sys.test.email') }}')
                        .then(function(response) {
                            Swal.close();

                            if (response.data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Email Sent!',
                                    text: response.data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Auto-reload after success
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.data.message
                                });
                            }
                        })
                        .catch(function(error) {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while sending the email.'
                            });
                        });
                }
            });
        }

        function testNotification() {
            Swal.fire({
                title: 'Send Test Notification?',
                text: 'Are you sure you want to send a test notification to your account?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, send it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoadingMessage('Sending notification...');

                    axios.post('{{ route('sys.test.notification') }}')
                        .then(function(response) {
                            Swal.close();

                            if (response.data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Notification Sent!',
                                    text: response.data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Auto-reload after success
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.data.message
                                });
                            }
                        })
                        .catch(function(error) {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while sending the notification.'
                            });
                        });
                }
            });
        }

        function testPdfExport() {
            Swal.fire({
                title: 'Generate Test PDF?',
                text: 'Are you sure you want to generate a test PDF with system data?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, generate it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoadingMessage('Generating PDF...');

                    axios({
                        method: 'POST',
                        url: '{{ route('sys.test.pdf-export') }}',
                        responseType: 'blob' // Important: tell axios to handle the response as a blob
                    })
                    .then(function(response) {
                        // If it's a PDF response, create a link to download it
                        const blob = new Blob([response.data], { type: 'application/pdf' });
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'test-report-' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.pdf';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);

                        Swal.close();

                        Swal.fire({
                            icon: 'success',
                            title: 'PDF Generated!',
                            text: 'Your test PDF has been downloaded successfully.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Auto-reload after success
                            location.reload();
                        });
                    })
                    .catch(function(error) {
                        // Check if error response is available
                        if (error.response) {
                            // If there's JSON error response
                            if (error.response.headers['content-type'] && error.response.headers['content-type'].includes('application/json')) {
                                error.response.data.text().then(function(text) {
                                    const jsonData = JSON.parse(text);
                                    Swal.close();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: jsonData.message
                                    });
                                });
                            } else {
                                // For other error responses
                                Swal.close();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'An error occurred while generating the PDF.'
                                });
                            }
                        } else {
                            // For network errors
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while generating the PDF.'
                            });
                        }
                    });
                }
            });
        }

        function testExcelExport() {
            Swal.fire({
                title: 'Generate Test Excel?',
                text: 'Are you sure you want to generate a test Excel with system data (Activity Logs)?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, generate it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoadingMessage('Generating Excel...');

                    axios({
                        method: 'POST',
                        url: '{{ route('sys.test.excel-export') }}',
                        responseType: 'blob' // Important: tell axios to handle the response as a blob
                    })
                    .then(function(response) {
                        // If it's an Excel response, create a link to download it
                        const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'activity-logs-' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.xlsx';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);

                        Swal.close();

                        Swal.fire({
                            icon: 'success',
                            title: 'Excel Generated!',
                            text: 'Your test Excel has been downloaded successfully.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Auto-reload after success
                            location.reload();
                        });
                    })
                    .catch(function(error) {
                        // Check if error response is available
                        if (error.response) {
                            // If there's JSON error response
                            if (error.response.headers['content-type'] && error.response.headers['content-type'].includes('application/json')) {
                                error.response.data.text().then(function(text) {
                                    const jsonData = JSON.parse(text);
                                    Swal.close();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: jsonData.message
                                    });
                                });
                            } else {
                                // For other error responses
                                Swal.close();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'An error occurred while generating the Excel.'
                                });
                            }
                        } else {
                            // For network errors
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while generating the Excel.'
                            });
                        }
                    });
                }
            });
        }

        function testWordExport() {
            Swal.fire({
                title: 'Generate Test Word?',
                text: 'Are you sure you want to generate a test Word document with system data and QR code?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, generate it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoadingMessage('Generating Word...');

                    axios({
                        method: 'POST',
                        url: '{{ route('sys.test.word-export') }}',
                        responseType: 'blob' // Important: tell axios to handle the response as a blob
                    })
                    .then(function(response) {
                        // If it's a Word response, create a link to download it
                        const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'test-report-' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.docx';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);

                        Swal.close();

                        Swal.fire({
                            icon: 'success',
                            title: 'Word Document Generated!',
                            text: 'Your test Word document has been downloaded successfully.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Auto-reload after success
                            location.reload();
                        });
                    })
                    .catch(function(error) {
                        // Check if error response is available
                        if (error.response) {
                            // If there's JSON error response
                            if (error.response.headers['content-type'] && error.response.headers['content-type'].includes('application/json')) {
                                error.response.data.text().then(function(text) {
                                    const jsonData = JSON.parse(text);
                                    Swal.close();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: jsonData.message
                                    });
                                });
                            } else {
                                // For other error responses
                                Swal.close();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'An error occurred while generating the Word document.'
                                });
                            }
                        } else {
                            // For network errors
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while generating the Word document.'
                            });
                        }
                    });
                }
            });
        }

        function testNotificationAPI() {
            // Open a modal or new window to show API testing interface
            Swal.fire({
                title: 'Notification API Test',
                html: `
                    <div class="text-start">
                        <p>Testing notification API endpoints:</p>
                        <ul>
                            <li><strong>Count API:</strong> GET /api/notifications/count</li>
                            <li><strong>List API:</strong> GET /api/notifications/list</li>
                        </ul>
                        <p>Click "Test" to make API calls using axios.</p>
                    </div>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button id="testCountBtn" class="btn btn-primary">Test Count</button>
                        <button id="testListBtn" class="btn btn-info">Test List</button>
                    </div>
                    <div id="apiResults" class="mt-3" style="max-height: 200px; overflow-y: auto;"></div>
                `,
                width: '60%',
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Close',
                willOpen: () => {
                    document.getElementById('testCountBtn').addEventListener('click', function() {
                        testNotificationCount();
                    });
                    document.getElementById('testListBtn').addEventListener('click', function() {
                        testNotificationList();
                    });
                }
            });
        }

        async function testNotificationCount() {
            const resultsDiv = document.getElementById('apiResults');
            resultsDiv.innerHTML = '<div class="text-center"><div class="spinner-border"></div>';

            axios.defaults.withCredentials = true;
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

            try {
                // WAJIB: tunggu sampai cookie Sanctum terpasang
                await axios.get('/sanctum/csrf-cookie');

                // Baru panggil API setelah cookie siap
                const response = await axios.get('/api/notifications/count');

                resultsDiv.innerHTML = `
                    <div class="alert alert-success">
                        <h6>Count API Response:</h6>
                        <pre>${JSON.stringify(response.data, null, 2)}</pre>
                    </div> `;
            } catch (error) {
                resultsDiv.innerHTML = `
                <div class="alert alert-danger">
                    <h6>Error:</h6>
                    <pre>${error.response?.data?.message || error.message}</pre>
                </div> `;
            }
        }


        function testNotificationList() {
            const resultsDiv = document.getElementById('apiResults');
            resultsDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p>Testing list API...</p></div>';

            axios.get('/api/notifications/list', {
                    params: {
                        per_page: 5
                    }
                })
                .then(response => {
                    resultsDiv.innerHTML = `
                        <div class="alert alert-success">
                            <h6>List API Response (first 5 items):</h6>
                            <pre>${JSON.stringify(response.data, null, 2)}</pre>
                        </div>
                    `;
                })
                .catch(error => {
                    resultsDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <h6>Error:</h6>
                            <pre>${error.response?.data?.message || error.message}</pre>
                        </div>
                    `;
                });
        }

        function testDocxTemplate() {
            // Create a modal dialog for DOCX template testing
            Swal.fire({
                title: 'Test DOCX Template',
                html: `
                    <div class="text-start">
                        <div class="alert alert-info">
                            <h6>Template Documentation:</h6>
                            <p class="mb-2">Your DOCX template can contain special variables that will be replaced with actual data:</p>
                            <ul class="mb-3">
                                <li>\${nama}</li>
                                <li>\${tanggal_lahir}</li>
                                <li>\${deskripsi}</li>
                                <li>\${pekerjaan}</li>
                                <li>\${perusahaan}</li>
                                <li>\${waktu_pembuatan}</li>
                                <li>\${keterangan}</li>
                            </ul>
                            <p>Example: "Employee: \${nama}, Position: \${pekerjaan} at \${perusahaan}"</p>
                        </div>
                        <div class="mb-3">
                            <label for="docxTemplateFile" class="form-label">Upload DOCX Template:</label>
                            <input type="file" id="docxTemplateFile" class="form-control" accept=".doc,.docx">
                            <div class="form-text">
                                Upload your DOCX template file with variables.
                                <a href="{{ Vite::asset('resources/assets/sys/template/template-docx-variable.docx') }}"
                                   class="btn btn-sm btn-outline-primary mt-1"
                                   target="_blank">
                                    <i class="bx bx-download"></i> Download Template Example
                                </a>
                            </div>
                        </div>
                    </div>
                `,
                width: '70%',
                showCancelButton: true,
                confirmButtonText: 'Process DOCX',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const templateFile = document.getElementById('docxTemplateFile').files[0];

                    // Validate inputs
                    if (!templateFile) {
                        Swal.showValidationMessage('Please upload a DOCX template file');
                        return false;
                    }

                    // Check file type
                    if (!templateFile.type.match('application/vnd.openxmlformats-officedocument.wordprocessingml.document') &&
                        !templateFile.name.toLowerCase().endsWith('.docx') &&
                        !templateFile.name.toLowerCase().endsWith('.doc')) {
                        Swal.showValidationMessage('Please upload a valid DOCX or DOC file');
                        return false;
                    }

                    // Create form data to send file
                    const formData = new FormData();
                    formData.append('template', templateFile);

                    return formData;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoadingMessage('Processing DOCX template...');

                    // Send the request to the server using form data
                    axios.post('{{ route('sys.test.docx-template') }}', result.value, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response => {
                        Swal.close();

                        // Show success message with download link
                        Swal.fire({
                            icon: 'success',
                            title: 'DOCX Processed!',
                            html: 'Your DOCX document has been processed successfully.<br>' +
                                  '<a href="' + response.data.data.download_url + '" target="_blank" class="btn btn-primary mt-2">Download Document</a>',
                            showConfirmButton: false,
                            timer: 5000
                        });
                    })
                    .catch(error => {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.response?.data?.message || 'An error occurred while processing the DOCX document.'
                        });
                    });
                }
            });
        }
    </script>
@endpush
