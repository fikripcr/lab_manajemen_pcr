@extends('layouts.sys.app')

@section('title', 'Backup Management')

@section('header')
<x-sys.page-header title="Backup Management" pretitle="Others">
    <x-slot:actions>
        <x-sys.button type="create" onclick="createBackup('files')" text="Web Files Backup" icon="ti ti-files" />
        <x-sys.button type="success" onclick="createBackup('db')" text="Database Backup" icon="ti ti-database" />
    </x-slot:actions>
</x-sys.page-header>
@endsection

@section('content')

<div class="card overflow-hidden">
    {{-- Card Header with Search and Page Length --}}


    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-sys.datatable-page-length :dataTableId="'backups-table'" />
            </div>
            <div>
                <x-sys.datatable-search :dataTableId="'backups-table'" />
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        @if (count($backups) > 0)
            <x-sys.datatable-client 
                id="backups-table" 
                :columns="[
                    ['title' => 'Filename'],
                    ['title' => 'Size'],
                    ['title' => 'Date Modified'],
                    ['title' => 'Actions', 'orderable' => false, 'searchable' => false],
                ]"
                :search="true" 
                :pageLength="10"
                :order="[[2, 'desc']]">
                
                {{-- User controls the loop and variable names --}}
                @foreach ($backups as $backup)
                    <tr>
                        <td>{{ basename($backup['name']) }}</td>
                        <td>{{ $backup['formatted_size'] }}</td>
                        <td>{{ $backup['formatted_date'] }}</td>
                        <td>
                            <a href="{{ route('sys.backup.download', $backup['name']) }}" 
                                class="btn btn-action text-primary" 
                                title="Download">
                                <i class="ti ti-download fs-2"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-action text-danger delete-backup" 
                                    data-filename="{{ $backup['name'] }}" 
                                    title="Delete">
                                <i class="ti ti-trash fs-2"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                
            </x-sys.datatable-client>
        @else
            <div class="text-center py-5">
                <i class="ti ti-data mb-3" style="font-size: 3rem;"></i>
                <h5>No backups found</h5>
                <p class="text-muted">Create your first backup using the button above.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const createBackup = (type) => {
                const message = type === 'db' 
                    ? 'Creating database backup, please wait...' 
                    : 'Creating web files backup, please wait...';
                
                showLoadingMessage('Processing Backup...', message);

                axios.post('{{ route('sys.backup.create') }}', { type })
                    .then(({ data }) => {
                        Swal.close();
                        data.success 
                            ? showSuccessMessage('Success!', data.message).then(() => location.reload())
                            : showErrorMessage('Error!', data.message);
                    })
                    .catch((error) => {
                        Swal.close();
                        console.error('Error creating backup:', error);
                        showErrorMessage('Error!', 'An error occurred while creating the backup');
                    });
            };

            // Expose globally for button onclick
            window.createBackup = createBackup;

            // Delete backup with confirmation
            $(document).on('click', '.delete-backup', function(e) {
                e.preventDefault();
                const filename = $(this).data('filename');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then(({ isConfirmed }) => {
                    if (!isConfirmed) return;

                    const deleteUrl = '{{ route('sys.backup.delete', ':filename') }}'.replace(':filename', encodeURIComponent(filename));
                    
                    axios.delete(deleteUrl)
                        .then(({ data }) => {
                            data.success
                                ? showSuccessMessage('Deleted!', data.message).then(() => location.reload())
                                : showErrorMessage('Error!', data.message);
                        })
                        .catch((error) => {
                            console.error('Error deleting backup:', error);
                            showErrorMessage('Error!', 'An error occurred while deleting the backup');
                        });
                });
            });
        });
    </script>
@endpush
