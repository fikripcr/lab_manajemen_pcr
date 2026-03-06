@extends('layouts.tabler.app')
@section('title', $pageTitle)

@section('header')
<x-tabler.page-header :title="$pageTitle" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button type="create" href="#" class="ajax-modal-btn" data-url="{{ route('pemutu.periode-kpi.create') }}" data-modal-title="Tambah Periode KPI" text="Tambah Periode" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    @forelse($periodes as $periode)
        <div class="col-md-6 col-lg-4">
            <div class="card card-md shadow-sm border-0 border-top border-3 @if($periode->is_active) border-success @else border-secondary @endif overflow-hidden h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div>
                        <div class="text-uppercase text-muted font-weight-bold tracking-widest small mb-1">Semester {{ $periode->semester }}</div>
                        <h2 class="card-title h2 mb-0 @if($periode->is_active) text-success @endif">
                            {{ $periode->nama }}
                        </h2>
                    </div>
                    <div class="card-actions">
                        <div class="dropdown">
                            <a href="#" class="btn btn-icon btn-ghost-secondary rounded-circle dropdown-toggle no-caret" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                <a class="dropdown-item ajax-modal-btn" href="#" data-url="{{ route('pemutu.periode-kpi.edit', $periode->encrypted_periode_kpi_id) }}">
                                    <i class="ti ti-pencil me-2 text-muted"></i> Edit Periode
                                </a>
                                @if(!$periode->is_active)
                                    <a class="dropdown-item text-success activate-periode" href="#" data-url="{{ route('pemutu.periode-kpi.activate', $periode->encrypted_periode_kpi_id) }}">
                                        <i class="ti ti-check-double me-2"></i> Aktifkan Sekarang
                                    </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger ajax-delete" href="#" 
                                   data-url="{{ route('pemutu.periode-kpi.destroy', $periode->encrypted_periode_kpi_id) }}"
                                   data-title="Hapus Periode?"
                                   data-text="Data periode dan seluruh data KPI terkait mungkin akan terpengaruh.">
                                    <i class="ti ti-trash me-2"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-6 text-center">
                            <div class="h3 mb-0">{{ $periode->tahun_akademik }}</div>
                            <div class="text-muted small text-uppercase">Tahun Akademik</div>
                        </div>
                        <div class="col-6 text-center border-start">
                            <div class="h3 mb-0">{{ $periode->tahun }}</div>
                            <div class="text-muted small text-uppercase">Tahun</div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small"><i class="ti ti-calendar-time me-1"></i> Durasi Periode</span>
                            @if($periode->is_active && now()->between($periode->tanggal_mulai, $periode->tanggal_selesai))
                                <span class="badge badge-outline text-success border-success badge-pill">Sedang Berlangsung</span>
                            @elseif($periode->is_active && now()->isAfter($periode->tanggal_selesai))
                                <span class="badge badge-outline text-danger border-danger badge-pill">Selesai (Aktif)</span>
                            @endif
                        </div>
                        <div class="p-3 rounded bg-light border border-dashed text-center">
                            <div class="font-weight-bold">
                                {{ $periode->tanggal_mulai->translatedFormat('d M Y') }} 
                                <span class="text-muted mx-2">&mdash;</span>
                                {{ $periode->tanggal_selesai->translatedFormat('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <x-tabler.empty-state
            title="Belum ada Periode KPI"
            text="Silakan tambahkan periode baru untuk memulai siklus penilaian kinerja."
            icon="ti ti-calendar-stats"
            actionClass="btn-primary ajax-modal-btn"
            :actionRoute="route('pemutu.periode-kpi.create')"
            actionText="Tambah Periode"
        />
    @endforelse
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Activate Periode Logic using SweetAlert & Axios
        document.querySelectorAll('.activate-periode').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.dataset.url;
                
                showConfirmation(
                    'Aktifkan Periode?',
                    'Periode aktif lainnya akan otomatis dinonaktifkan.',
                    'Ya, Aktifkan'
                ).then((result) => {
                    if (result.isConfirmed) {
                        showLoadingMessage('Memproses...', 'Sedang mengaktifkan periode');
                        axios.post(url)
                            .then(response => {
                                showSuccessMessage('Berhasil!', response.data.message)
                                    .then(() => window.location.reload());
                            })
                            .catch(error => {
                                showErrorMessage('Gagal!', error.response?.data?.message || 'Terjadi kesalahan');
                            });
                    }
                });
            });
        });
    });
</script>
@endpush
