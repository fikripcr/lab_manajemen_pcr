<x-tabler.form-modal 
    :title="'Reset Password: ' . $user->name" 
    :route="route('sys.users.update-password', encryptId($user->id))"
    method="PUT"
    submitText="Reset Password">
    
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
