@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">System /</span> Activity Log</h4>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">Activity Log</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x:datatable.page-length id="pageLength" selected="10" />
                    </div>
                </div>
            </div>
            @include('components.datatable.search-filter', [
                'dataTableId' => 'activity-log-table',
                'filters' => [
                    [
                        'id' => 'logNameFilter',
                        'name' => 'log_name',
                        'label' => 'Log Name',
                        'type' => 'select',
                        'column' => 3, // Log name column index
                        'options' => [
                            '' => 'All Logs',
                            'default' => 'Default',
                            'user' => 'User',
                            'system' => 'System',
                        ],
                        'placeholder' => 'Select Log Name'
                    ],
                    [
                        'id' => 'eventFilter',
                        'name' => 'event',
                        'label' => 'Event',
                        'type' => 'select',
                        'column' => 4, // Event column index
                        'options' => [
                            '' => 'All Events',
                            'created' => 'Created',
                            'updated' => 'Updated',
                            'deleted' => 'Deleted',
                            'restored' => 'Restored',
                        ],
                        'placeholder' => 'Select Event'
                    ]
                ]
            ])
        </div>
        <div class="card-body">
            <x-flash-message />

            <div class="table-responsive">
                <table id="activity-log-table" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Time</th>
                            <th>User</th>
                            <th>Log Name</th>
                            <th>Event</th>
                            <th>Subject</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Activity Detail Modal -->
    <div class="modal fade" id="activityDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Activity Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="activity-detail-content">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('components.sweetalert')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if DataTable is already initialized to avoid re-initialization
            if (!$.fn.DataTable.isDataTable('#activity-log-table')) {
                var table = $('#activity-log-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('activity-log.data') }}',
                        data: function(d) {
                            // Capture custom search from the filter component
                            var searchValue = $('#globalSearch-activity-log-table').val();
                            if (searchValue) {
                                d.search.value = searchValue;
                            }
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'causer.name',
                            name: 'causer.name'
                        },
                        {
                            data: 'log_name',
                            name: 'log_name'
                        },
                        {
                            data: 'event',
                            name: 'event'
                        },
                        {
                            data: 'subject_info',
                            name: 'subject_info'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    order: [
                        [0, 'desc']
                    ],
                    pageLength: 10,
                    responsive: true,
                    dom: 'rtip' // Only show table, info, and paging
                });

                // Handle page length change
                $(document).on('change', '#pageLength', function() {
                    var pageLength = parseInt($(this).val());
                    table.page.len(pageLength).draw();
                });

                // Handle search input from the filter component
                $(document).on('keyup', '#globalSearch-activity-log-table', function() {
                    table.search(this.value).draw();
                });
                
                // Handle activity detail modal
                $(document).on('click', '[data-bs-target="#activityDetailModal"]', function() {
                    var activityId = $(this).data('activity-id');
                    loadActivityDetails(activityId);
                });
            }
        });
        
        function loadActivityDetails(activityId) {
            $.get('{{ url("admin/activity-log") }}/' + activityId, function(response) {
                var activity = response.activity;
                var properties = response.properties;
                
                var content = '<div class="row">';
                content += '<div class="col-md-6"><strong>Time:</strong> ' + activity.created_at + '</div>';
                content += '<div class="col-md-6"><strong>Log Name:</strong> ' + activity.log_name + '</div>';
                content += '<div class="col-md-6"><strong>Event:</strong> ' + activity.event + '</div>';
                content += '<div class="col-md-6"><strong>User:</strong> ' + (activity.causer ? activity.causer.name : 'System') + '</div>';
                content += '<div class="col-md-12"><strong>Subject:</strong> ' + (activity.subject ? activity.subject_type + ': ' + activity.subject.name : 'N/A') + '</div>';
                content += '<div class="col-md-12"><strong>Description:</strong> ' + activity.description + '</div>';
                
                if (properties && Object.keys(properties).length > 0) {
                    content += '<div class="col-md-12 mt-3"><strong>Properties:</strong></div>';
                    content += '<div class="col-md-12">';
                    for (var key in properties) {
                        if (properties.hasOwnProperty(key)) {
                            content += '<div><strong>' + key + ':</strong> ';
                            if (typeof properties[key] === 'object') {
                                content += '<pre>' + JSON.stringify(properties[key], null, 2) + '</pre>';
                            } else {
                                content += properties[key];
                            }
                            content += '</div>';
                        }
                    }
                    content += '</div>';
                }
                
                content += '</div>';
                
                $('#activity-detail-content').html(content);
            }).fail(function() {
                $('#activity-detail-content').html('<div class="alert alert-danger">Failed to load activity details.</div>');
            });
        }
    </script>
@endpush