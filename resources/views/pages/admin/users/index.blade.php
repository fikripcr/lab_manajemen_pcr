@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> User Management</h4>

    <div class="card">
        <div class="card-body">
            @include('components.flash-message')
            <div class="dt-container dt-bootstrap5">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="dt-length mb-3">
                            <label class="form-label">Show
                                <select name="users-table_length" aria-controls="users-table" class="form-select form-select-sm">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select> entries
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 d-flex justify-content-md-end">
                        <div class="dt-search">
                            <form method="GET" action="{{ route('users.index') }}" class="d-flex">
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Search users by name or email..."
                                       class="form-control form-control-sm"
                                       aria-controls="users-table">
                                <button type="submit" class="btn btn-sm btn-primary ms-2">
                                    <i class="bx bx-search"></i> Search
                                </button>
                                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary ms-2">
                                    <i class="bx bx-plus"></i> Add New User
                                </a>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>ID</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}"
                                                 alt="{{ $user->name }}" class="rounded-circle w-px-40 h-40">
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-nowrap">{{ $user->name }}</span>
                                            <small class="text-muted">{{ $user->created_at->format('M Y') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-label-primary me-1">{{ $user->roles->first()?->name ?? 'No Role' }}</span>
                                </td>
                                <td>{{ $user->npm ?? $user->nip ?? '-' }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('users.show', $user) }}"
                                           class="text-primary dropdown-item">
                                            <i class="bx bx-show me-1"></i> View
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="text-primary dropdown-item">
                                            <i class="bx bx-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-danger dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete this user? All their data will be permanently removed.')">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                        <i class="bx bx-user bx-lg text-muted mb-3"></i>
                                        <h5 class="mb-1">No users found</h5>
                                        <p class="text-muted">Get started by creating a new user.</p>
                                        <a href="{{ route('users.create') }}" class="btn btn-primary mt-2">
                                            <i class="bx bx-plus me-1"></i> Add New User
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7 d-flex justify-content-md-end">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                @if ($users->onFirstPage())
                                    <li class="page-item first">
                                        <a class="page-link" href="javascript:void(0);"
                                          ><i class="tf-icon bx bx-chevrons-left"></i
                                        ></a>
                                    </li>
                                    <li class="page-item prev">
                                        <a class="page-link" href="javascript:void(0);"
                                          ><i class="tf-icon bx bx-chevron-left"></i
                                        ></a>
                                    </li>
                                @else
                                    <li class="page-item first">
                                        <a class="page-link" href="{{ $users->url(1) }}"
                                          ><i class="tf-icon bx bx-chevrons-left"></i
                                        ></a>
                                    </li>
                                    <li class="page-item prev">
                                        <a class="page-link" href="{{ $users->previousPageUrl() }}"
                                          ><i class="tf-icon bx bx-chevron-left"></i
                                        ></a>
                                    </li>
                                @endif

                                @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                                    @if ($page == $users->currentPage())
                                        <li class="page-item active">
                                            <a class="page-link" href="javascript:void(0);">{{ $page }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                @if ($users->hasMorePages())
                                    <li class="page-item next">
                                        <a class="page-link" href="{{ $users->nextPageUrl() }}"
                                          ><i class="tf-icon bx bx-chevron-right"></i
                                        ></a>
                                    </li>
                                    <li class="page-item last">
                                        <a class="page-link" href="{{ $users->url($users->lastPage()) }}"
                                          ><i class="tf-icon bx bx-chevrons-right"></i
                                        ></a>
                                    </li>
                                @else
                                    <li class="page-item next">
                                        <a class="page-link" href="javascript:void(0);"
                                          ><i class="tf-icon bx bx-chevron-right"></i
                                        ></a>
                                    </li>
                                    <li class="page-item last">
                                        <a class="page-link" href="javascript:void(0);"
                                          ><i class="tf-icon bx bx-chevrons-right"></i
                                        ></a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
