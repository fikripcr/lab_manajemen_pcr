{{-- Partial for RTM Peningkatan Content --}}
@php $idSuffix = $typeId; @endphp

<div class="row row-cards">
    {{-- ══ KIRI: Tabs Data Umum + Peserta ══ --}}
    <div class="col-md-6">
        <x-tabler.card shadow="none" class="border">
            <x-tabler.card-header>
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" id="rtm-tabs-{{ $idSuffix }}">
                    <li class="nav-item">
                        <a href="#rtm-tab-umum-{{ $idSuffix }}" class="nav-link active" data-bs-toggle="tab">
                            <i class="ti ti-info-circle me-1"></i> Data Umum
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#rtm-tab-peserta-{{ $idSuffix }}" class="nav-link" data-bs-toggle="tab">
                            <i class="ti ti-users me-1"></i> Peserta
                            <span class="badge bg-green-lt ms-1">{{ $rapat->pesertas->count() }}</span>
                        </a>
                    </li>
                </ul>
            </x-tabler.card-header>
            <x-tabler.card-body>
                <div class="tab-content">
                    {{-- ── TAB: Data Umum ── --}}
                    <div class="tab-pane active show" id="rtm-tab-umum-{{ $idSuffix }}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0"><i class="ti ti-calendar-event me-2 text-blue"></i>Info Rapat</h3>
                            <div class="d-flex align-items-center gap-2">
                                <x-tabler.button href="{{ route('Kegiatan.rapat.generate-pdf', $rapat->encrypted_rapat_id) }}" 
                                    class="btn-ghost-danger btn-sm" icon="ti ti-file-type-pdf" text="PDF" />
                                <x-tabler.button type="button" class="btn-outline-primary btn-sm ajax-modal-btn"
                                    data-url="{{ route('Kegiatan.rapat.edit', $rapat->encrypted_rapat_id) }}"
                                    data-modal-size="modal-xl"
                                    icon="ti ti-edit" text="Edit" />
                            </div>
                        </div>
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Judul Kegiatan</div>
                                <div class="datagrid-content fw-bold">{{ $rapat->judul_kegiatan }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Tanggal</div>
                                <div class="datagrid-content">{{ formatTanggalIndo($rapat->tgl_rapat) }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Waktu</div>
                                <div class="datagrid-content">
                                    {{ $rapat->waktu_mulai->format('H:i') }} – {{ $rapat->waktu_selesai->format('H:i') }}
                                </div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Tempat</div>
                                <div class="datagrid-content">{{ $rapat->tempat_rapat }}</div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0"><i class="ti ti-user-star me-2 text-orange"></i>Pejabat</h3>
                            <x-tabler.button type="warning" class="btn-sm ajax-modal-btn"
                                data-url="{{ route('Kegiatan.rapat.edit-officials', $rapat->hashid) }}"
                                icon="ti ti-edit" text="Set" />
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 rounded bg-blue-lt h-100">
                                    <span class="avatar avatar-sm me-3 rounded-circle bg-blue text-white">
                                        {{ strtoupper(substr($rapat->ketua_user->name ?? '?', 0, 2)) }}
                                    </span>
                                    <div>
                                        <div class="text-muted small">Ketua Rapat</div>
                                        <div class="fw-bold fs-5">{{ $rapat->ketua_user->name ?? '— Belum Diset —' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 rounded bg-orange-lt h-100">
                                    <span class="avatar avatar-sm me-3 rounded-circle bg-orange text-white">
                                        {{ strtoupper(substr($rapat->notulen_user->name ?? '?', 0, 2)) }}
                                    </span>
                                    <div>
                                        <div class="text-muted small">Notulen</div>
                                        <div class="fw-bold fs-5">{{ $rapat->notulen_user->name ?? '— Belum Diset —' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB: Peserta ── --}}
                    <div class="tab-pane" id="rtm-tab-peserta-{{ $idSuffix }}">
                        <x-tabler.card class="bg-light-lt border p-3 mb-3 shadow-none">
                            <x-tabler.card-body class="p-0">
                            <h4 class="mb-3"><i class="ti ti-user-plus me-1"></i>Tambah Peserta</h4>
                            <form action="{{ route('Kegiatan.rapat.participants.store', $rapat->hashid) }}" method="POST" class="ajax-form">
                                @csrf
                                <div class="peserta-rows-container">
                                    <div class="row g-2 mb-2 peserta-row align-items-end">
                                        <div class="col-md-6">
                                            <x-tabler.form-select name="participants[0][user_id]" label="Pegawai" placeholder="— Pilih Pegawai —" required="true" class="mb-0">
                                                 @foreach($users as $user)
                                                    <option value="{{ $user->encrypted_id }}">{{ $user->name }}
                                                        @if($user->pegawai?->latestDataDiri) — {{ $user->pegawai->latestDataDiri->jabatan ?? '' }}@endif
                                                    </option>
                                                @endforeach
                                            </x-tabler.form-select>
                                        </div>
                                        <div class="col-md-4">
                                            <x-tabler.form-input name="participants[0][jabatan]" label="Jabatan / Peran" placeholder="Contoh: Peserta" class="mb-0" />
                                        </div>
                                        <div class="col-md-2">
                                            <x-tabler.button type="button" class="btn-outline-danger btn-icon remove-peserta-row d-none" icon="ti ti-trash" iconOnly="true" />
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-2">
                                    <x-tabler.button type="button" id="btn-add-peserta-row" class="btn-outline-secondary btn-sm" text="Tambah Baris" />
                                    <x-tabler.button type="submit" class="btn-sm ms-auto" text="Simpan" />
                                </div>
                            </form>
                            </x-tabler.card-body>
                        </x-tabler.card>

                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-vcenter table-borderless mb-0">
                                <thead class="bg-light sticky-top">
                                    <tr>
                                        <th class="ps-3">Peserta</th>
                                        <th>Jabatan</th>
                                        <th class="text-center">Hadir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rapat->pesertas as $peserta)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2 rounded-circle {{ $peserta->status === 'hadir' ? 'bg-green text-white' : 'bg-secondary-lt text-muted' }}" id="avatar-{{ $peserta->encrypted_rapatpeserta_id }}">
                                                    {{ strtoupper(substr($peserta->nama_display, 0, 1)) }}
                                                </span>
                                                <div>
                                                    <div class="fw-medium">{{ $peserta->nama_display }}</div>
                                                    <div class="text-muted x-small waktu-hadir-{{ $peserta->encrypted_rapatpeserta_id }} {{ $peserta->status !== 'hadir' ? 'd-none' : '' }}">
                                                        <i class="ti ti-clock me-1"></i> <span class="text-green fw-semibold">Hadir {{ $peserta->waktu_hadir?->format('H:i') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted small">{{ $peserta->jabatan ?? '—' }}</td>
                                        <td class="text-center">
                                            <label class="form-check form-switch mb-0 justify-content-center">
                                                <input class="form-check-input attendance-switch" type="checkbox" data-url="{{ route('Kegiatan.rapat.peserta.toggle-attendance', $peserta->encrypted_rapatpeserta_id) }}" data-peserta-id="{{ $peserta->encrypted_rapatpeserta_id }}" {{ $peserta->status === 'hadir' ? 'checked' : '' }}>
                                            </label>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada peserta.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>

    {{-- ══ KANAN: Agenda ══ --}}
    <div class="col-md-6">
        <x-tabler.card shadow="none" class="border">
            <x-tabler.card-header title='<i class="ti ti-checklist me-2"></i>Agenda & Pembahasan'>
                <x-slot:actions>
                    <x-tabler.button type="create" class="btn-success btn-sm ajax-modal-btn" data-url="{{ route('Kegiatan.rapat.agenda.create', $rapat->encrypted_rapat_id) }}" data-modal-title="Tambah Agenda" />
                </x-slot:actions>
            </x-tabler.card-header>
            <x-tabler.card-body>
                <div class="accordion" id="accordion-agenda-{{ $idSuffix }}">
                    @forelse($rapat->agendas as $index => $agenda)
                    <div class="accordion-item">
                        <h4 class="accordion-header d-flex align-items-center">
                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }} flex-fill py-2" type="button" data-bs-toggle="collapse" data-bs-target="#ac-{{ $agenda->encrypted_rapatagenda_id }}">
                                <span class="badge bg-blue-lt me-2">{{ $loop->iteration }}</span>
                                <span class="flex-fill">{{ $agenda->judul_agenda }}</span>
                                <span class="ms-2 badge save-status-{{ $agenda->encrypted_rapatagenda_id }} d-none bg-blue-lt">Saving...</span>
                            </button>
                            <div class="dropdown pe-3">
                                <a href="#" class="btn btn-ghost-secondary btn-icon btn-sm dropdown-toggle no-caret" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:void(0)" class="dropdown-item ajax-modal-btn" data-url="{{ route('Kegiatan.rapat.agenda.edit', $agenda->encrypted_rapatagenda_id) }}" data-modal-title="Edit Judul">Edit Judul</a>
                                    <a href="javascript:void(0)" class="dropdown-item text-danger ajax-confirm" data-url="{{ route('Kegiatan.rapat.agenda.destroy', $agenda->encrypted_rapatagenda_id) }}" data-method="DELETE" data-title="Hapus Agenda">Hapus</a>
                                </div>
                            </div>
                        </h4>
                        <div id="ac-{{ $agenda->encrypted_rapatagenda_id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#accordion-agenda-{{ $idSuffix }}">
                            <div class="accordion-body p-0">
                                <textarea name="agendas[{{ $agenda->encrypted_rapatagenda_id }}][isi]" data-agenda-id="{{ $agenda->encrypted_rapatagenda_id }}" data-rapat-id="{{ $rapat->encrypted_rapat_id }}" class="form-control border-0">{{ $agenda->isi }}</textarea>
                            </div>
                        </div>
                    </div>
                    @empty
                    <x-tabler.empty-state title="Belum ada agenda" icon="ti ti-checklist" />
                    @endforelse
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
</div>
