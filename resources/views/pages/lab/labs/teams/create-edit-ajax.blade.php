<x-tabler.form-modal
    id_form="{{ $labTeam->exists ? 'editLabTeamForm' : 'createLabTeamForm' }}"
    title="{{ $labTeam->exists ? 'Update Anggota Tim' : 'Tambah Anggota Tim' }}"
    route="{{ $labTeam->exists ? route('lab.labs.teams.update', [$lab->encrypted_lab_id, $labTeam->encrypted_id]) : route('lab.labs.teams.store', $lab->encrypted_lab_id) }}"
    method="{{ $labTeam->exists ? 'PUT' : 'POST' }}"
>
    <div class="mb-3">
        @if($labTeam->exists)
            <x-tabler.form-input 
                label="Pengguna" 
                value="{{ $labTeam->user->name }}" 
                disabled 
            />
            <input type="hidden" name="user_id" value="{{ encryptId($labTeam->user_id) }}">
        @else
            <x-tabler.form-select 
                name="user_id" 
                label="Pilih Pengguna" 
                :options="[]" 
                placeholder="Cari nama atau email..." 
                class="ajax-select"
                data-url="{{ route('lab.labs.teams.users', $lab->encrypted_lab_id) }}"
                required 
            />
        @endif
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            name="jabatan" 
            label="Jabatan / Peran" 
            value="{{ old('jabatan', $labTeam->jabatan) }}"
            placeholder="Misal: Koordinator Lab, Asisten, IT Support" 
            required 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            type="date" 
            name="tanggal_mulai" 
            label="Tanggal Mulai" 
            value="{{ old('tanggal_mulai', $labTeam->tanggal_mulai ? $labTeam->tanggal_mulai->format('Y-m-d') : now()->format('Y-m-d')) }}"
            required 
        />
    </div>

    @if($labTeam->exists)
        <div class="mb-3">
            <x-tabler.form-checkbox 
                name="is_active" 
                label="Status Aktif" 
                value="1" 
                :checked="$labTeam->is_active" 
                switch 
            />
        </div>
    @endif
</x-tabler.form-modal>
