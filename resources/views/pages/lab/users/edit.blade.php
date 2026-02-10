    <form class="ajax-form" action="{{ route('lab.users.update', $user->encrypted_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <x-tabler.flash-message />
            
            <div class="mb-3">
                <label class="form-label required" for="name">Full Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="name" name="name" value="{{ old('name', $user->name) }}"
                       placeholder="John Doe" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label required" for="email">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email', $user->email) }}"
                       placeholder="john@example.com" required>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">New Password (Optional)</label>
                <div class="input-group input-group-merge">
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password"
                           placeholder="••••••••">
                    <span class="input-group-text cursor-pointer togglePassword"><i class="ti ti-eye-off"></i></span>
                </div>
                <div class="form-text">Leave blank to keep current password</div>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                <div class="input-group input-group-merge">
                    <input type="password" class="form-control"
                           id="password_confirmation" name="password_confirmation"
                           placeholder="••••••••">
                    <span class="input-group-text cursor-pointer togglePasswordConfirmation"><i class="ti ti-eye-off"></i></span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label required" for="role">Role(s)</label>
                <x-form.select2 
                    id="role" 
                    name="role" 
                    placeholder="Select roles..." 
                    multiple="true" 
                    :options="$roles->pluck('name', 'name')->toArray()"
                    :selected="old('role', $user->roles->pluck('name')->toArray())" 
                    required="true"
                />
                @error('role')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="expired_at">Expiration Date (Optional)</label>
                <input type="date" class="form-control @error('expired_at') is-invalid @enderror"
                       id="expired_at" name="expired_at" value="{{ old('expired_at', $user->expired_at ? $user->expired_at->format('Y-m-d') : '') }}">
                <div class="form-text">Leave empty for no expiration.</div>
                @error('expired_at')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="avatar">Avatar</label>
                <div class="d-flex align-items-center mb-3">
                    @if($user->avatar_url)
                        <span class="avatar avatar-lg me-3" style="background-image: url('{{ $user->avatar_url }}')"></span>
                        <div>
                            <div class="font-weight-medium">Current Avatar</div>
                            <div class="text-muted small">Upload new to replace</div>
                        </div>
                    @else
                        <span class="avatar avatar-lg me-3 bg-secondary-lt">{{ substr($user->name, 0, 2) }}</span>
                        <div>
                            <div class="font-weight-medium">No Custom Avatar</div>
                            <div class="text-muted small">Using default initials</div>
                        </div>
                    @endif
                </div>
                <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg, image/gif">
                <div class="form-hint">Allowed formats: jpeg, png, jpg, gif. Max size: 2MB.</div>
                @error('avatar')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="modal-footer">
            <x-tabler.button type="cancel" data-bs-dismiss="modal" />
            <x-tabler.button type="submit" text="Update User" />
        </div>
    </form>

    <script>
        (async function() {
            // Password Toggles
            const togglePwd = (container) => {
                const input = container.querySelector('input[type="password"]') || container.querySelector('input[type="text"]');
                const toggle = container.querySelector('.input-group-text');
                if(input && toggle){
                    toggle.addEventListener('click', () => {
                        const type = input.type === 'password' ? 'text' : 'password';
                        input.type = type;
                        toggle.innerHTML = type === 'password' ? '<i class="ti ti-eye-off"></i>' : '<i class="ti ti-eye"></i>';
                    });
                }
            };
            
            document.querySelectorAll('.input-group-merge').forEach(togglePwd);

            // FilePond
            if (typeof window.loadFilePond === 'function') {
                const FilePond = await window.loadFilePond();
                const avatarInput = document.querySelector('#avatar');
                if(avatarInput) {
                    FilePond.create(avatarInput, {
                        storeAsFile: true,
                        labelIdle: 'Drag & Drop your avatar or <span class="filepond--label-action">Browse</span>',
                        acceptedFileTypes: ['image/*'],
                        imagePreviewHeight: 170,
                        imageCropAspectRatio: '1:1',
                        imageResizeTargetWidth: 200,
                        imageResizeTargetHeight: 200,
                        stylePanelLayout: 'compact circle',
                        styleLoadIndicatorPosition: 'center bottom',
                        styleProgressIndicatorPosition: 'right bottom',
                        styleButtonRemoveItemPosition: 'left bottom',
                        styleButtonProcessItemPosition: 'right bottom',
                    });
                }
            }
        })();
    </script>


