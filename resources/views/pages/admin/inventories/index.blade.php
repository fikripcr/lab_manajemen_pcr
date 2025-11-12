@extends('layouts.admin.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Inventory Management</h4>

    <div class="card">
        <div class="card-body">
            @include('components.flash-message')
            
            <div class="dt-container dt-bootstrap5">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="dt-length mb-3">
                            <label class="form-label">Show 
                                <select name="inventories-table_length" aria-controls="inventories-table" class="form-select form-select-sm">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select> entries
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 d-flex justify-content-md-end">
                        <div class="dt-search">
                            <form method="GET" action="{{ route('inventories.index') }}" class="d-flex">
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Search inventories by name, type or lab..." 
                                       class="form-control form-control-sm" 
                                       aria-controls="inventories-table">
                                <button type="submit" class="btn btn-sm btn-primary ms-2">
                                    <i class="bx bx-search"></i> Search
                                </button>
                                <a href="{{ route('inventories.create') }}" class="btn btn-sm btn-primary ms-2">
                                    <i class="bx bx-plus"></i> Add New Inventory
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Equipment Name</th>
                                <th>Type</th>
                                <th>Condition</th>
                                <th>Lab</th>
                                <th>Last Check</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($inventories as $inventory)
                            <tr>
                                <td><span class="fw-medium">{{ $inventory->nama_alat }}</span></td>
                                <td>
                                    <span class="badge bg-label-info me-1">{{ $inventory->jenis_alat }}</span>
                                </td>
                                <td>{{ $inventory->kondisi_terakhir }}</td>
                                <td>{{ $inventory->lab->name ?? '-' }}</td>
                                <td>{{ $inventory->tanggal_pengecekan->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('inventories.show', $inventory) }}"
                                           class="text-primary dropdown-item">
                                            <i class="bx bx-show me-1"></i> View
                                        </a>
                                        <a href="{{ route('inventories.edit', $inventory) }}"
                                           class="text-primary dropdown-item">
                                            <i class="bx bx-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('inventories.destroy', $inventory) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-danger dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete this inventory item?')">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                        <i class="bx bx-package bx-lg text-muted mb-3"></i>
                                        <h5 class="mb-1">No inventory found</h5>
                                        <p class="text-muted">Get started by creating a new inventory item.</p>
                                        <a href="{{ route('inventories.create') }}" class="btn btn-primary mt-2">
                                            <i class="bx bx-plus me-1"></i> Add New Inventory
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
                            Showing {{ $inventories->firstItem() }} to {{ $inventories->lastItem() }} of {{ $inventories->total() }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7 d-flex justify-content-md-end">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                @if ($inventories->onFirstPage())
                                    <li class="page-item first">
                                        <a class="page-link" href="javascript:void(0);">
                                            <i class="tf-icon bx bx-chevrons-left"></i>
                                        </a>
                                    </li>
                                    <li class="page-item prev">
                                        <a class="page-link" href="javascript:void(0);">
                                            <i class="tf-icon bx bx-chevron-left"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item first">
                                        <a class="page-link" href="{{ $inventories->url(1) }}">
                                            <i class="tf-icon bx bx-chevrons-left"></i>
                                        </a>
                                    </li>
                                    <li class="page-item prev">
                                        <a class="page-link" href="{{ $inventories->previousPageUrl() }}">
                                            <i class="tf-icon bx bx-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                @foreach ($inventories->getUrlRange(max(1, $inventories->currentPage() - 2), min($inventories->lastPage(), $inventories->currentPage() + 2)) as $page => $url)
                                    @if ($page == $inventories->currentPage())
                                        <li class="page-item active">
                                            <a class="page-link" href="javascript:void(0);">{{ $page }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                @if ($inventories->hasMorePages())
                                    <li class="page-item next">
                                        <a class="page-link" href="{{ $inventories->nextPageUrl() }}">
                                            <i class="tf-icon bx bx-chevron-right"></i>
                                        </a>
                                    </li>
                                    <li class="page-item last">
                                        <a class="page-link" href="{{ $inventories->url($inventories->lastPage()) }}">
                                            <i class="tf-icon bx bx-chevrons-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item next">
                                        <a class="page-link" href="javascript:void(0);">
                                            <i class="tf-icon bx bx-chevron-right"></i>
                                        </a>
                                    </li>
                                    <li class="page-item last">
                                        <a class="page-link" href="javascript:void(0);">
                                            <i class="tf-icon bx bx-chevrons-right"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection