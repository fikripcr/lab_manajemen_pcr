
@extends('layouts.sys.app')

@section('title', 'Notifications')

@section('header')
    <x-tabler.page-header title="Notifications" pretitle="System Log" />
@endsection

@section('content')

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
                            <h4 class="mb-0" id="unreadNotifications">{{ auth()->user()->unreadNotifications->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3 bg-label-success rounded">
                            <i class="bx bx-check-circle bx-lg"></i>
                        </div>
                        <div>
                            <p class="mb-0">Read</p>
                            <h4 class="mb-0" id="readNotifications">{{ auth()->user()->notifications()->count() - auth()->user()->unreadNotifications->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between">
                <div class="d-flex flex-wrap gap-2 mb-2 mb-sm-0">
                    <div>
                        <x-tabler.datatable-page-length :dataTableId="'notifications-table'" />
                    </div>
                    <div>
                        <x-tabler.datatable-search :dataTableId="'notifications-table'" />
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <x-tabler.button type="create" id="markSelectedAsReadBtn" class="btn-sm" icon="ti ti-check-double" text="Mark Selected as Read" disabled />
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <x-tabler.datatable id="notifications-table" route="{{ route('notifications.data') }}" checkbox="true"
            :columns="[
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
            ]" :order="[[3, 'desc']]" />
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Use CustomDataTables instance for checkbox tracking
            const notificationsTable = window['DT_notifications-table'];

            // Update button state when checkboxes change
            $(document).on('change', '.select-row', function() {
                toggleMarkSelectedButton();
            });

            $(document).on('change', '#selectAll-notifications-table', function() {
                toggleMarkSelectedButton();
            });

            // Enable/disable "Mark Selected as Read" button
            function toggleMarkSelectedButton() {
                const checkedCount = $('.select-row:checked').length;
                $('#markSelectedAsReadBtn').prop('disabled', checkedCount === 0);
            }

            // Handle mark selected as read button
            $(document).on('click', '#markSelectedAsReadBtn', function(e) {
                e.preventDefault();

                const selectedIds = $('.select-row:checked').map(function() {
                    return $(this).data('id');
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
                        axios.post('{{ route('notifications.mark-selected-as-read') }}', {
                                ids: selectedIds
                            })
                            .then(function(response) {
                                if (response.data.success) {
                                    showSuccessMessage('Sukses!', response.data.message);

                                    // Reload the table to reflect changes
                                    if (notificationsTable && notificationsTable.table) {
                                        notificationsTable.table.ajax.reload();
                                    }

                                    // Update stats
                                    updateNotificationStats();
                                } else {
                                    showErrorMessage('Error!', 'Gagal menandai notifikasi sebagai telah dibaca');
                                }
                            })
                            .catch(function(error) {
                                console.error('Error:', error);
                                showErrorMessage('Error!', 'Terjadi kesalahan saat menandai notifikasi sebagai telah dibaca');
                            });
                    }
                });
            });

            function updateNotificationStats() {
                // Update notification counts
                // Check if global notification state exists and use it if recently updated
                if (window.notificationState && window.notificationState.cache) {
                    // Use cached data from header to prevent duplicate requests
                    const cachedCount = window.notificationState.cache.count;
                    $('#totalNotifications').text(cachedCount); // This is a simplification

                    // For detailed stats, we still need to call the specific endpoint
                    axios.get('{{ route('notifications.counts') }}')
                        .then(function(response) {
                            if (response.data.success) {
                                const counts = response.data.counts;

                                $('#totalNotifications').text(counts.total);
                                $('#unreadNotifications').text(counts.unread);
                                $('#readNotifications').text(counts.read);
                            }
                        })
                        .catch(function(error) {
                            console.error('Error updating stats:', error);
                        });
                } else {
                    // If no global state, make direct request
                    axios.get('{{ route('notifications.counts') }}')
                        .then(function(response) {
                            if (response.data.success) {
                                const counts = response.data.counts;

                                $('#totalNotifications').text(counts.total);
                                $('#unreadNotifications').text(counts.unread);
                                $('#readNotifications').text(counts.read);
                            }
                        })
                        .catch(function(error) {
                            console.error('Error updating stats:', error);
                        });
                }
            }
        });
    </script>
@endpush
