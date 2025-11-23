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
                    <p class="card-text text-muted">Send a test notification via email channel to your account <b>({{ auth()->user()->email }})</b></p>
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
                    <p class="card-text text-muted">Send a test notification via database channel to your account <b>({{ auth()->user()->email }})</b></p>
                    <button type="button" class="btn btn-success" onclick="testNotification()">
                        Send Test Notification
                    </button>
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
                    <h5 class="card-title">Test PDF Export</h5>
                    <p class="card-text text-muted">Generate a test PDF report with sample system data</p>
                    <button type="button" class="btn btn-danger" onclick="testPdfExport()">
                        Generate Test PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Test Notification API Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3  bg-opacity-10">
                        <i class="bx bx-data bx-lg text-info"></i>
                    </div>
                    <h5 class="card-title">Test Notification API</h5>
                    <p class="card-text text-muted">Test the notification API endpoints for count and list operations</p>
                    <button type="button" class="btn btn-info" onclick="testNotificationAPI()">
                        Test Notification API
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script>
        function showLoading(message = 'Processing...') {
            Swal.fire({
                title: 'Please wait...',
                text: message,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
        }

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
                    showLoading('Sending email...');

                    fetch('{{ route('sys.test.email') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.close();

                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Email Sent!',
                                    text: data.message,
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
                                    text: data.message
                                });
                            }
                        })
                        .catch(error => {
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
                    showLoading('Sending notification...');

                    fetch('{{ route('sys.test.notification') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.close();

                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Notification Sent!',
                                    text: data.message,
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
                                    text: data.message
                                });
                            }
                        })
                        .catch(error => {
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
                    showLoading('Generating PDF...');

                    fetch('{{ route('sys.test.pdf-export') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                // If it's a PDF response, create a link to download it
                                return response.blob().then(blob => {
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
                                });
                            } else {
                                return response.json().then(data => {
                                    Swal.close();
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: data.message
                                    });
                                });
                            }
                        })
                        .catch(error => {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while generating the PDF.'
                            });
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
            </div>
        `;
            } catch (error) {
                resultsDiv.innerHTML = `
            <div class="alert alert-danger">
                <h6>Error:</h6>
                <pre>${error.response?.data?.message || error.message}</pre>
            </div>
        `;
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
    </script>
@endpush
