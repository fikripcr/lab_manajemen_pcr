@extends('layouts.admin.app')
@section('title', $pageTitle)

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Detail Rapat: {{ $rapat->judul_kegiatan }}
            </h2>
            <div class="text-muted mt-1">
                {{ $rapat->jenis_rapat }} &bull; {{ formatTanggalIndo($rapat->tgl_rapat) }}
            </div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('pemutu.rapat.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
                    <i class="ti ti-arrow-left me-1"></i>
                    Kembali
                </a>
                <a href="{{ route('pemutu.rapat.edit', $rapat) }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="ti ti-edit me-1"></i>
                    Edit
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
            <li class="nav-item">
                <a href="#tabs-info" class="nav-link active" data-bs-toggle="tab">Info & Absensi</a>
            </li>
            <li class="nav-item">
                <a href="#tabs-agenda" class="nav-link" data-bs-toggle="tab">Agenda & Pembahasan</a>
            </li>
            <li class="nav-item">
                <a href="#tabs-hasil" class="nav-link" data-bs-toggle="tab">Hasil & Laporan</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            {{-- TAB 1: INFO & ABSENSI --}}
            <div class="tab-pane active show" id="tabs-info">
                <div class="row row-cards">
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Dasar</h3>
                            </div>
                            <div class="card-body">
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Waktu</div>
                                        <div class="datagrid-content">{{ $rapat->waktu_mulai->format('H:i') }} - {{ $rapat->waktu_selesai->format('H:i') }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Tempat</div>
                                        <div class="datagrid-content">{{ $rapat->tempat_rapat }}</div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Ketua Rapat</div>
                                        <div class="datagrid-content">
                                            @if($rapat->ketuaUser)
                                                {{ $rapat->ketuaUser->name }}
                                            @else
                                                <span class="text-danger fst-italic">- Belum Diset -</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Notulen</div>
                                        <div class="datagrid-content">
                                            @if($rapat->notulenUser)
                                                {{ $rapat->notulenUser->name }}
                                            @else
                                                <span class="text-danger fst-italic">- Belum Diset -</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if(!$rapat->ketuaUser || !$rapat->notulenUser)
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Aksi</div>
                                        <div class="datagrid-content">
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modal-set-officials">
                                                <i class="ti ti-users me-1"></i> Set Pejabat Rapat
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Keterangan</div>
                                        <div class="datagrid-content">{{ $rapat->keterangan ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">Entitas Terkait</h3>
                            </div>
                             <div class="card-body">
                                @if($rapat->entitas->count() > 0)
                                    <ul class="list-group list-group-flush">
                                        @foreach($rapat->entitas as $entitas)
                                            <li class="list-group-item">
                                                <strong>{{ $entitas->model }}</strong>: {{ $entitas->model_id }}
                                                <br>
                                                <small class="text-muted">{{ $entitas->keterangan }}</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="text-muted text-center">Tidak ada entitas terkait.</div>
                                @endif
                             </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Hadir Peserta</h3>
                                <div class="card-actions">
                                    {{-- Optional: Button to invite more? --}}
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('pemutu.rapat.update-attendance', $rapat) }}" method="POST">
                                    @csrf
                                    <div class="table-responsive">
                                        <table class="table table-vcenter">
                                            <thead>
                                                <tr>
                                                    <th>Nama & Jabatan</th>
                                                    <th>Status Kehadiran</th>
                                                    <th>Waktu Hadir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($rapat->pesertas as $peserta)
                                                <tr>
                                                    <td>
                                                        <div class="font-weight-medium">{{ $peserta->user->name ?? 'User N/A' }}</div>
                                                        <div class="text-muted small">{{ $peserta->jabatan }}</div>
                                                    </td>
                                                    <td>
                                                        <select name="attendance[{{ $peserta->rapatpeserta_id }}][status]" class="form-select form-select-sm">
                                                            <option value="" {{ is_null($peserta->status) ? 'selected' : '' }}>- Belum Absen -</option>
                                                            <option value="hadir" {{ $peserta->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                            <option value="izin" {{ $peserta->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                                            <option value="sakit" {{ $peserta->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                                            <option value="alpa" {{ $peserta->status == 'alpa' ? 'selected' : '' }}>Alpa</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="time" name="attendance[{{ $peserta->rapatpeserta_id }}][waktu_hadir]" 
                                                            class="form-control form-control-sm"
                                                            value="{{ $peserta->waktu_hadir ? $peserta->waktu_hadir->format('H:i') : '' }}">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-check me-1"></i> Simpan Absensi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 2: AGENDA & PEMBAHASAN --}}
            <div class="tab-pane" id="tabs-agenda">
                <form action="{{ route('pemutu.rapat.update-agenda', $rapat) }}" method="POST">
                    @csrf
                    <div class="accordion" id="accordion-agenda">
                        @foreach($rapat->agendas as $index => $agenda)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-{{ $agenda->rapatagenda_id }}">
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $agenda->rapatagenda_id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                        {{ $loop->iteration }}. {{ $agenda->judul_agenda }}
                                    </button>
                                </h2>
                                <div id="collapse-{{ $agenda->rapatagenda_id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#accordion-agenda">
                                    <div class="accordion-body">
                                        <label class="form-label">Catatan Pembahasan / Hasil Agenda</label>
                                        <x-tabler.form-textarea 
                                            name="agendas[{{ $agenda->rapatagenda_id }}][isi]" 
                                            class="editor" 
                                            :value="$agenda->isi" 
                                            rows="5" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Pembahasan Agenda
                        </button>
                    </div>
                </form>
            </div>

            {{-- TAB 3: HASIL & LAPORAN --}}
            <div class="tab-pane" id="tabs-hasil">
                <div class="card card-body text-center py-5">
                    <h3 class="mb-3">Laporan Hasil Rapat</h3>
                    <p class="text-muted mb-4">
                        Unduh laporan hasil rapat lengkap dalam format PDF, mencakup informasi rapat, daftar hadir, dan hasil pembahasan agenda.
                    </p>
                    <div>
                        <a href="{{ route('pemutu.rapat.generate-pdf', $rapat) }}" class="btn btn-red btn-lg">
                            <i class="ti ti-file-type-pdf me-2"></i> Download PDF Hasil Rapat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

{{-- MODAL: Set Pejabat Rapat --}}
<div class="modal modal-blur fade" id="modal-set-officials" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Pejabat Rapat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pemutu.rapat.update-officials', $rapat) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Ketua Rapat</label>
                        <select class="form-select select2-modal" name="ketua_user_id" required>
                             <option value="" selected disabled>Pilih Ketua Rapat</option>
                             @foreach(\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Notulen Rapat</label>
                        <select class="form-select select2-modal" name="notulen_user_id" required>
                             <option value="" selected disabled>Pilih Notulen Rapat</option>
                             @foreach(\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                             @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- HugeRTE (already globally available or need init?) --}}
<script>
    // Initialize HugeRTE if not automatically done by class 'editor'
    // Assuming 'editor' class is handled by global script or x-tabler component
    // If not, we might need manual init here.
    // Based on previous knowledge, HugeRTE setup might be needed.
    
    // Check if user has provided specific HUGE RTE instruction or if standard 'editor' class works.
    // Default Tabler dashkit uses TinyMCE or similar.
    // Let's assume standard editor initialization.
</script>
@endpush
