@extends('layouts.admin.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Inventory Details /</span> {{ $inventory->nama_alat }}</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Inventory Information</h5>
                    <div class="d-flex">
                        <a href="{{ route('inventories.edit', $inventory) }}" class="btn btn-primary me-2">
                            <i class='bx bx-edit me-1'></i> Edit
                        </a>
                        <form action="{{ route('inventories.destroy', $inventory) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this inventory item? All related data will be affected.')">
                                <i class='bx bx-trash me-1'></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <div class="button-wrapper">
                            <h4 class="mb-1">{{ $inventory->nama_alat }}</h4>
                            <p class="mb-1">{{ $inventory->jenis_alat }}</p>
                            <span class="badge bg-label-{{ $inventory->kondisi_terakhir === 'Baik' ? 'success' : ($inventory->kondisi_terakhir === 'Rusak Ringan' ? 'warning' : 'danger') }} me-1">
                                {{ $inventory->kondisi_terakhir }}
                            </span>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <h6 class="mb-2">Basic Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Equipment Name</strong></td>
                                    <td>{{ $inventory->nama_alat }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type</strong></td>
                                    <td>{{ $inventory->jenis_alat }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Condition</strong></td>
                                    <td>
                                        <span class="badge bg-label-{{ $inventory->kondisi_terakhir === 'Baik' ? 'success' : ($inventory->kondisi_terakhir === 'Rusak Ringan' ? 'warning' : 'danger') }}">
                                            {{ $inventory->kondisi_terakhir }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Lab</strong></td>
                                    <td>{{ $inventory->lab->name ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="mb-3 col-md-6">
                            <h6 class="mb-2">Additional Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Last Check Date</strong></td>
                                    <td>{{ $inventory->tanggal_pengecekan->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('inventories.index') }}" class="btn btn-secondary">
                            <i class='bx bx-arrow-back me-1'></i> Back to Inventories
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection