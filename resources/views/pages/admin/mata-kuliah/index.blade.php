@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Mata Kuliah Management</h4>
        <a href="{{ route('mata-kuliah.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Add New Mata Kuliah
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @include('components.flash-message')

            <div class="table-responsive">
                <table id="mata-kuliah-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode MK</th>
                            <th>Nama MK</th>
                            <th>SKS</th>
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
            var table = $('#mata-kuliah-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('mata-kuliah.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'kode_mk',
                        name: 'kode_mk'
                    },
                    {
                        data: 'nama_mk',
                        name: 'nama_mk'
                    },
                    {
                        data: 'sks',
                        name: 'sks',
                        className: 'text-center'
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
