@extends('layouts.sys.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <div class="page-pretitle">Others</div>
        <h2 class="page-title">Backup Management</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <button type="button" class="btn btn-primary" onclick="createBackup('files')">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" /></svg>
                Web Files Backup
            </button>
            <button type="button" class="btn btn-success" onclick="createBackup('db')">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 6m-8 0a8 3 0 1 0 16 0a8 3 0 1 0 -16 0" /><path d="M4 6v6a8 3 0 0 0 16 0v-6" /><path d="M4 12v6a8 3 0 0 0 16 0v-6" /></svg>
                Database Backup
            </button>
        </div>
    </div>
</div>
@endsection

@section('content')
<x-sys.flash-message />

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
