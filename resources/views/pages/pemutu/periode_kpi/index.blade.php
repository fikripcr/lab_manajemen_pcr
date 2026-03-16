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
        @php
            $themeColor = 'primary';
            $isOngoing = $periode->is_active && now()->between($periode->tanggal_mulai, $periode->tanggal_selesai);
        @endphp
        <div class="col-md-6 col-lg-4">
            <x-tabler.card class="card-stacked shadow-sm border-0 h-100 position-relative">
                {{-- Modern Accent Border --}}
                <div class="position-absolute top-0 start-0 h-100 border-start border-4 border-{{ $themeColor }}"></div>
                
                {{-- Card Stamp for Depth --}}
                <div class="card-stamp card-stamp-lg z-0">
                    <div class="card-stamp-icon bg-{{ $themeColor }} opacity-10">
                        <i class="ti ti-leaf"></i>
                    </div>
                </div>

                <x-tabler.card-header title="{{ $periode->nama }}" class="border-0 pb-0 z-1">
                    <span class="ms-3 d-flex align-items-center gap-2">
                         @if($isOngoing)
                            <span class="status-dot status-dot-animated status-{{ $themeColor }}"></span>
                            <span class="badge bg-{{ $themeColor }}-lt px-2">LIVE</span>
                        @elseif($periode->is_active)
                            <span class="badge bg-secondary-lt px-2">AKTIF</span>
                        @endif
                    </span>
                    <x-slot:actions>
                        <x-tabler.dropdown>
                            <x-tabler.dropdown-item type="edit" url="{{ route('pemutu.periode-kpi.edit', $periode->encrypted_periode_kpi_id) }}" />
                            @if(!$periode->is_active)
                                <x-tabler.dropdown-item 
                                    type="button" 
                                    class="text-success activate-periode border-top" 
                                    icon="ti ti-check-double"
                                    label="Aktifkan Sekarang"
                                    url="{{ route('pemutu.periode-kpi.activate', $periode->encrypted_periode_kpi_id) }}" 
                                    data-url="{{ route('pemutu.periode-kpi.activate', $periode->encrypted_periode_kpi_id) }}"
                                />
                            @endif
                            <x-tabler.dropdown-divider />
                            <x-tabler.dropdown-item 
                                type="delete" 
                                url="{{ route('pemutu.periode-kpi.destroy', $periode->encrypted_periode_kpi_id) }}"
                                title="Hapus Periode?"
                                text="Data periode dan seluruh data KPI terkait mungkin akan terpengaruh."
                            />
                        </x-tabler.dropdown>
                    </x-slot:actions>
                </x-tabler.card-header>

                <x-tabler.card-body class="p-4 pt-4 z-1">

                    <div class="p-3 rounded-3 bg-{{ $themeColor }}-lt border border-{{ $themeColor }} border-opacity-10">
                        <div class="text-{{ $themeColor }} small fw-bold text-uppercase mb-2 tracking-wider">Durasi Periode</div>
                        <div class="h4 mb-0 d-flex align-items-center gap-3">
                            <span class="fw-bold">{{ formatTanggalIndo($periode->tanggal_mulai) }}</span>
                            <span class="text-muted fw-normal opacity-50">/</span>
                            <span class="fw-bold">{{ formatTanggalIndo($periode->tanggal_selesai) }}</span>
                        </div>
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
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
