@extends('layouts.sys.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">System Log/</span> Notification</h4>
    </div>

    <x-sys.flash-message />

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
            <div class="d-flex flex-wrap justify-content-between">
                <div class="d-flex flex-wrap gap-2 mb-2 mb-sm-0">
                    <div>
                        <x-sys.datatable-page-length :dataTableId="'notifications-table'" />
                    </div>
                    <div>
                        <x-sys.datatable-search-filter :dataTableId="'notifications-table'" />
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button id="markSelectedAsReadBtn" class="btn btn-primary btn-sm" disabled>
                        <i class="bx bx-check-double me-1"></i> Mark Selected as Read
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-sys.datatable id="notifications-table" route="{{ route('notifications.data') }}" Checkbox="true" checkboxKey="id"
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
            ]" />
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                        axios.post('{{ route('notifications.mark-selected-as-read') }}', {
                                ids: selectedIds
                            })
                            .then(function(response) {
                                if (response.data.success) {
                                    Swal.fire({
                                        title: 'Sukses!',
                                        text: response.data.message,
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
                            .catch(function(error) {
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
        });
    </script>
@endpush
