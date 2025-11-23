@extends('layouts.sys.app')

@section('title', 'Testing Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Page Title and Search -->
        <div class="col-12 mb-4">
            <div class="text-center mb-4">
                <h4 class="fw-bold">Testing Dashboard</h4>
                <p class="text-muted">Test system functionality including email, notifications, and PDF generation</p>
            </div>
        </div>

        <!-- Test Email Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar mx-auto mb-3  bg-opacity-10">
                        <i class="bx bx-envelope bx-lg text-primary"></i>
                    </div>
                    <h5 class="card-title">Test Email</h5>
                    <p class="card-text text-muted">Send a test notification via email channel to your account  <b>({{auth()->user()->email}})</b></p>
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
                    <p class="card-text text-muted">Send a test notification via database channel to your account <b>({{auth()->user()->email}})</b></p>
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

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    function testDatabase() {
        Swal.fire({
            title: 'Run Database Test?',
            text: 'This will run a basic database connectivity test.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, run test!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading('Running database test...');

                // Simulating database test - replace with actual functionality
                setTimeout(() => {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Database Test Passed!',
                        text: 'Database connectivity is working properly.'
                    });
                }, 1500);
            }
        });
    }

    function testSecurity() {
        Swal.fire({
            title: 'Run Security Test?',
            text: 'This will run a basic security configuration test.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, run test!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading('Running security test...');

                // Simulating security test - replace with actual functionality
                setTimeout(() => {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Security Test Passed!',
                        text: 'Security configurations are properly set.'
                    });
                }, 1500);
            }
        });
    }

    function testPerformance() {
        Swal.fire({
            title: 'Run Performance Test?',
            text: 'This will run a basic performance test.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, run test!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading('Running performance test...');

                // Simulating performance test - replace with actual functionality
                setTimeout(() => {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Performance Test Passed!',
                        text: 'System performance is within acceptable limits.'
                    });
                }, 1500);
            }
        });
    }
</script>
@endpush
