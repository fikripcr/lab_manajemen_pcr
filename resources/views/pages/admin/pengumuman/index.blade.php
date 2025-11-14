@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Tables /</span> {{ ucfirst($type) }} Management</h4>
        <a href="{{ route($type . '.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Add New {{ ucfirst($type) }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">{{ ucfirst($type) }} List</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x:datatable.page-length id="{{ $type }}PageLength" selected="10" />
                    </div>
                </div>
            </div>
            @include('components.datatable.search-filter', [
                'dataTableId' => $type . '-table'
            ])
        </div>
        <div class="card-body">
            <x-flash-message />
            <div class="table-responsive">
                <table id="{{ $type }}-table" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cover</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Author</th>
                            <th>Created Date</th>
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
            if (!$.fn.DataTable.isDataTable('#{{ $type }}-table')) {
                var table = $('#{{ $type }}-table').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    ajax: {
                        url: '{{ route($type.'.data')}}',
                        data: function(d) {
                            // Capture custom search from the filter component
                            var searchValue = $('#globalSearch-{{ $type }}-table').val();
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
                            data: null,
                            name: 'cover_image',
                            render: function(data, type, row) {
                                var coverInfo = row.cover_image || {};
                                var imageUrl = coverInfo.url || "{{ asset('assets-guest/img/person/person-m-10.webp') }}";
                                return '<img src="' + imageUrl + '" alt="Cover" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">';
                            },
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'judul',
                            name: 'judul',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'is_published',
                            name: 'is_published',
                            orderable: true,
                            searchable: false
                        },
                        {
                            data: null,
                            name: 'penulis.name',
                            render: function(data, type, row) {
                                return row.penulis ? row.penulis.name : 'System';
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            orderable: true,
                            searchable: false
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
                    dom: 'rtip' // Only show table, info, and paging - hide default search and length inputs
                });

                // Handle page length change
                $(document).on('change', '#{{ $type }}PageLength', function() {
                    var pageLength = parseInt($(this).val());
                    table.page.len(pageLength).draw();
                });

                // Handle search input from the filter component
                $(document).on('keyup', '#globalSearch-{{ $type }}-table', function() {
                    table.search(this.value).draw();
                });
            }
        });
    </script>
@endpush
