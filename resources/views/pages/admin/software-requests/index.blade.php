@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Software Request Management</h4>
    </div>

    <div class="card">
        <div class="card-body">
            @include('components.flash-message')

            <div class="table-responsive">
                <table id="software-requests-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Software</th>
                            <th>Dosen</th>
                            <th>Mata Kuliah</th>
                            <th>Status</th>
                            <th>Created At</th>
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
            var table = $('#software-requests-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('software-requests.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama_software',
                        name: 'nama_software'
                    },
                    {
                        data: 'dosen.name',
                        name: 'dosen.name',
                        render: function(data, type, row) {
                            return data || 'Guest';
                        }
                    },
                    {
                        data: 'mata_kuliah',
                        name: 'mata_kuliah'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return data ? moment(data).format('DD MMM YYYY') : '-';
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
                dom: 'rtip' // Only show table, info, and paging
            });
        });
    </script>
@endpush
