@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Lab Management</h4>

    <div class="card">
        <div class="card-body">
            <div class="dt-container dt-bootstrap5">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="dt-length mb-3">
                            <label class="form-label">Show
                                <select name="labs-table_length" aria-controls="labs-table" class="form-select form-select-sm">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select> entries
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 d-flex justify-content-md-end">
                        <div class="dt-search">
                            <form method="GET" action="{{ route('labs.index') }}" class="d-flex">
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Search labs by name, location or description..."
                                       class="form-control form-control-sm"
                                       aria-controls="labs-table">
                                <button type="submit" class="btn btn-sm btn-primary ms-2">
                                    <i class="bx bx-search"></i> Search
                                </button>
                                <a href="{{ route('labs.create') }}" class="btn btn-sm btn-primary ms-2">
                                    <i class="bx bx-plus"></i> Add New Lab
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
                                <th>Location</th>
                                <th>Capacity</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($labs as $lab)
                            <tr>
                                <td><span class="fw-medium">{{ $lab->name }}</span></td>
                                <td>{{ $lab->location }}</td>
                                <td>
                                    <span class="badge bg-label-info me-1">{{ $lab->capacity }} Seats</span>
                                </td>
                                <td>{{ Str::limit($lab->description, 50) }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('labs.show', $lab) }}"
                                           class="text-primary dropdown-item">
                                            <i class="bx bx-show me-1"></i> View
                                        </a>
                                        <a href="{{ route('labs.edit', $lab) }}"
                                           class="text-primary dropdown-item">
                                            <i class="bx bx-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('labs.destroy', $lab) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-danger dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete this lab? All related data will be affected.')">
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
                                        <i class="bx bx-home bx-lg text-muted mb-3"></i>
                                        <h5 class="mb-1">No labs found</h5>
                                        <p class="text-muted">Get started by creating a new lab.</p>
                                        <a href="{{ route('labs.create') }}" class="btn btn-primary mt-2">
                                            <i class="bx bx-plus me-1"></i> Add New Lab
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
                            Showing {{ $labs->firstItem() }} to {{ $labs->lastItem() }} of {{ $labs->total() }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7 d-flex justify-content-md-end">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                @if ($labs->onFirstPage())
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
                                        <a class="page-link" href="{{ $labs->url(1) }}"
                                          ><i class="tf-icon bx bx-chevrons-left"></i
                                        ></a>
                                    </li>
                                    <li class="page-item prev">
                                        <a class="page-link" href="{{ $labs->previousPageUrl() }}"
                                          ><i class="tf-icon bx bx-chevron-left"></i
                                        ></a>
                                    </li>
                                @endif

                                @foreach ($labs->getUrlRange(max(1, $labs->currentPage() - 2), min($labs->lastPage(), $labs->currentPage() + 2)) as $page => $url)
                                    @if ($page == $labs->currentPage())
                                        <li class="page-item active">
                                            <a class="page-link" href="javascript:void(0);">{{ $page }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                @if ($labs->hasMorePages())
                                    <li class="page-item next">
                                        <a class="page-link" href="{{ $labs->nextPageUrl() }}"
                                          ><i class="tf-icon bx bx-chevron-right"></i
                                        ></a>
                                    </li>
                                    <li class="page-item last">
                                        <a class="page-link" href="{{ $labs->url($labs->lastPage()) }}"
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
