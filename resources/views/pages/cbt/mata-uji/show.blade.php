@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Detail Mata Uji: {{ $mu->nama_mata_uji }}" pretitle="CBT">
    <x-slot:actions>
        <x-tabler.button href="{{ route('cbt.mata-uji.index') }}" class="btn-outline-secondary" icon="ti ti-arrow-left" text="Kembali" />
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ti ti-plus me-2"></i> Tambah Soal
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item ajax-modal-btn" href="{{ route('cbt.soal.create', $mu->encrypted_mata_uji_id) }}?tipe_soal=Pilihan_Ganda" data-modal-size="modal-lg"><i class="ti ti-list-check me-2"></i> Pilihan Ganda</a></li>
                <li><a class="dropdown-item ajax-modal-btn" href="{{ route('cbt.soal.create', $mu->encrypted_mata_uji_id) }}?tipe_soal=Esai" data-modal-size="modal-lg"><i class="ti ti-file-description me-2"></i> Esai</a></li>
                <li><a class="dropdown-item ajax-modal-btn" href="{{ route('cbt.soal.create', $mu->encrypted_mata_uji_id) }}?tipe_soal=Benar_Salah" data-modal-size="modal-lg"><i class="ti ti-circle-check me-2"></i> Benar / Salah</a></li>
            </ul>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <div class="row row-cards">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Mata Uji</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama Mata Uji</label>
                            <div class="fw-bold">{{ $mu->nama_mata_uji }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Tipe</label>
                            <div><span class="badge bg-blue-lt">{{ $mu->tipe }}</span></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Deskripsi</label>
                            <div>{{ $mu->deskripsi ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Daftar Soal</h3>
                        <span class="badge bg-green-lt">{{ $mu->soal->count() }} Soal</span>
                    </div>
                    <div class="card-table">
                        <x-tabler.datatable-client
                            id="table-soal-mu"
                            :columns="[
                                ['name' => 'No', 'class' => 'w-1'],
                                ['name' => 'Pertanyaan'],
                                ['name' => 'Tipe'],
                                ['name' => 'Kesulitan'],
                                ['name' => 'Aksi', 'class' => 'w-1', 'sortable' => false]
                            ]"
                        >
                            @foreach($mu->soal as $index => $soal)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="text-truncate" style="max-width: 400px;">
                                        {!! strip_tags($soal->konten_pertanyaan) !!}
                                    </div>
                                </td>
                                <td>{{ $soal->tipe_soal }}</td>
                                <td>
                                    @php
                                        $badgeColor = match($soal->tingkat_kesulitan) {
                                            'Mudah' => 'success',
                                            'Sedang' => 'warning',
                                            'Sulit' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }}-lt">{{ $soal->tingkat_kesulitan }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('cbt.soal.edit', $soal->encrypted_soal_id) }}" class="btn btn-primary ajax-modal-btn" data-modal-size="modal-lg" title="Edit">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger ajax-delete" data-url="{{ route('cbt.soal.destroy', $soal->encrypted_soal_id) }}" title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </x-tabler.datatable-client>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Reload page on successful AJAX form submission (since we don't have server-side DT here)
    document.addEventListener('ajax-form:success', function() {
        // Only reload if we are in this specific page context
        if ($('#table-soal-mu').length) {
            location.reload();
        }
    });

    // Handle modal size reset when hidden (best practice for global modal)
    $(document).on('hidden.bs.modal', '#modalAction', function () {
        $(this).find('.modal-dialog').removeClass('modal-sm modal-lg modal-xl');
    });
});
</script>
@endpush
