@extends('layouts.admin.app')

@section('title', 'Notifications')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Notifications /</span> List
    </h4>

    <!-- Success Message -->
    @include('components.flash-message')

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
                            <h4 class="mb-0" id="totalNotifications">{{ $notifications->total() }}</h4>
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
                            <h4 class="mb-0" id="readNotifications">{{ $notifications->total() - Auth::user()->unreadNotifications->count() }}</h4>
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
                    <button id="markSelectedAsReadBtn" class="btn btn-primary me-2" disabled>
                        <i class="bx bx-check-double me-1"></i> Mark Selected as Read
                    </button>
                    <div class="me-3 mb-2 mb-sm-0">
                        <x:datatable.page-length id="pageLength" selected="10" />
                    </div>
                    <div class="position-relative mb-2 mb-sm-0">
                        <div class="input-group">
                            <input type="text" id="globalSearch-notifications-table" class="form-control" placeholder="Search..." />
                            <span class="input-group-text"><i class="bx bx-search-alt bx-xs"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="notifications-table" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                                    <label class="form-check-label" for="selectAllCheckbox"></label>
                                </div>
                            </th>
                            <th>Status</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!$.fn.DataTable.isDataTable('#notifications-table')) {
                var table = $('#notifications-table').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    ajax: {
                        url: '{{ route('notifications.data') }}',
                        data: function(d) {
                            // Capture custom search from the filter component
                            var searchValue = $('#globalSearch-notifications-table').val();
                            if (searchValue) {
                                d.search.value = searchValue;
                            }
                        }
                    },
                    columns: [
                        {
                            data: 'checkbox',
                            name: 'checkbox',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'title',
                            name: 'title',
                        },
                        {
                            data: 'body',
                            name: 'body'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    order: [
                        [3, 'desc'] // Order by created_at (index 3) descending
                    ],
                    pageLength: 10,
                    responsive: true,
                    dom: 'rtip' // Only show table, info, and paging - hide default search and length inputs
                });

                // Handle page length change
                $(document).on('change', '#pageLength', function() {
                    var pageLength = parseInt($(this).val());
                    table.page.len(pageLength).draw();
                });

                // Handle search input
                $(document).on('keyup', '#globalSearch-notifications-table', function() {
                    table.search(this.value).draw();
                });

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
                        Swal.fire({
                            title: 'Info!',
                            text: 'Silakan pilih setidaknya satu notifikasi.',
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Konfirmasi',
                        text: `Apakah Anda yakin ingin menandai ${selectedIds.length} notifikasi sebagai telah dibaca?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, tandai sebagai telah dibaca',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
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
                                    table.ajax.reload();

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

                // Handle mark all as read button
                $(document).on('click', '#markAllAsReadBtn', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Apakah Anda yakin ingin menandai semua notifikasi sebagai telah dibaca?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, tandai semua',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('{{ route('notifications.mark-all-as-read') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
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
                                    table.ajax.reload();

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
            }
        });

        function updateNotificationStats() {
            fetch('{{ route('notifications.unread-count') }}')
                .then(response => response.json())
                .then(data => {
                    const unreadCount = data.count;
                    const totalCount = parseInt($('#totalNotifications').text());

                    $('#unreadNotifications').text(unreadCount);
                    $('#readNotifications').text(totalCount - unreadCount);
                })
                .catch(error => console.error('Error updating stats:', error));
        }
    </script>
    @include('components.sweetalert')

    <!-- Add the mark all as read button in a fixed position -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        @if(Auth::user()->unreadNotifications->count() > 0)
            <button id="markAllAsReadBtn" class="btn btn-primary me-2">
                <i class="bx bx-check-double me-1"></i> Mark All as Read
            </button>
        @endif
    </div>
@endpush