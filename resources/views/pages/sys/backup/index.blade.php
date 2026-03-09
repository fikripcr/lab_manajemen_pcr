@extends('layouts.tabler.app')

@section('title', 'Backup Management')

@section('header')
<x-tabler.page-header title="Backup Management" pretitle="Others">
    <x-slot:actions>
        <x-tabler.button href="{{ route('sys.dashboard') }}" type="back" />
        <x-tabler.button type="button" class="btn-outline-primary" onclick="createBackup('files')" text="Web Files Backup" icon="ti ti-files" />
        <x-tabler.button type="button" class="btn-primary" onclick="createBackup('db')" text="Database Backup" icon="ti ti-database" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

<x-tabler.card class="overflow-hidden">
    <x-tabler.card-header>
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'backups-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'backups-table'" />
            </div>
        </div>
    </x-tabler.card-header>

    <x-tabler.card-body class="p-0">
        @if (count($backups) > 0)
            <x-tabler.datatable-client
                id="backups-table"
                :columns="[
                    ['title' => 'Filename'],
                    ['title' => 'Size'],
                    ['title' => 'Date Modified'],
                    ['title' => 'Actions', 'orderable' => false, 'searchable' => false],
                ]">

                {{-- User controls the loop and variable names --}}
                @foreach ($backups as $backup)
                    <tr>
                        <td>{{ basename($backup['name']) }}</td>
                        <td>{{ $backup['formatted_size'] }}</td>
                        <td>{{ $backup['formatted_date'] }}</td>
                        <td>
                            <x-tabler.button href="{{ route('sys.backup.download', $backup['name']) }}" style="ghost-primary" icon="ti ti-download" icon-only="true" title="Download Backup" />
                            <x-tabler.button type="button" class="btn-action text-danger delete-backup" icon="ti ti-trash fs-2" icon-only="true" title="Delete" data-filename="{{ $backup['name'] }}" />
                        </td>
                    </tr>
                @endforeach

            </x-tabler.datatable-client>
        @else
            <div class="text-center py-5">
                <i class="ti ti-data mb-3" style="font-size: 3rem;"></i>
                <h5>No backups found</h5>
                <p class="text-muted">Create your first backup using the button above.</p>
            </div>
        @endif
    </x-tabler.card-body>
</x-tabler.card>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const createBackup = (type) => {
                const message = type === 'db'
                    ? 'Creating database backup, please wait...'
                    : 'Creating web files backup, please wait...';

                showLoadingMessage('Processing Backup...', message);

                axios.post('{{ route('sys.backup.store') }}', { type })
                    .then(({ data }) => {
                        if (data.success) {
                            showSuccessMessage('Success!', data.message).then(() => location.reload());
                        } else {
                            showErrorMessage('Error!', data.message);
                        }
                    })
                    .catch((error) => {
                        console.error('Error creating backup:', error);
                        showErrorMessage('Error!', 'An error occurred while creating the backup');
                    });
            };

            // Expose globally for button onclick
            window.createBackup = createBackup;

            // Delete backup with confirmation
            document.addEventListener('click', function(e) {
                const deleteBtn = e.target.closest('.delete-backup');
                if (!deleteBtn) return;
                e.preventDefault();
                const filename = deleteBtn.dataset.filename;

                showDeleteConfirmation('Are you sure?', "You won't be able to revert this!").then(({ isConfirmed }) => {
                    if (!isConfirmed) return;

                    const deleteUrl = '{{ route('sys.backup.destroy', ':filename') }}'.replace(':filename', encodeURIComponent(filename));

                    showLoadingMessage('Deleting...', 'Please wait');

                    axios.delete(deleteUrl)
                        .then(({ data }) => {
                            if (data.success) {
                                showSuccessMessage('Deleted!', data.message).then(() => location.reload());
                            } else {
                                showErrorMessage('Error!', data.message);
                            }
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
