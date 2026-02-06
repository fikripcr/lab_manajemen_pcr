@extends('layouts.admin.app')

@section('header')
<x-sys.page-header title="Semester" pretitle="Perkuliahan">
    <x-slot:actions>
        <x-sys.button type="button" icon="ti ti-plus" text="Create" id="createSemesterBtn" />
    </x-slot:actions>
</x-sys.page-header>
@endsection

@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-sys.datatable-page-length dataTableId="semesters-table" />
                </div>
                <div>
                    <x-sys.datatable-search dataTableId="semesters-table" />
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <x-tabler.flash-message />
            @php
                $columns = [
                    [
                        'title' => '#',
                        'data' => 'DT_RowIndex',
                        'name' => 'DT_RowIndex',
                        'orderable' => false,
                        'searchable' => false,
                        'className' => 'text-center'
                    ],
                    [
                        'title' => 'Tahun Ajaran',
                        'data' => 'tahun_ajaran',
                        'name' => 'tahun_ajaran'
                    ],
                    [
                        'title' => 'Semester',
                        'data' => 'semester',
                        'name' => 'semester'
                    ],
                    [
                        'title' => 'Start Date',
                        'data' => 'start_date',
                        'name' => 'start_date',
                    ],
                    [
                        'title' => 'End Date',
                        'data' => 'end_date',
                        'name' => 'end_date',
                    ],
                    [
                        'title' => 'Status',
                        'data' => 'is_active',
                        'name' => 'is_active'
                    ],
                    [
                        'title' => 'Actions',
                        'data' => 'action',
                        'name' => 'action',
                        'orderable' => false,
                        'searchable' => false,
                        'className' => 'text-end'
                    ]
                ];
            @endphp
            <x-sys.datatable id="semesters-table" :route="route('semesters.data')" :columns="$columns" :order="[[0, 'desc']]" />
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                $.get('{{ route('semesters.edit-modal.show', '') }}/' + semesterId, function(data) {
                    $('#modalContent').html(data);
                    $('#modalAction').modal('show');
                }).fail(function() {
                    Swal.fire('Error!', 'Could not load form', 'error');
                });
            });
        });
    </script>
@endpush
