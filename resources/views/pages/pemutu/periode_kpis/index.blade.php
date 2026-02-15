@extends('layouts.admin.app')
@section('title', $pageTitle)

@section('header')
<x-tabler.page-header :title="$pageTitle" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <a href="#" 
           class="btn btn-primary ajax-modal-btn"
           data-url="{{ route('pemutu.periode-kpis.create') }}"
           data-modal-title="Tambah Periode KPI">
            <i class="ti ti-plus me-2"></i> Tambah Periode
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    @forelse($periodes as $periode)
        <div class="col-md-6 col-lg-4">
            <div class="card card-stacked">
                @if($periode->is_active)
                    <div class="card-status-top bg-success"></div>
                @else
                    <div class="card-status-top bg-secondary"></div>
                @endif

                <div class="card-header">
                    <h3 class="card-title">
                        {{ $periode->nama }}
                        @if($periode->is_active)
                            <span class="badge bg-success-lt ms-2">Aktif</span>
                        @endif
                    </h3>
                    <div class="card-actions">
                        <div class="dropdown">
                            <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item ajax-modal-btn" href="#" data-url="{{ route('pemutu.periode-kpis.edit', $periode->encrypted_periode_kpi_id) }}">
                                    <i class="ti ti-pencil me-2"></i> Edit
                                </a>
                                @if(!$periode->is_active)
                                    <a class="dropdown-item text-success activate-periode" href="#" data-url="{{ route('pemutu.periode-kpis.activate', $periode->encrypted_periode_kpi_id) }}">
                                        <i class="ti ti-check me-2"></i> Aktifkan
                                    </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger ajax-delete" href="#" 
                                   data-url="{{ route('pemutu.periode-kpis.destroy', $periode->encrypted_periode_kpi_id) }}"
                                   data-title="Hapus Periode?"
                                   data-text="Data periode dan seluruh data KPI terkait mungkin akan terpengaruh.">
                                    <i class="ti ti-trash me-2"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Tahun Akademik</div>
                            <div class="datagrid-content">{{ $periode->tahun_akademik }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Semester</div>
                            <div class="datagrid-content">{{ $periode->semester }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Tahun</div>
                            <div class="datagrid-content">{{ $periode->tahun }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Durasi</div>
                            <div class="datagrid-content">
                                {{ $periode->tanggal_mulai->translatedFormat('d M Y') }} - 
                                {{ $periode->tanggal_selesai->translatedFormat('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                   <div class="row align-items-center">
                      <div class="col-auto">
                         <span class="text-muted small">Dibuat: {{ $periode->created_at->diffForHumans() }}</span>
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
            :actionRoute="route('pemutu.periode-kpis.create')"
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
                
                Swal.fire({
                    title: 'Aktifkan Periode?',
                    text: 'Periode aktif lainnya akan otomatis dinonaktifkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Aktifkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(url)
                            .then(response => {
                                Swal.fire('Berhasil!', response.data.message, 'success')
                                    .then(() => window.location.reload());
                            })
                            .catch(error => {
                                Swal.fire('Gagal!', error.response?.data?.message || 'Terjadi kesalahan', 'error');
                            });
                    }
                });
            });
        });
    });
</script>
@endpush
