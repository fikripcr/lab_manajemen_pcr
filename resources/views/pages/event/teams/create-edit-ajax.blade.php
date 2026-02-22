<x-tabler.form-modal
    :title="$pageTitle"
    :route="$team->exists ? route('Kegiatan.Kegiatans.teams.update', ['event' => $event->encrypted_event_id, 'team' => $team->encrypted_eventteam_id]) : route('Kegiatan.Kegiatans.teams.store', ['event' => $event->encrypted_event_id])"
    :method="$team->exists ? 'PUT' : 'POST'"
    submit-text="{{ $team->exists ? 'Update' : 'Tambah' }}"
    submit-icon="ti ti-check"
>
    @csrf
    
    <x-tabler.form-select 
        name="pegawai_id" 
        label="Pilih Pegawai" 
        required="true"
        placeholder="Cari pegawai..."
    >
        <option value="">-- Pilih Pegawai --</option>
        @foreach($pegawais as $pegawai)
            <option value="{{ $pegawai->pegawai_id }}" 
                    {{ (isset($team) && $team->pegawai_id == $pegawai->pegawai_id) ? 'selected' : '' }}>
                {{ $pegawai->nama_pegawai }} ({{ $pegawai->nip ?? 'N/A' }}) - {{ $pegawai->jabatan ?? 'Staff' }}
            </option>
        @endforeach
    </x-tabler.form-select>

    <x-tabler.form-input 
        name="role" 
        label="Peran dalam Tim" 
        placeholder="Contoh: Koordinator, Sekretaris, dll"
        :value="$team->role ?? old('role')"
    />

    <x-tabler.form-input 
        name="jabatan_dalam_tim" 
        label="Jabatan dalam Kegiatan" 
        placeholder="Contoh: Ketua Panitia, Anggota, dll"
        :value="$team->jabatan_dalam_tim ?? old('jabatan_dalam_tim')"
    />

    <div class="mb-3">
        <label class="form-check">
            <input type="checkbox" name="is_pic" value="1" class="form-check-input" {{ (isset($team) && $team->is_pic) ? 'checked' : '' }}>
            <span class="form-check-label">Set sebagai PIC Utama Kegiatan</span>
        </label>
        <small class="form-hint">Hanya satu orang yang bisa menjadi PIC utama</small>
    </div>

    <div class="alert alert-info">
        <i class="ti ti-info-circle me-1"></i>
        <strong>Tips:</strong> Anda dapat menambahkan beberapa anggota tim. Klik "Tambah" untuk menambah anggota lain, atau "Simpan" untuk menyimpan dan kembali.
    </div>
</x-tabler.form-modal>
