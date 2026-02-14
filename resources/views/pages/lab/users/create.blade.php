@if(request()->ajax())
    <form class="ajax-form" action="{{ route('lab.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Create New User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <x-tabler.flash-message />
            
            <x-tabler.form-input name="name" label="Full Name" placeholder="John Doe" required />

            <x-tabler.form-input type="email" name="email" label="Email" placeholder="john@example.com" required />

            <div class="mb-3">
                <label class="form-label required" for="password">Password</label>
                <div class="input-group input-group-merge">
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password"
                           placeholder="••••••••" required>
                    <span class="input-group-text cursor-pointer togglePassword"><i class="ti ti-eye-off"></i></span>
                </div>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label required" for="password_confirmation">Confirm Password</label>
                <div class="input-group input-group-merge">
                    <input type="password" class="form-control"
                           id="password_confirmation" name="password_confirmation"
                           placeholder="••••••••" required>
                    <span class="input-group-text cursor-pointer togglePasswordConfirmation"><i class="ti ti-eye-off"></i></span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label required" for="role">Role(s)</label>
                <x-tabler.form-select 
                    id="role" 
                    name="role" 
                    label="Role(s)"
                    placeholder="Select roles..." 
                    multiple
                    type="select2"
                    :options="$roles->pluck('name', 'name')->toArray()"
                    :selected="old('role', [])" 
                    required="true"
                />
                @error('role')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <x-tabler.form-input type="date" name="expired_at" label="Expiration Date (Optional)" help="Leave empty for no expiration." />

            <x-tabler.form-input type="file" name="avatar" label="Avatar (Optional)" accept="image/png, image/jpeg, image/gif" help="Allowed formats: jpeg, png, jpg, gif. Max size: 2MB." />
        </div>
        <div class="modal-footer">
            <x-tabler.button type="cancel" data-bs-dismiss="modal" />
            <x-tabler.button type="submit" text="Create User" />
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
@else
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

                        <form action="{{ route('lab.users.store') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                            @csrf

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label required" for="name">Full Name</label>
                                <div class="col-sm-10">
                                    <x-tabler.form-input name="name" placeholder="John Doe" required class="mb-0" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label required" for="email">Email</label>
                                <div class="col-sm-10">
                                    <x-tabler.form-input type="email" name="email" placeholder="john@example.com" required class="mb-0" />
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
                                    <x-tabler.form-select 
                                        id="role" 
                                        name="role" 
                                        placeholder="Select roles..." 
                                        multiple
                                        type="select2"
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
                                    <x-tabler.form-input type="date" name="expired_at" help="Leave empty for no expiration." class="mb-0" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="avatar_full">Avatar (Optional)</label>
                                <div class="col-sm-10">
                                    <x-tabler.form-input type="file" id="avatar_full" name="avatar" accept="image/png, image/jpeg, image/gif" help="Allowed formats: jpeg, png, jpg, gif. Max size: 2MB." class="mb-0" />
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

            // FilePond for full page
            if (typeof window.loadFilePond === 'function') {
                const FilePond = await window.loadFilePond();
                const avatarInput = document.querySelector('#avatar_full');
                if(avatarInput) {
                    FilePond.create(avatarInput, {
                        storeAsFile: true,
                        labelIdle: 'Drag & Drop your avatar or <span class="filepond--label-action">Browse</span>',
                        acceptedFileTypes: ['image/*'],
                    });
                }
            }
        });
    </script>
    @endpush
@endif


