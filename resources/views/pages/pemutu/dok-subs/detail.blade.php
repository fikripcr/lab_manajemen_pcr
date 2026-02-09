@extends('layouts.admin.app')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    SPMI / {{ $parent->judul }} / Sub Dokumen
                </div>
                <h2 class="page-title">
                    Detail Sub Dokumen
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('pemutu.dokumens.show', $parent->dok_id) }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-2"></i> Kembali ke {{ $parent->jenis }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <span class="badge badge-outline text-muted me-2">{{ $dokSub->seq }}</span>
                    {{ $dokSub->judul }}
                </h3>
                <div class="card-actions">
                    <a href="{{ route('pemutu.dok-subs.edit', $dokSub->doksub_id) }}" class="btn btn-icon btn-outline-primary" title="Edit Isi">
                        <i class="ti ti-pencil"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($dokSub->isi)
                    <div class="markdown">
                        {!! $dokSub->isi !!}
                    </div>
                @else
                    <div class="text-muted text-center fst-italic">
                        Belum ada konten isi. Klik edit untuk menambahkan.
                    </div>
                @endif
            </div>
        </div>

        @if($childType)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">List {{ $childType }} (Turunan)</h4>
                    <div class="card-actions">
                        <a href="#" class="btn btn-primary ajax-modal-btn" 
                           data-url="{{ route('pemutu.dokumens.create', ['parent_id' => $parent->dok_id, 'parent_doksub_id' => $dokSub->doksub_id]) }}" 
                           data-modal-title="Tambah {{ $childType }}">
                            <i class="ti ti-plus me-2"></i> Tambah {{ $childType }}
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th class="w-1">No</th>
                                <th>Judul {{ $childType }}</th>
                                <th>Kode</th>
                                <th class="w-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dokSub->childDokumens as $child)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('pemutu.dokumens.show', $child->dok_id) }}" class="fw-bold text-reset">
                                            {{ $child->judul }}
                                        </a>
                                    </td>
                                    <td>{{ $child->kode ?? '-' }}</td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <a href="#" class="btn btn-icon btn-sm btn-outline-secondary ajax-modal-btn" data-url="{{ route('pemutu.dokumens.edit', $child->dok_id) }}" data-modal-title="Edit {{ $childType }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                            <a href="#" class="btn btn-icon btn-sm btn-danger ajax-delete" data-url="{{ route('pemutu.dokumens.destroy', $child->dok_id) }}" data-title="Hapus?">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Belum ada data {{ $childType }}.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                Jenis dokumen ini ({{ strtoupper($parent->jenis) }}) tidak memiliki turunan dokumen lebih lanjut pada level ini.
            </div>
        @endif
    </div>
</div>
@endsection
