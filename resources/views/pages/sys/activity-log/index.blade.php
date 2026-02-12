@extends('layouts.sys.app')

@section('title', 'Activity Log')

@section('header')
<x-tabler.page-header title="Activity Log" pretitle="System Log" />
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <div class="d-flex flex-wrap justify-content-between">
            <div class="d-flex flex-wrap gap-2 mb-2 mb-sm-0">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'activity-log-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'activity-log-table'" />
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <x-tabler.datatable
            id="activity-log-table"
            route="{{ route('activity-log.data') }}"
            :columns="[
                [
                    'title' => '#',
                    'data' => 'DT_RowIndex',
                    'name' => 'DT_RowIndex',
                    'orderable' => false,
                    'searchable' => false
                ],
                [
                    'title' => 'Time',
                    'data' => 'created_at',
                    'name' => 'created_at'
                ],
                [
                    'title' => 'User',
                    'data' => 'causer_name',
                    'name' => 'causer_name'
                ],
                [
                    'title' => 'Log Name',
                    'data' => 'log_name',
                    'name' => 'log_name'
                ],
                [
                    'title' => 'Description',
                    'data' => 'description',
                    'name' => 'description'
                ],
                [
                    'title' => 'Actions',
                    'data' => 'action',
                    'name' => 'action',
                    'orderable' => false,
                    'searchable' => false
                ]
            ]"
            :order="[[1, 'desc']]"
        />
    </div>
</div>

<!-- Activity Detail Modal -->
<div class="modal modal-blur" id="activityDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
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
                <x-tabler.button type="cancel" data-bs-dismiss="modal" class="btn-secondary" />
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle activity detail modal
        document.addEventListener('click', function(event) {
            var targetElement = event.target.closest('[data-bs-toggle="modal"][data-bs-target="#activityDetailModal"]');
            if (targetElement) {
                var activityId = targetElement.getAttribute('data-activity-id');
                if (activityId) {
                    loadActivityDetails(activityId);
                }
            }
        });

        function loadActivityDetails(activityId) {
            axios.get('/api/activity-logs/' + activityId)
                .then(function(response) {
                    if (response.data.status === 'success' && response.data.data) {
                        var activity = response.data.data.activity;
                        var properties = activity.properties || {};

                        var content = '<div class="row">';
                        content += '<div class="col-md-6"><strong>Time:</strong> ' + activity.created_at + '</div>';
                        content += '<div class="col-md-6"><strong>Log Name:</strong> ' + activity.log_name + '</div>';
                        content += '<div class="col-md-6"><strong>Event:</strong> ' + activity.event + '</div>';
                        content += '<div class="col-md-6"><strong>User:</strong> ' + (activity.causer ? activity.causer.name : 'System') + '</div>';
                        content += '<div class="col-md-12"><strong>Subject:</strong> ' + (activity.subject ? activity.subject_type + ': ' + activity.subject.name : 'N/A') + '</div>';
                        content += '<div class="col-md-12"><strong>Description:</strong> ' + activity.description + '</div>';

                        if (activity.properties && typeof activity.properties === 'object' && Object.keys(activity.properties).length > 0) {
                            content += '<div class="col-md-12 mt-3"><strong>Properties:</strong></div>';
                            content += '<div class="col-md-12">';
                            for (var key in activity.properties) {
                                if (activity.properties.hasOwnProperty(key)) {
                                    content += '<div><strong>' + key + ':</strong> ';
                                    if (typeof activity.properties[key] === 'object') {
                                        content += '<pre>' + JSON.stringify(activity.properties[key], null, 2) + '</pre>';
                                    } else {
                                        content += activity.properties[key];
                                    }
                                    content += '</div>';
                                }
                            }
                            content += '</div>';
                        }

                        content += '</div>';

                        document.getElementById('activity-detail-content').innerHTML = content;
                    } else {
                        document.getElementById('activity-detail-content').innerHTML = '<div class="alert alert-danger">Failed to load activity details.</div>';
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    document.getElementById('activity-detail-content').innerHTML = '<div class="alert alert-danger">Failed to load activity details.</div>';
                });
        }
    });
</script>
@endsection
