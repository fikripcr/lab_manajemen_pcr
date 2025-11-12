@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Tables /</span> {{ ucfirst($type) }} Management
    </h4>

    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center py-2">
            <h5 class="mb-2 mb-sm-0">{{ ucfirst($type) }} List</h5>
            <div class="d-flex flex-wrap gap-2">
                <div class="me-3 mb-2 mb-sm-0">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search {{ $type }}...">
                </div>
                <div class="me-3 mb-2 mb-sm-0">
                    <select id="pageLength" class="form-select form-select-sm">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <a href="{{ route($type.'.create') }}" class="btn btn-primary btn-sm mb-2 mb-sm-0">
                    <i class="bx bx-plus"></i> Add New {{ ucfirst($type) }}
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('components.flash-message')
            <div class="table-responsive">
                <table id="{{ $type }}-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
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
        $(document).ready(function() {
            var table = $('#{{ $type }}-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ $type === "pengumuman" ? route("pengumuman.data") : route("berita.data") }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
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
                order: [[0, 'desc']],
                pageLength: 10,
                responsive: true,
                dom: 'rtip' // Only show table, info, and paging - hide default search and length inputs
            });

            // Handle search input
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Handle page length change
            $('#pageLength').on('change', function() {
                var pageLength = parseInt($(this).val());
                table.page.len(pageLength).draw();
            });
        });
    </script>
@endpush
