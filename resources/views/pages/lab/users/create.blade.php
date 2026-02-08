@extends('layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Create New User" pretitle="Forms">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.users.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form action="{{ route('lab.users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="name">Full Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}"
                                       placeholder="John Doe" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="email">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}"
                                       placeholder="john@example.com" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="password">Password</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password"
                                           placeholder="••••••••" required>
                                    <span class="input-group-text cursor-pointer" id="togglePassword"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="password_confirmation">Confirm Password</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation"
                                           placeholder="••••••••" required>
                                    <span class="input-group-text cursor-pointer" id="togglePasswordConfirmation"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="role">Role(s)</label>
                            <div class="col-sm-10">
                                <x-form.select2 
                                    id="role" 
                                    name="role" 
                                    placeholder="Select roles..." 
                                    multiple="true" 
                                    :options="$roles->pluck('name', 'name')->toArray()"
                                    :selected="old('role', [])" 
                                    required="true"
                                />
                                @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
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
                                <input type="file" class="filepond-input" 
                                       id="avatar" name="avatar" 
                                       accept="image/png, image/jpeg, image/gif">
                                <div class="form-hint">Allowed formats: jpeg, png, jpg, gif. Max size: 2MB.</div>
                                @error('avatar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" text="Create User" />
                                <x-tabler.button type="cancel" :href="route('lab.users.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        // Init FilePond
        if (typeof window.loadFilePond === 'function') {
            const FilePond = await window.loadFilePond();
            const input = document.querySelector('#avatar');
            if (input) {
                FilePond.create(input, {
                    storeAsFile: true,
                    allowMultiple: false,
                    maxFiles: 1,
                    labelIdle: 'Drag & Drop avatar or <span class="filepond--label-action">Browse</span>',
                    acceptedFileTypes: ['image/*'],
                });
            }
        }

        // Password Toggles
        const togglePwd = (id, toggleId) => {
            const input = document.getElementById(id);
            const toggle = document.getElementById(toggleId);
            if(input && toggle){
                toggle.addEventListener('click', () => {
                    const type = input.type === 'password' ? 'text' : 'password';
                    input.type = type;
                    toggle.innerHTML = type === 'password' ? '<i class="ti ti-eye-off"></i>' : '<i class="ti ti-eye"></i>';
                });
            }
        };
        
        togglePwd('password', 'togglePassword');
        togglePwd('password_confirmation', 'togglePasswordConfirmation');
    });
</script>
@endpush
                    <form action="{{ route('lab.users.store') }}" method="POST" enctype="multipart/form-data">
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
                                <a href="{{ route('lab.users.index') }}" class="btn btn-secondary">
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
