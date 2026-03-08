@php
    $isEdit = isset($rapat);
    $route = $isEdit ? route('pemutu.pelaksanaan.pemantauan.update', $rapat->encrypted_rapat_id) : route('pemutu.pelaksanaan.pemantauan.store');
@endphp

<x-tabler.form-modal 
    title="{{ $isEdit ? 'Edit Jadwal Pemantauan' : 'Jadwalkan Pemantauan Baru' }}" 
    route="{{ $route }}"
    method="{{ $isEdit ? 'PUT' : 'POST' }}"
    submitText="Simpan Jadwal"
    data-reload="true">
    
    <div class="row g-3">
        <div class="col-md-12">
            <x-tabler.form-input name="judul_kegiatan" label="Judul Kegiatan/Rapat" placeholder="Contoh: Rapat Pemantauan KTS Unit IT" required="true" :value="$rapat->judul_kegiatan ?? ''" />
        </div>
        <div class="col-md-4">
            <x-tabler.form-input type="date" name="tgl_rapat" label="Tanggal" required="true" :value="isset($rapat) ? $rapat->tgl_rapat->format('Y-m-d') : date('Y-m-d')" />
        </div>
        <div class="col-md-4">
            <x-tabler.form-input type="time" name="waktu_mulai" label="Waktu Mulai" required="true" :value="isset($rapat->waktu_mulai) ? $rapat->waktu_mulai->format('H:i') : '09:00'" />
        </div>
        <div class="col-md-4">
            <x-tabler.form-input type="time" name="waktu_selesai" label="Waktu Selesai" required="true" :value="isset($rapat->waktu_selesai) ? $rapat->waktu_selesai->format('H:i') : '10:00'" />
        </div>
        <div class="col-md-12">
            <x-tabler.form-input name="tempat_rapat" label="Tempat" placeholder="Ruang Rapat / Zoom Link" required="true" :value="$rapat->tempat_rapat ?? ''" />
        </div>
        
        <div class="col-md-6">
            <x-tabler.form-select name="ketua_user_id" label="Ketua Rapat" placeholder="Pilih Ketua">
                @foreach($users as $user)
                    <option value="{{ $user->encrypted_id }}"
                        {{ (isset($rapat) && ($rapat->ketua_user_id == $user->id || old('ketua_user_id') == $user->encrypted_id)) ? 'selected' : '' }}>
                        {{ $user->name }}
                        @if($user->pegawai?->latestDataDiri)
                            — {{ $user->pegawai->latestDataDiri->jabatan ?? '' }}
                        @endif
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="col-md-6">
            <x-tabler.form-select name="notulen_user_id" label="Notulen" placeholder="Pilih Notulen">
                @foreach($users as $user)
                    <option value="{{ $user->encrypted_id }}"
                        {{ (isset($rapat) && ($rapat->notulen_user_id == $user->id || old('notulen_user_id') == $user->encrypted_id)) ? 'selected' : '' }}>
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
