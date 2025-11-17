@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Edit User</h4>

    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-body">
                    <x-flash-message />

                    <form action="{{ route('users.update', encryptId($user->id)) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="name">Full Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $user->name) }}"
                                       placeholder="John Doe" >
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="email">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $user->email) }}"
                                       placeholder="john@example.com" >
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="password">New Password (Optional)</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password"
                                           placeholder="••••••••">
                                    <span class="input-group-text cursor-pointer" id="togglePassword"><i class="bx bx-hide"></i></span>
                                </div>
                                <div class="form-text">Leave blank to keep current password</div>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="password_confirmation">Confirm New Password</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation"
                                           placeholder="••••••••">
                                    <span class="input-group-text cursor-pointer" id="togglePasswordConfirmation"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="role">Role</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('role') is-invalid @enderror"
                                        id="role" name="role" >
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="nim">NIM (Optional)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nim') is-invalid @enderror"
                                       id="nim" name="nim" value="{{ old('nim', $user->nim ?? '') }}"
                                       placeholder="e.g., 1234567890">
                                @error('nim')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="nip">NIP (Optional)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('nip') is-invalid @enderror"
                                       id="nip" name="nip" value="{{ old('nip', $user->nip) }}"
                                       placeholder="e.g., 1234567890">
                                @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="avatar">Avatar</label>
                            <div class="col-sm-10">
                                @if($user->avatar_url)
                                    <div class="mb-2">
                                        <img src="{{ $user->avatar_url }}"
                                             class="rounded-circle"
                                             width="60"
                                             height="60"
                                             alt="Avatar">
                                    </div>
                                @endif
                                <input class="form-control @error('avatar') is-invalid @enderror"
                                       type="file" id="avatar" name="avatar" accept="image/*">
                                <div class="form-text">Allowed formats: jpeg, png, jpg, gif. Max size: 2MB.
                                    @if($user->avatar_url) Leave empty to keep current avatar @endif
                                </div>
                                @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i> Update User
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-arrow-back me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');

    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="bx bx-hide"></i>' : '<i class="bx bx-show"></i>';
        });
    }

    if (togglePasswordConfirmation) {
        togglePasswordConfirmation.addEventListener('click', function() {
            const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmationInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="bx bx-hide"></i>' : '<i class="bx bx-show"></i>';
        });
    }
});
</script>
@endsection
