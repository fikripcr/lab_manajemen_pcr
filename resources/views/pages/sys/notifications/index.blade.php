@extends('layouts.admin.app')

@section('title', 'Notifications')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Notifications /</span> List
        </h4>

        <!-- Success Message -->
        <x-flash-message />

        <!-- Notifications Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3 bg-label-primary rounded">
                                <i class="bx bx-bell bx-lg"></i>
                            </div>
                            <div>
                                <p class="mb-0">Total Notifications</p>
                                <h4 class="mb-0" id="totalNotifications">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3 bg-label-warning rounded">
                                <i class="bx bx-envelope bx-lg"></i>
                            </div>
                            <div>
                                <p class="mb-0">Unread</p>
                                <h4 class="mb-0" id="unreadNotifications">{{ Auth::user()->unreadNotifications->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3 bg-label-success rounded">
                                <i class="bx bx-check-circle bx-lg"></i>
                            </div>
                            <div>
                                <p class="mb-0">Read</p>
                                <h4 class="mb-0" id="readNotifications">{{ Auth::user()->notifications()->count() - Auth::user()->unreadNotifications->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                    <h5 class="mb-2 mb-sm-0">Notification List</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <button id="markSelectedAsReadBtn" class="btn btn-primary btn-sm me-2" disabled>
                            <i class="bx bx-check-double me-1"></i> Mark Selected as Read
                        </button>
                        <button id="sendTestNotificationBtn" class="btn btn-success btn-sm me-2" onclick="sendTestNotification()">
                            <i class="bx bx-bell me-1"></i> Test Notification
                        </button>
                        <div class="me-3 mb-2 mb-sm-0">
                            <x-datatable.page-length id="pageLength" selected="10" />
                        </div>
                    </div>
                </div>
                @include('components.datatable.search-filter', [
                    'dataTableId' => 'notifications-table',
                ])
            </div>
            <div class="card-body">
                <x-flash-message />

                <x-datatable.datatable id="notifications-table" route="{{ route('notifications.data') }}" withCheckbox="true" checkboxKey="id" :columns="[
                    [
                        'title' => 'Status',
                        'data' => 'status',
                        'name' => 'status',
                        'orderable' => false,
                        'searchable' => false,
                    ],
                    [
                        'title' => 'Title',
                        'data' => 'title',
                        'name' => 'title',
                    ],
                    [
                        'title' => 'Message',
                        'data' => 'body',
                        'name' => 'body',
                    ],
                    [
                        'title' => 'Date',
                        'data' => 'created_at',
                        'name' => 'created_at',
                    ],
                    [
                        'title' => 'Actions',
                        'data' => 'action',
                        'name' => 'action',
                        'orderable' => false,
                        'searchable' => false,
                    ],
                ]" />
            </div>
        </div>
    </div>

    <script>
        // Handle select all checkboxes
        $('#selectAllCheckbox').on('change', function() {
            $('.notification-checkbox').prop('checked', this.checked);
            toggleMarkSelectedButton();
        });

        // Handle individual checkbox changes
        $(document).on('change', '.notification-checkbox', function() {
            if (!this.checked) {
                $('#selectAllCheckbox').prop('checked', false);
            }
            toggleMarkSelectedButton();
        });

        // Enable/disable "Mark Selected as Read" button
        function toggleMarkSelectedButton() {
            const checkedCount = $('.notification-checkbox:checked').length;
            $('#markSelectedAsReadBtn').prop('disabled', checkedCount === 0);
        }

        // Handle mark selected as read button
        $(document).on('click', '#markSelectedAsReadBtn', function(e) {
            e.preventDefault();

            const selectedIds = $('.notification-checkbox:checked').map(function() {
                return this.value;
            }).get();

            if (selectedIds.length === 0) {
                showInfoMessage('Info!', 'Silakan pilih setidaknya satu notifikasi.');
                return;
            }

            showConfirmation(
                'Konfirmasi',
                `Apakah Anda yakin ingin menandai ${selectedIds.length} notifikasi sebagai telah dibaca?`,
                'Ya, tandai sebagai telah dibaca'
            ).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('notifications.mark-selected-as-read') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                ids: selectedIds
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Sukses!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });

                                // Reload the table to reflect changes
                                $('#notifications-table').DataTable().ajax.reload();

                                // Update stats
                                updateNotificationStats();
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Gagal menandai notifikasi sebagai telah dibaca',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menandai notifikasi sebagai telah dibaca',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        });

        function updateNotificationStats() {
            // Update notification counts
            fetch('{{ route('notifications.counts') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const counts = data.counts;

                        $('#totalNotifications').text(counts.total);
                        $('#unreadNotifications').text(counts.unread);
                        $('#readNotifications').text(counts.read);
                    }
                })
                .catch(error => console.error('Error updating stats:', error));
        }

        // Function to send test notification to current user
        function sendTestNotification() {
            // Show loading indicator
            Swal.fire({
                title: 'Processing...',
                text: 'Sending test notification...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route('notifications.test') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: 'database'
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        title: 'Sukses!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reload the table to show the new notification
                        $('#notifications-table').DataTable().ajax.reload();
                        // Update stats
                        updateNotificationStats();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Gagal mengirim notifikasi',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat mengirim notifikasi',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    </script>
@endsection
