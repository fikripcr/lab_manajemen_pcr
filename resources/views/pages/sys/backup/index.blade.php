@extends('layouts.sys.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Others/</span> Backup Management</h4>
    </div>

    <x-sys.flash-message />

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
            @if (count($backups) > 0)
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
            // Show loading Swal
            Swal.fire({
                title: 'Processing Backup...',
                text: type === 'db' ? 'Creating database backup, please wait...' : 'Creating web files backup, please wait...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post('{{ route('sys.backup.create') }}', {
                    type: type
                })
                .then(function(response) {
                    // Close the loading Swal
                    Swal.close();

                    if (response.data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload the page to show the new backup
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(function(error) {
                    // Close the loading Swal
                    Swal.close();
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
@endpush
