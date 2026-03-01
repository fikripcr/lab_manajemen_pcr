<x-tabler.form-modal 
    :title="$user->exists ? 'Edit Pengguna: ' . $user->name : 'Tambah Pengguna Baru'" 
    :route="$user->exists ? route('sys.users.update', encryptId($user->id)) : route('sys.users.store')"
    :method="$user->exists ? 'PUT' : 'POST'"
    enctype="multipart/form-data">
    
    <div class="row g-3">
        <div class="col-md-6">
            <x-tabler.form-input 
                name="name" 
                label="Nama Lengkap" 
                :value="$user->name" 
                required 
                placeholder="Masukkan nama lengkap" 
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                type="email"
                name="email" 
                label="Alamat Email" 
                :value="$user->email" 
                required 
                placeholder="email@example.com" 
            />
        </div>

        <div class="col-md-12">
            <x-tabler.form-select 
                name="role[]" 
                label="Role / Peran" 
                multiple
                required
                id="user_roles">
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ (isset($user) && $user->hasRole($role->name)) ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>

        @if(!$user->exists)
            <div class="col-md-6">
                <x-tabler.form-input 
                    type="password"
                    name="password" 
                    label="Password" 
                    required 
                    placeholder="Minimal 8 karakter" 
                />
            </div>
            <div class="col-md-6">
                <x-tabler.form-input 
                    type="password"
                    name="password_confirmation" 
                    label="Konfirmasi Password" 
                    required 
                    placeholder="Ulangi password" 
                />
            </div>
        @endif

        <div class="col-md-6">
            <x-tabler.form-input 
                type="date"
                name="expired_at" 
                label="Tanggal Kedaluwarsa (Opsional)" 
                :value="$user->expired_at ? $user->expired_at->format('Y-m-d') : ''" 
                help="Akun akan dinonaktifkan setelah tanggal ini."
            />
        </div>

        <div class="col-md-12">
            <x-tabler.form-input 
                type="file" 
                name="avatar" 
                label="Foto Profil (Opsional)" 
                accept="image/*"
                help="Maksimal 2MB." 
            />
            @if($user->avatar_url)
                <div class="mt-2 text-center border rounded p-2 bg-light">
                    <label class="form-label text-muted small d-block mb-2">Foto Saat Ini:</label>
                    <img src="{{ $user->avatar_small_url }}" alt="Current Avatar" class="img-thumbnail rounded-circle" style="width: 64px; height: 64px; object-fit: cover;">
                </div>
            @endif
        </div>
    </div>
</x-tabler.form-modal>

<script>
    (function() {
        if (typeof window.loadSelect2 === 'function') {
            window.loadSelect2('#user_roles');
        }
        if (typeof window.initFilePond === 'function') {
            window.initFilePond();
        }
        if (typeof window.initFlatpickr === 'function') {
            window.initFlatpickr();
        }
    })();
</script>
