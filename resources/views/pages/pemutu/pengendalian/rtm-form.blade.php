<x-tabler.form-modal
    title="{{ isset($rapat) && $rapat->exists ? 'Edit Data RTM' : 'Buat RTM Pengendalian' }}"
    route="{{ isset($rapat) && $rapat->exists
        ? route('pemutu.pengendalian.rtm.update', [$periode->encrypted_periodespmi_id, $rapat->encrypted_rapat_id])
        : route('pemutu.pengendalian.rtm.store', $periode->encrypted_periodespmi_id) }}"
    method="{{ isset($rapat) && $rapat->exists ? 'PUT' : 'POST' }}">

    <div class="row g-3">
        {{-- Tanggal --}}
        <div class="col-md-4">
            <x-tabler.form-input
                type="date"
                name="tgl_rapat"
                label="Tanggal Rapat"
                required="true"
                :value="old('tgl_rapat', isset($rapat) && $rapat->exists ? $rapat->tgl_rapat->format('Y-m-d') : now()->format('Y-m-d'))"
            />
        </div>

        {{-- Waktu Mulai --}}
        <div class="col-md-4">
            <x-tabler.form-input
                type="time"
                name="waktu_mulai"
                label="Waktu Mulai"
                required="true"
                :value="old('waktu_mulai', isset($rapat) && $rapat->exists ? $rapat->waktu_mulai->format('H:i') : '08:00')"
            />
        </div>

        {{-- Waktu Selesai --}}
        <div class="col-md-4">
            <x-tabler.form-input
                type="time"
                name="waktu_selesai"
                label="Waktu Selesai"
                required="true"
                :value="old('waktu_selesai', isset($rapat) && $rapat->exists ? $rapat->waktu_selesai->format('H:i') : '12:00')"
            />
        </div>

        {{-- Tempat --}}
        <div class="col-12">
            <x-tabler.form-input
                name="tempat_rapat"
                label="Tempat Rapat"
                required="true"
                placeholder="Contoh: R. Auditorium"
                :value="old('tempat_rapat', $rapat->tempat_rapat ?? '')"
            />
        </div>

        {{-- Ketua Rapat --}}
        <div class="col-md-6">
            <x-tabler.form-select
                name="ketua_user_id"
                label="Ketua Rapat"
                placeholder="Pilih Ketua Rapat"
                :value="old('ketua_user_id', $rapat->ketua_user_id ?? '')">
                @foreach($users as $user)
                    <option value="{{ $user->encrypted_id }}"
                        {{ old('ketua_user_id', $rapat->ketua_user_id ?? '') == $user->id || old('ketua_user_id', $rapat->ketua_user_id ?? '') == $user->encrypted_id ? 'selected' : '' }}>
                        {{ $user->name }}
                        @if($user->pegawai?->latestDataDiri)
                            — {{ $user->pegawai->latestDataDiri->jabatan ?? '' }}
                        @endif
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>

        {{-- Notulen --}}
        <div class="col-md-6">
            <x-tabler.form-select
                name="notulen_user_id"
                label="Notulen"
                placeholder="Pilih Notulen"
                :value="old('notulen_user_id', $rapat->notulen_user_id ?? '')">
                @foreach($users as $user)
                    <option value="{{ $user->encrypted_id }}"
                        {{ old('notulen_user_id', $rapat->notulen_user_id ?? '') == $user->id || old('notulen_user_id', $rapat->notulen_user_id ?? '') == $user->encrypted_id ? 'selected' : '' }}>
                        {{ $user->name }}
                        @if($user->pegawai?->latestDataDiri)
                            — {{ $user->pegawai->latestDataDiri->jabatan ?? '' }}
                        @endif
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>
    </div>
</x-tabler.form-modal>
