@extends('layouts.tabler.app')

@section('title', 'Backup Management')

@section('header')
<x-tabler.page-header title="Backup Management" pretitle="Others">
    <x-slot:actions>
        <x-tabler.button href="{{ route('sys.dashboard') }}" text="Kembali" icon="ti ti-arrow-left" class="btn-outline-secondary me-2" />
        <x-tabler.button type="button" class="btn-outline-primary" onclick="createBackup('files')" text="Web Files Backup" icon="ti ti-files" />
        <x-tabler.button type="button" class="btn-primary" onclick="createBackup('db')" text="Database Backup" icon="ti ti-database" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

<div class="card overflow-hidden">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'backups-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'backups-table'" />
            </div>
        </div>
    </div>

    <div class="card-body p-0">
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
                            <a href="{{ route('sys.backup.download', $backup['name']) }}"
                                class="btn btn-action text-primary"
                                title="Download">
                                <i class="ti ti-download fs-2"></i>
                            </a>
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

                axios.post('{{ route('sys.backup.store') }}', { type })
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
            document.addEventListener('click', function(e) {
                const deleteBtn = e.target.closest('.delete-backup');
                if (!deleteBtn) return;
                e.preventDefault();
                const filename = deleteBtn.dataset.filename;

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

                    const deleteUrl = '{{ route('sys.backup.destroy', ':filename') }}'.replace(':filename', encodeURIComponent(filename));

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
