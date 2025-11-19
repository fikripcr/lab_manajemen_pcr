@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Perkuliahan /</span> Jadwal Kuliah</h4>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('jadwal.import.form') }}" class="btn btn-success me-2">
                <i class="bx bx-import me-1"></i> Import Jadwal
            </a>
            <a href="{{ route('jadwal.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Add New Schedule
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">Jadwal List</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x:datatable.page-length id="pageLength" selected="10" />
                    </div>
                </div>
            </div>
            @include('components.datatable.search-filter', [
                'dataTableId' => 'jadwal-table',
                'filters' => [
                    [
                        'id' => 'hariFilter',
                        'name' => 'hari',
                        'label' => 'Hari',
                        'type' => 'select',
                        'column' => 1, // Hari column index (we'll adjust the column names)
                        'options' => [
                            '' => 'All Days',
                            'Senin' => 'Senin',
                            'Selasa' => 'Selasa',
                            'Rabu' => 'Rabu',
                            'Kamis' => 'Kamis',
                            'Jumat' => 'Jumat',
                            'Sabtu' => 'Sabtu',
                            'Minggu' => 'Minggu'
                        ],
                        'placeholder' => 'Select Day'
                    ],
                    [
                        'id' => 'dosenFilter',
                        'name' => 'dosen',
                        'label' => 'Dosen',
                        'type' => 'select',
                        'column' => 5, // Dosen column index
                        'options' => [],
                        'placeholder' => 'Select Dosen'
                    ]
                ]
            ])
        </div>
        <div class="card-body">
            <x-flash-message />

            <div class="table-responsive">
                <table id="jadwal-table" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Hari</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Mata Kuliah</th>
                            <th>Dosen</th>
                            <th>Lab</th>
                            <th>Semester</th>
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
            // Check if DataTable is already initialized to avoid re-initialization
            if (!$.fn.DataTable.isDataTable('#jadwal-table')) {
                var table = $('#jadwal-table').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    ajax: {
                        url: '{{ route('jadwal.data') }}',
                        data: function(d) {
                            // Capture custom search from the filter component
                            var searchValue = $('#globalSearch-jadwal-table').val();
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
                            data: 'tanggal',
                            name: 'tanggal'
                        },
                        {
                            data: 'waktu_mulai',
                            name: 'waktu_mulai'
                        },
                        {
                            data: 'waktu_selesai',
                            name: 'waktu_selesai'
                        },
                        {
                            data: 'mata_kuliah.nama',
                            name: 'mata_kuliah.nama'
                        },
                        {
                            data: 'dosen.nama',
                            name: 'dosen.nama'
                        },
                        {
                            data: 'ruang',
                            name: 'ruang'
                        },
                        {
                            data: 'semester.tahun_ajaran',
                            name: 'semester.tahun_ajaran'
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

                // Setup common DataTable behaviors
                setupCommonDataTableBehaviors(table, {
                    searchInputSelector: '#globalSearch-jadwal-table',
                    pageLengthSelector: '#pageLength'
                });
            }
        });
    </script>
@endpush
