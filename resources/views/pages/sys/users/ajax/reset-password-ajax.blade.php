<x-tabler.form-modal
    :title="'Ubah Password: ' . $user->name"
    :route="route('sys.users.update-password', encryptId($user->id))"
    method="PUT"
    submitText="Ubah Password">

    <div class="alert alert-info py-2 px-3 mb-3 border-0">
        <div class="small">
            Password baru akan diset untuk <strong>{{ $user->name }}</strong> ({{ $user->email }}).
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-12">
            <x-tabler.form-input
                type="password"
                name="password"
                label="Password Baru"
                required
                placeholder="Minimal 8 karakter"
            />
        </div>
        <div class="col-md-12">
            <x-tabler.form-input
                type="password"
                name="password_confirmation"
                label="Konfirmasi Password Baru"
                required
                placeholder="Ulangi password baru"
            />
        </div>
    </div>
</x-tabler.form-modal>
