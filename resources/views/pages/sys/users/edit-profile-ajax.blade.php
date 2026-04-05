<x-tabler.form-modal
    title="Edit Profil: {{ $user->name }}"
    :route="route('sys.profile.update')"
    method="PUT"
    enctype="multipart/form-data">

    <div class="mb-3">
        <x-tabler.form-input
            name="name"
            label="Nama Lengkap"
            :value="$user->name"
            required
            placeholder="Masukkan nama lengkap"
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-input
            type="file"
            name="avatar"
            label="Foto Profil (Opsional)"
            accept="image/*"
            help="Biarkan kosong jika tidak ingin mengubah foto profil. Maksimal 2MB."
        />
        @if($user->avatar_url)
            <div class="mt-3">
                <label class="form-label text-muted small mb-2">Foto Saat Ini:</label>
                <img src="{{ $user->avatar_small_url }}" alt="Current Avatar" class="img-thumbnail rounded-circle border-3 border-light shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
            </div>
        @endif
    </div>

    <div class="mt-3 pt-3 border-top">
        <a href="{{ route('sys.users.reset-password', encryptId($user->id)) }}" class="btn btn-outline-primary w-100 ajax-modal-btn" data-modal-title="Ubah Password">
            <i class="ti ti-lock me-1"></i> Ubah Password
        </a>
    </div>
</x-tabler.form-modal>

<script>
    if (typeof window.initFilePond === 'function') {
        window.initFilePond();
    }
</script>
