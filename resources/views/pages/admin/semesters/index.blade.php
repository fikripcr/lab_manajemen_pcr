@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Perkuliahan /</span>Semester</h4>
        <a href="{{ route('semesters.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Add New Semester
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">User List</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x:datatable.page-length id="pageLength" selected="10" />
                    </div>
                </div>
            </div>
            @include('components.datatable.search-filter', [
                'dataTableId' => 'users-table'
            ])
        </div>
        <div class="card-body">
            @include('components.flash-message')

            <div class="table-responsive">
                <table id="semesters-table" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tahun Ajaran</th>
                            <th>Semester</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    @include('components.sweetalert')

    <script>
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#semesters-table')) {
                var table = $('#semesters-table').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    ajax: '{{ route('semesters.data') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'tahun_ajaran',
                            name: 'tahun_ajaran'
                        },
                        {
                            data: 'semester',
                            name: 'semester'
                        },
                        {
                            data: 'start_date',
                            name: 'start_date',
                        },
                        {
                            data: 'end_date',
                            name: 'end_date',
                        },
                        {
                            data: 'is_active',
                            name: 'is_active'
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
            }
        });
    </script>
@endpush
