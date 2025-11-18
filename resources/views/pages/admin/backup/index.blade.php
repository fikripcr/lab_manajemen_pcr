@extends('layouts.admin.app')

@section('title', 'Backup Management')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Backup Management</h4>

    <div class="card">
        <div class="card-header align-items-center">
            <div class="row">
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary w-100" onclick="createBackup('files')">
                        <i class='bx bx-folder'></i> Web Files Backup
                    </button>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-success w-100" onclick="createBackup('db')">
                        <i class='bx bx-data'></i> Database Backup
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(count($backups) > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Filename</th>
                                <th>Size</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $backup)
                            <tr>
                                <td>{{ basename($backup['name']) }}</td>
                                <td>{{ $backup['formatted_size'] }}</td>
                                <td>{{ $backup['formatted_date'] }}</td>
                                <td>
                                    <a href="{{ route('admin.backup.download', basename($backup['name'])) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bx bx-download"></i> Download
                                    </a>
                                    <form action="{{ route('admin.backup.delete', basename($backup['name'])) }}" method="POST" class="d-inline">
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
</div>

<!-- JavaScript for Backup Functionality -->
<script>
function createBackup(type) {
    fetch('{{ route('admin.backup.create') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Reload the page to show the new backup
                location.reload();
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: data.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error creating backup:', error);
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while creating the backup',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}
</script>
@endsection
