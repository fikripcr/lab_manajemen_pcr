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
    <div class="card-body p-0">
        @if (count($backups) > 0)
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Filename</th>
                            <th>Size</th>
                            <th>Date Modified</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($backups as $backup)
                            <tr>
                                <td>{{ basename($backup['name']) }}</td>
                                <td>{{ $backup['formatted_size'] }}</td>
                                <td>{{ $backup['formatted_date'] }}</td>
                                <td>
                                    <a href="{{ route('sys.backup.download', $backup['name']) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bx bx-download"></i> Download
                                    </a>
                                    <form action="{{ route('sys.backup.delete', $backup['name']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this backup?')">
                                            <i class="bx bx-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bx bx-data mb-3" style="font-size: 3rem;"></i>
                <h5>No backups found</h5>
                <p class="text-muted">Create your first backup using the button above.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function createBackup(type) {
            // Show loading message
            showLoadingMessage('Processing Backup...', type === 'db' ? 'Creating database backup, please wait...' : 'Creating web files backup, please wait...');

            axios.post('{{ route('sys.backup.create') }}', {
                    type: type
                })
                .then(function(response) {
                    // Close the loading message
                    Swal.close();

                    if (response.data.success) {
                        showSuccessMessage('Success!', response.data.message).then(() => {
                            // Reload the page to show the new backup
                            location.reload();
                        });
                    } else {
                        showErrorMessage('Error!', response.data.message);
                    }
                })
                .catch(function(error) {
                    // Close the loading message
                    Swal.close();
                    console.error('Error creating backup:', error);
                    showErrorMessage('Error!', 'An error occurred while creating the backup');
                });
        }
    </script>
@endpush
