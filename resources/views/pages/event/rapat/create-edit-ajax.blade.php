@php
    $isEdit = $rapat->exists;
    $route = $isEdit ? route('Kegiatan.rapat.update', $rapat->encrypted_rapat_id) : route('Kegiatan.rapat.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$pageTitle"
    :route="$route"
    :method="$method"
    submitText="{{ $isEdit ? 'Simpan Perubahan' : 'Jadwalkan Rapat' }}"
    size="modal-xl"
>
    {{-- Hidden fields for entitas linking --}}
    <input type="hidden" name="entitas_type" value="{{ $entitasType ?? '' }}">
    <input type="hidden" name="entitas_id" value="{{ $entitasId ?? '' }}">

    <div class="row g-3">
        {{-- KOLOM KIRI: DATA UMUM --}}
        <div class="col-lg-5">
            <x-tabler.card>
                <x-tabler.card-header title="<i class='ti ti-info-circle me-2'></i>Data Umum" />
                <x-tabler.card-body>
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

                    <div class="row">
                        <div class="col-6">
                            <x-tabler.form-select name="ketua_user_id" label="Ketua Rapat" placeholder="Pilih Ketua">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (old('ketua_user_id', $rapat->ketua_user_id) == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-tabler.form-select>
                        </div>
                        <div class="col-6">
                            <x-tabler.form-select name="notulen_user_id" label="Notulen" placeholder="Pilih Notulen">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (old('notulen_user_id', $rapat->notulen_user_id) == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-tabler.form-select>
                        </div>
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>

        {{-- KOLOM KANAN: AGENDA & PESERTA (Selalu tampil untuk sinkronisasi) --}}
        <div class="col-lg-7">
            {{-- AGENDA CARD --}}
            <x-tabler.card class="mb-3">
                <x-tabler.card-header title="<i class='ti ti-list-check me-2'></i>Agenda Rapat" />
                <x-tabler.card-body>
                    <div id="agenda-container">
                        @php
                            $agendas = [];
                            if (old('agendas')) {
                                $agendas = old('agendas');
                            } elseif ($rapat->exists && $rapat->agendas->count() > 0) {
                                $agendas = $rapat->agendas->toArray();
                            } elseif (isset($prefilledAgendas) && count($prefilledAgendas) > 0) {
                                $agendas = $prefilledAgendas;
                            } else {
                                $agendas = [['judul_agenda' => '']];
                            }
                        @endphp

                        @foreach($agendas as $index => $agenda)
                            <div class="agenda-item mb-2 border rounded p-2 bg-light-lt">
                                <div class="row align-items-center g-2">
                                    <div class="col-auto">
                                        <span class="badge bg-primary text-white rounded-pill">{{ $index + 1 }}</span>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="agendas[{{ $index }}][judul_agenda]" class="form-control fw-bold" value="{{ $agenda['judul_agenda'] ?? $agenda->judul_agenda ?? '' }}" placeholder="Judul Agenda" required>
                                        @if(isset($agenda['rapatagenda_id']))
                                            <input type="hidden" name="agendas[{{ $index }}][rapatagenda_id]" value="{{ $agenda['rapatagenda_id'] }}">
                                        @endif
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-icon btn-outline-danger remove-agenda" title="Hapus Agenda"><i class="ti ti-x"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <x-tabler.button type="button" id="add-agenda-btn" class="mt-2 btn-sm" text="Tambah Agenda" icon="ti ti-plus" />
                </x-tabler.card-body>
            </x-tabler.card>

            {{-- PESERTA CARD --}}
            <x-tabler.card>
                <x-tabler.card-header title="<i class='ti ti-users me-2'></i>Peserta Rapat" />
                <x-tabler.card-body>
                    @if($isEdit)
                        <div class="alert alert-info py-2 small mb-2">
                            <i class="ti ti-info-circle me-1"></i> Anda dapat menambah peserta baru di sini. Peserta yang sudah ada dapat dikelola di halaman detail.
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Pilih Peserta</label>
                        <select name="participants[]" id="select-participants" class="form-select select2-multiple" multiple="multiple">
                            @foreach($users as $user)
                                @php
                                    $isSelected = false;
                                    if (old('participants')) {
                                        $isSelected = in_array($user->id, old('participants'));
                                    } elseif ($isEdit) {
                                        $isSelected = $rapat->pesertas->pluck('user_id')->contains($user->id);
                                    }
                                @endphp
                                <option value="{{ $user->id }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->pegawai?->nip ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-0">
                        <x-tabler.form-input name="jabatan_peserta" label="Jabatan Default" type="text" value="Peserta" placeholder="Contoh: Peserta, Narasumber" />
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>
    </div>
</x-tabler.form-modal>

<script>
(function() {
    var agendaCounter = {{ count($agendas) }};

    var initRapatForm = function() {
        // Initialize Select2 if it's available and not already initialized
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2-multiple').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih peserta...',
                allowClear: true,
                dropdownParent: $('#select-participants').parent()
            });
        }

        // Add agenda button logic
        const addBtn = document.getElementById('add-agenda-btn');
        if (addBtn) {
            addBtn.onclick = function() {
                const container = document.getElementById('agenda-container');
                const newAgenda = document.createElement('div');
                newAgenda.className = 'agenda-item mb-2 border rounded p-2 bg-light-lt';
                newAgenda.innerHTML = `
                    <div class="row align-items-center g-2">
                        <div class="col-auto">
                            <span class="badge bg-primary text-white rounded-pill">${agendaCounter + 1}</span>
                        </div>
                        <div class="col">
                            <input type="text" name="agendas[${agendaCounter}][judul_agenda]" class="form-control fw-bold" placeholder="Judul Agenda" required>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-icon btn-outline-danger remove-agenda" title="Hapus Agenda"><i class="ti ti-x"></i></button>
                        </div>
                    </div>
                `;
                container.appendChild(newAgenda);
                agendaCounter++;
            };
        }

        // Remove agenda delegate
        document.getElementById('agenda-container')?.addEventListener('click', function(e) {
            if (e.target.closest('.remove-agenda')) {
                const items = document.querySelectorAll('.agenda-item');
                if (items.length > 1) {
                    e.target.closest('.agenda-item').remove();
                    // Renumber
                    document.querySelectorAll('.agenda-item').forEach((it, idx) => {
                        it.querySelector('.badge').textContent = idx + 1;
                    });
                }
            }
        });
    };

    // Auto-run on load
    setTimeout(initRapatForm, 100);
})();
</script>

<style>
    .select2-container--bootstrap-5 .select2-selection--multiple {
        border: 1px solid #dadcde;
        border-radius: 4px;
    }
    .bg-light-lt {
        background-color: rgba(246, 248, 251, 0.6) !important;
    }
</style>
