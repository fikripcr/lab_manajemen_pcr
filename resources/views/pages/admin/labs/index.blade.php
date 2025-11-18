@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Tabel /</span> Manajemen Laboratorium</h4>
        <a href="{{ route('labs.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Tambah Lab Baru
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">Daftar Laboratorium</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x:datatable.page-length id="pageLength" selected="10" />
                    </div>
                </div>
            </div>
            @include('components.datatable.search-filter', [
                'dataTableId' => 'labs-table'
            ])
        </div>
        <div class="card-body">
            <x-flash-message />
            <div class="table-responsive">
                <table id="labs-table" class="table " style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Lokasi</th>
                            <th>Kapasitas</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
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
            if (!$.fn.DataTable.isDataTable('#labs-table')) {
                var table = $('#labs-table').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    ajax: {
                        url: '{{ route('labs.data') }}',
                        data: function(d) {
                            // Capture custom search from the filter component
                            var searchValue = $('#globalSearch-labs-table').val();
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
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'location',
                            name: 'location'
                        },
                        {
                            data: null,
                            name: 'capacity',
                            render: function(data, type, row) {
                                return '<span class="badge bg-label-info me-1">' + row.capacity + ' Seats</span>';
                            }
                        },
                        {
                            data: 'description',
                            name: 'description',
                            render: function(data, type, row) {
                                return data && data.length > 50 ? data.substring(0, 50) + '...' : data;
                            }
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

                // Setup common DataTable behaviors
                setupCommonDataTableBehaviors(table, {
                    searchInputSelector: '#globalSearch-labs-table',
                    pageLengthSelector: '#pageLength'
                });
            }
        });
    </script>
    @include('components.sweetalert')
@endpush
