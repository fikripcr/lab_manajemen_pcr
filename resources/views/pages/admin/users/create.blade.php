@extends('layouts.admin.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Create New User</h4>

    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-body">
                    <x-admin.flash-message />

                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="name">Full Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}"
                                       placeholder="John Doe" >
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="email">Email</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}"
                                       placeholder="john@example.com" >
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="password">Password</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password"
                                           placeholder="••••••••" >
                                    <span class="input-group-text cursor-pointer" id="togglePassword"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="password_confirmation">Confirm Password</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password_confirmation" name="password_confirmation"
                                           placeholder="••••••••" >
                                </div>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="role">Role(s)</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('role') is-invalid @enderror js-choice"
                                        id="role" name="role[]" multiple>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ in_array($role->name, old('role', [])) ? 'selected' : '' }}>
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
                            <label class="col-sm-2 col-form-label" for="expired_at">Expiration Date (Optional)</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control @error('expired_at') is-invalid @enderror"
                                       id="expired_at" name="expired_at" value="{{ old('expired_at') }}">
                                <div class="form-text">Leave empty for no expiration.</div>
                                @error('expired_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="avatar">Avatar (Optional)</label>
                            <div class="col-sm-10">
                                <input type="file" id="avatar" name="avatar" accept="image/*">
                                <div class="form-text">Allowed formats: jpeg, png, jpg, gif. Max size: 2MB.</div>
                                @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i> Create User
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

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="bx bx-hide"></i>' : '<i class="bx bx-show"></i>';
        });
    }

    if (togglePasswordConfirmation && passwordConfirmationInput) {
        togglePasswordConfirmation.addEventListener('click', function() {
            const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmationInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="bx bx-hide"></i>' : '<i class="bx bx-show"></i>';
        });
    }
});
</script>
@endsection

@push('css')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Load FilePond library -->
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    <script>
        // Register the plugin
        FilePond.registerPlugin(FilePondPluginImagePreview);

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Choices.js on the role multiselect
            const roleChoices = new Choices('#role', {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Select roles...',
                searchPlaceholderValue: 'Search roles...'
            });

            // Existing password toggle functionality
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const togglePassword = document.getElementById('togglePassword');
            const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="bx bx-hide"></i>' : '<i class="bx bx-show"></i>';
                });
            }

            if (togglePasswordConfirmation && passwordConfirmationInput) {
                togglePasswordConfirmation.addEventListener('click', function() {
                    const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordConfirmationInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="bx bx-hide"></i>' : '<i class="bx bx-show"></i>';
                });
            }

            // Initialize FilePond for avatar upload
            if (typeof FilePond !== 'undefined') {
                // Wait for a moment to ensure DOM is ready
                setTimeout(() => {
                    const inputElement = document.querySelector('input#avatar');
                    if (inputElement) {
                        // Create FilePond instance
                        const pond = FilePond.create(inputElement, {
                            allowMultiple: false,
                            maxFiles: 1,
                            acceptedFileTypes: ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'],
                            labelIdle: 'Drag & Drop your avatar or <span class="filepond--label-action"> Browse </span>',
                            onaddfile: (error, file) => {
                                if (error) {
                                    console.error('FilePond error:', error);
                                }
                            }
                        });

                        // The FilePond element will replace the input automatically
                    } else {
                        console.error('Avatar input element not found');
                    }
                }, 100); // Delay to ensure DOM is completely loaded
            } else {
                console.log('FilePond is not available');
            }
        });
    </script>
@endpush
