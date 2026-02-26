@extends('layouts.tabler.app')

@section('title', $pageTitle)

@section('header')
<x-tabler.page-header :title="$pageTitle" pretitle="Kegiatan / Rapat / {{ $rapat->exists ? 'Edit' : 'Jadwalkan' }}">
    <x-slot:actions>
        <x-tabler.button type="back" href="{{ route('Kegiatan.rapat.index') }}" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form class="ajax-form" action="{{ $rapat->exists ? route('Kegiatan.rapat.update', $rapat->encrypted_rapat_id) : route('Kegiatan.rapat.store') }}" method="POST">
            @csrf
            @if($rapat->exists)
                @method('PUT')
            @endif

            <div class="row">
                {{-- KOLOM KIRI: DATA UMUM --}}
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-info-circle me-2"></i>Data Umum
                            </h3>
                        </div>
                        <div class="card-body">
                            <x-tabler.flash-message />

                            <div class="mb-3">
                                <x-tabler.form-input
                                    name="jenis_rapat"
                                    label="Jenis Rapat"
                                    type="text"
                                    value="{{ old('jenis_rapat', $rapat->jenis_rapat) }}"
                                    placeholder="Contoh: Rapat Koordinasi, Rapat Tinjauan Manajemen"
                                    required="true"
                                />
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-input
                                    name="judul_kegiatan"
                                    label="Judul Kegiatan"
                                    type="text"
                                    value="{{ old('judul_kegiatan', $rapat->judul_kegiatan) }}"
                                    placeholder="Masukkan judul kegiatan"
                                    required="true"
                                />
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <x-tabler.form-input
                                        name="tgl_rapat"
                                        label="Tanggal Rapat"
                                        type="date"
                                        value="{{ old('tgl_rapat', $rapat->exists ? $rapat->tgl_rapat?->format('Y-m-d') : ($defaultDate ?? date('Y-m-d'))) }}"
                                        required="true"
                                    />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <x-tabler.form-input
                                        name="waktu_mulai"
                                        label="Waktu Mulai"
                                        type="time"
                                        value="{{ old('waktu_mulai', $rapat->exists ? $rapat->waktu_mulai?->format('H:i') : ($defaultStartTime ?? date('H:i'))) }}"
                                        required="true"
                                    />
                                </div>
                                <div class="col-6">
                                    <x-tabler.form-input
                                        name="waktu_selesai"
                                        label="Waktu Selesai"
                                        type="time"
                                        value="{{ old('waktu_selesai', $rapat->exists ? $rapat->waktu_selesai?->format('H:i') : ($defaultEndTime ?? date('H:i', strtotime('+2 hours')))) }}"
                                        required="true"
                                    />
                                </div>
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-input
                                    name="tempat_rapat"
                                    label="Tempat Rapat"
                                    type="text"
                                    value="{{ old('tempat_rapat', $rapat->tempat_rapat) }}"
                                    placeholder="Contoh: Ruang Rapat Utama, Zoom Meeting"
                                    required="true"
                                />
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-textarea
                                    name="keterangan"
                                    label="Keterangan Tambahan"
                                    value="{{ old('keterangan', $rapat->keterangan) }}"
                                    placeholder="Informasi tambahan (opsional)"
                                    rows="2"
                                />
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <x-tabler.button type="submit" :text="$rapat->exists ? 'Simpan Perubahan' : 'Jadwalkan Rapat'" icon="ti ti-check" />
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: AGENDA & PESERTA --}}
                <div class="col-lg-7">
                    {{-- AGENDA CARD --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-list-check me-2"></i>Agenda Rapat
                                @if($rapat->exists)
                                    <span class="badge bg-primary text-white ms-2">{{ $rapat->agendas->count() }}</span>
                                @endif
                            </h3>
                        </div>
                        <div class="card-body">
                            <div id="agenda-container">
                                @if($rapat->exists && $rapat->agendas->count() > 0)
                                    @foreach($rapat->agendas->sortBy('seq') as $index => $agenda)
                                        <div class="agenda-item card mb-2">
                                            <div class="card-body py-2">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <span class="badge bg-primary rounded-pill">{{ $loop->iteration }}</span>
                                                    </div>
                                                    <div class="col">
                                                        <x-tabler.form-input name="agendas[{{ $index }}][judul_agenda]" value="{{ $agenda->judul_agenda }}" placeholder="Judul Agenda" required="true" />
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button" class="btn btn-icon btn-sm btn-danger remove-agenda" title="Hapus Agenda">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="agenda-item card mb-2">
                                        <div class="card-body py-2">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="badge badge-primary rounded-pill">#1</span>
                                                </div>
                                                <div class="col">
                                                    <input type="text" 
                                                           name="agendas[0][judul_agenda]" 
                                                           class="form-control form-control-sm fw-bold" 
                                                           placeholder="Judul Agenda"
                                                           required>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="button" class="btn btn-icon btn-sm btn-danger remove-agenda" title="Hapus Agenda">
                                                        <i class="ti ti-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <button type="button" id="add-agenda-btn" class="btn btn-sm btn-secondary mt-2">
                                <i class="ti ti-plus me-1"></i> Tambah Agenda
                            </button>
                        </div>
                    </div>

                    {{-- PESERTA CARD --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-users me-2"></i>Peserta Rapat
                                @if($rapat->exists)
                                    <span class="badge bg-success text-white ms-2">{{ $rapat->pesertas->count() }}</span>
                                @endif
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Undang Peserta</label>
                                <select name="participants[]" 
                                        id="select-participants" 
                                        class="form-select select2-multiple" 
                                        multiple="multiple"
                                        data-placeholder="Pilih peserta yang akan diundang...">
                                    @foreach($users as $user)
                                        @php
                                            $pegawaiName = $user->pegawai?->nama ?? $user->name;
                                            $nip = $user->pegawai?->nip ?? '-';
                                        @endphp
                                        <option value="{{ $user->id }}" 
                                                {{ $rapat->exists && $rapat->pesertas->contains('user_id', $user->id) ? 'selected' : '' }}>
                                            {{ $pegawaiName }} ({{ $nip }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Ctrl+Click (Windows) atau Cmd+Click (Mac) untuk pilih banyak</small>
                            </div>

                            <div class="mb-3">
                                <x-tabler.form-input
                                    name="jabatan_peserta"
                                    label="Jabatan dalam Rapat"
                                    type="text"
                                    value="{{ old('jabatan_peserta', 'Peserta') }}"
                                    placeholder="Contoh: Peserta, Narasumber"
                                />
                            </div>

                            @if($rapat->exists && $rapat->pesertas->count() > 0)
                                <div class="mt-3">
                                    <h6 class="card-title mb-2">Peserta Terundang</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-vcenter">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Jabatan</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($rapat->pesertas as $peserta)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <span class="avatar avatar-xs me-2 rounded-circle bg-light text-muted">
                                                                    {{ strtoupper(substr($peserta->user->name ?? '?', 0, 1)) }}
                                                                </span>
                                                                <div class="text-truncate" style="max-width: 150px;">
                                                                    {{ $peserta->user->name ?? 'User N/A' }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span class="badge bg-light text-dark">{{ $peserta->jabatan }}</span></td>
                                                        <td>
                                                            @if($peserta->is_invited)
                                                                <span class="badge bg-green-lt">
                                                                    <i class="ti ti-check me-1"></i>Terkirim
                                                                </span>
                                                            @else
                                                                <span class="badge bg-yellow-lt">
                                                                    <i class="ti ti-clock me-1"></i>Belum
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">
                                                            <button type="button" 
                                                                    class="btn btn-icon btn-sm btn-primary resend-invite-btn"
                                                                    data-peserta-id="{{ $peserta->encrypted_rapatpeserta_id }}"
                                                                    data-peserta-name="{{ $peserta->user->name }}"
                                                                    title="Kirim ulang undangan">
                                                                <i class="ti ti-mail"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let agendaCounter = {{ $rapat->exists && $rapat->agendas->count() > 0 ? $rapat->agendas->count() : 1 }};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for participants
    if (window.loadSelect2) {
        window.loadSelect2().then(() => {
            $('.select2-multiple').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih peserta yang akan diundang...',
                allowClear: true
            });
        });
    }

    // Add agenda button
    document.getElementById('add-agenda-btn')?.addEventListener('click', function() {
        const container = document.getElementById('agenda-container');
        const newAgenda = document.createElement('div');
        newAgenda.className = 'agenda-item card mb-2';
        newAgenda.innerHTML = `
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="badge bg-primary rounded-pill">${agendaCounter + 1}</span>
                    </div>
                    <div class="col">
                        <input type="text" 
                               name="agendas[${agendaCounter}][judul_agenda]" 
                               class="form-control form-control-sm fw-bold" 
                               placeholder="Judul Agenda"
                               required>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-icon btn-sm btn-danger remove-agenda" title="Hapus Agenda">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newAgenda);
        agendaCounter++;
        updateAgendaNumbers();
    });

    // Remove agenda button (event delegation)
    document.getElementById('agenda-container')?.addEventListener('click', function(e) {
        if (e.target.closest('.remove-agenda')) {
            const agendaItem = e.target.closest('.agenda-item');
            if (document.querySelectorAll('.agenda-item').length > 1) {
                agendaItem.remove();
                updateAgendaNumbers();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Minimal 1 Agenda',
                    text: 'Rapat harus memiliki minimal 1 agenda',
                });
            }
        }
    });

    // Update agenda numbers
    function updateAgendaNumbers() {
        document.querySelectorAll('.agenda-item').forEach((item, index) => {
            const badge = item.querySelector('.badge');
            if (badge) badge.textContent = index + 1;
        });
    }

    // Resend invitation button
    document.querySelectorAll('.resend-invite-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const pesertaId = this.dataset.pesertaId;
            const pesertaName = this.dataset.pesertaName;
            
            Swal.fire({
                title: 'Kirim Ulang Undangan?',
                text: `Kirim ulang undangan email ke ${pesertaName}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#206bc4',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`{{ route('Kegiatan.rapat.peserta.resend-invite', '__ID__') }}`.replace('__ID__', pesertaId))
                        .then((response) => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.data.message || 'Undangan berhasil dikirim ulang',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000,
                            });
                        })
                        .catch((error) => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: error.response?.data?.message || 'Gagal mengirim ulang undangan',
                            });
                        });
                }
            });
        });
    });

    // Form success handler
    document.addEventListener('form-success', function(e) {
        if (e.detail.redirect) {
            window.location.href = e.detail.redirect;
        }
    });
});
</script>
@endpush

@push('styles')
<style>
    .select2-container--bootstrap-5 .select2-selection--multiple {
        min-height: 38px;
    }
</style>
@endpush
