@if(request()->ajax())
    <x-tabler.form-modal
        title="Create New User"
        route="{{ route('lab.users.store') }}"
        method="POST"
        submitText="Create User"
        enctype="multipart/form-data"
    >
        <x-tabler.flash-message />
        
        <x-tabler.form-input name="name" label="Full Name" placeholder="John Doe" required />

        <x-tabler.form-input type="email" name="email" label="Email" placeholder="john@example.com" required />

        <x-tabler.form-input type="password" name="password" label="Password" placeholder="••••••••" required />
        <x-tabler.form-input type="password" name="password_confirmation" label="Confirm Password" placeholder="••••••••" required />

        <div class="mb-3">
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
    </x-tabler.form-modal>
@endif
    @extends('layouts.tabler.app')

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

                            <x-tabler.form-input name="name" label="Full Name" placeholder="John Doe" required />

                            <x-tabler.form-input type="email" name="email" label="Email" placeholder="john@example.com" required />

                            <x-tabler.form-input type="password" name="password" label="Password" placeholder="••••••••" required />

                            <x-tabler.form-input type="password" name="password_confirmation" label="Confirm Password" placeholder="••••••••" required />

                            <div class="mb-3">
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

                            <x-tabler.form-input type="file" id="avatar_full" name="avatar" label="Avatar (Optional)" accept="image/png, image/jpeg, image/gif" help="Allowed formats: jpeg, png, jpg, gif. Max size: 2MB." />

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



