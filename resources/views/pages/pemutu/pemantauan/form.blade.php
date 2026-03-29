@php
    $isEdit = isset($rapat);
    $route = $isEdit ? route('pemutu.pemantauan.update', $rapat->encrypted_rapat_id) : route('pemutu.pemantauan.store');
@endphp

<x-tabler.form-modal 
    title="{{ $isEdit ? 'Edit Jadwal Pemantauan' : 'Jadwalkan Pemantauan Baru' }}" 
    route="{{ $route }}"
    method="{{ $isEdit ? 'PUT' : 'POST' }}"
    submitText="Simpan Jadwal"
    size="modal-xl"
    data-reload="true">
    
    <div class="row g-3">
        {{-- KOLOM KIRI: DATA UMUM --}}
        <div class="col-lg-5">
            <x-tabler.card>
                <x-tabler.card-header title="<i class='ti ti-info-circle me-2'></i>Data Umum" />
                <x-tabler.card-body>
                    <div class="mb-3">
                        <x-tabler.form-input name="judul_kegiatan" label="Judul Kegiatan/Rapat" placeholder="Contoh: Rapat Pemantauan KTS Unit IT" required="true" :value="$rapat->judul_kegiatan ?? ''" />
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <x-tabler.form-input type="date" name="tgl_rapat" label="Tanggal" required="true" :value="isset($rapat) ? $rapat->tgl_rapat->format('Y-m-d') : date('Y-m-d')" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <x-tabler.form-input type="time" name="waktu_mulai" label="Waktu Mulai" required="true" :value="isset($rapat->waktu_mulai) ? $rapat->waktu_mulai->format('H:i') : '09:00'" />
                        </div>
                        <div class="col-md-6">
                            <x-tabler.form-input type="time" name="waktu_selesai" label="Waktu Selesai" required="true" :value="isset($rapat->waktu_selesai) ? $rapat->waktu_selesai->format('H:i') : '10:00'" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <x-tabler.form-input name="tempat_rapat" label="Tempat" placeholder="Ruang Rapat / Zoom Link" required="true" :value="$rapat->tempat_rapat ?? ''" />
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <x-tabler.form-select name="ketua_user_id" label="Ketua Rapat" placeholder="Pilih Ketua" type="select2" data-dropdown-parent="#modalAction">
                                @foreach($users as $user)
                                    <option value="{{ $user->encrypted_id }}"
                                        {{ (isset($rapat) && ($rapat->ketua_user_id == $user->id || old('ketua_user_id') == $user->encrypted_id)) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-tabler.form-select>
                        </div>
                        <div class="col-md-6">
                            <x-tabler.form-select name="notulen_user_id" label="Notulen" placeholder="Pilih Notulen" type="select2" data-dropdown-parent="#modalAction">
                                @foreach($users as $user)
                                    <option value="{{ $user->encrypted_id }}"
                                        {{ (isset($rapat) && ($rapat->notulen_user_id == $user->id || old('notulen_user_id') == $user->encrypted_id)) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-tabler.form-select>
                        </div>
                    </div>

                    <div class="mb-0">
                        <x-tabler.form-textarea name="keterangan" label="Keterangan" placeholder="Catatan tambahan (opsional)" :value="$rapat->keterangan ?? ''" rows="2" />
                    </div>
                </x-tabler.card-body>
            </x-tabler.card>
        </div>

        {{-- KOLOM KANAN: AGENDA & PESERTA --}}
        <div class="col-lg-7">
            {{-- AGENDA CARD --}}
            <x-tabler.card class="mb-3">
                <x-tabler.card-header title="<i class='ti ti-list-check me-2'></i>Agenda Rapat" />
                <x-tabler.card-body>
                    <div id="agenda-container-pemantauan">
                        @php
                            $agendas = [];
                            if (old('agendas')) {
                                $agendas = old('agendas');
                            } elseif (isset($rapat) && $rapat->agendas->count() > 0) {
                                $agendas = $rapat->agendas->toArray();
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
                                        <button type="button" class="btn btn-icon btn-outline-danger remove-agenda-pemantauan" title="Hapus Agenda"><i class="ti ti-x"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <x-tabler.button type="button" id="add-agenda-btn-pemantauan" class="mt-2 btn-sm" text="Tambah Agenda" icon="ti ti-plus" />
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
                        <label class="form-label">Undang Peserta</label>
                        <select name="participants[]" id="select-participants-pemantauan" class="form-select select2-offline" multiple="multiple" data-dropdown-parent="#modalAction" data-placeholder="Pilih peserta...">
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
        // Add agenda button logic
        const addBtn = document.getElementById('add-agenda-btn-pemantauan');
        if (addBtn) {
            addBtn.onclick = function() {
                const container = document.getElementById('agenda-container-pemantauan');
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
                            <button type="button" class="btn btn-icon btn-outline-danger remove-agenda-pemantauan" title="Hapus Agenda"><i class="ti ti-x"></i></button>
                        </div>
                    </div>
                `;
                container.appendChild(newAgenda);
                agendaCounter++;
            };
        }

        // Remove agenda delegate
        document.getElementById('agenda-container-pemantauan')?.addEventListener('click', function(e) {
            if (e.target.closest('.remove-agenda-pemantauan')) {
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
