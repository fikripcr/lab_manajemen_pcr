@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Perkuliahan /</span>Semester</h4>
        <button type="button" class="btn btn-primary" id="createSemesterBtn">
            <i class="bx bx-plus"></i> Add New Semester
        </button>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">Semester List</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x:datatable.page-length id="pageLength" selected="10" />
                    </div>
                </div>
            </div>
            @include('components.datatable.search-filter', [
                'dataTableId' => 'semesters-table'
            ])
        </div>
        <div class="card-body">
            <x-flash-message />
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!$.fn.DataTable.isDataTable('#semesters-table')) {
                var table = $('#semesters-table').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    ajax: {
                        url: '{{ route('semesters.data') }}',
                        data: function(d) {
                            // Capture custom search from the filter component
                            var searchValue = $('#globalSearch-semesters-table').val();
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

                // Handle page length change
                $(document).on('change', '#pageLength', function() {
                    var pageLength = parseInt($(this).val());
                    table.page.len(pageLength).draw();
                });

                // Handle search input from the filter component
                $(document).on('keyup', '#globalSearch-semesters-table', function() {
                    table.search(this.value).draw();
                });
            }
        });
    </script>

    <!-- Modal container for create/edit operations -->
    <div class="modal fade" id="modalAction" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div id="modalContent">
                    <!-- Modal content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle create semester button click
        $('#createSemesterBtn').on('click', function() {
            $.get('{{ route('semesters.create-modal') }}', function(data) {
                $('#modalContent').html(data);
                $('#modalAction').modal('show');
            }).fail(function() {
                Swal.fire('Error!', 'Could not load form', 'error');
            });
        });

        // Handle edit semester - using event delegation for dynamically added elements
        $(document).on('click', '.edit-semester', function() {
            var semesterId = $(this).data('id');
            $.get('{{ route('semesters.edit-modal', '') }}/' + semesterId, function(data) {
                $('#modalContent').html(data);
                $('#modalAction').modal('show');
            }).fail(function() {
                Swal.fire('Error!', 'Could not load form', 'error');
            });
        });
    </script>

    @include('components.sweetalert')
@endpush
