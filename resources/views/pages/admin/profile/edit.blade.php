@extends('layouts.admin.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Profile</h2>

        @if (session('status') === 'profile-updated')
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">Profile updated successfully.</span>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6">
            <div class="bg-white p-6 shadow rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Profile Information</h3>
                <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input id="name" name="name" type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Save
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-6 shadow rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Password</h3>
                <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input id="current_password" name="current_password" type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" autocomplete="current-password">
                        @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input id="password" name="password" type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" autocomplete="new-password">
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" autocomplete="new-password">
                        @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-6 shadow rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Account</h3>
                <p class="text-sm text-gray-600 mb-4">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>

                <button type="button"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                        onclick="document.getElementById('delete-user-form').classList.remove('hidden')">
                    Delete Account
                </button>

                <form id="delete-user-form" method="post" action="{{ route('profile.destroy') }}" class="hidden mt-4 p-4 bg-red-50 rounded">
                    @csrf
                    @method('delete')

                    <h4 class="text-md font-medium text-red-600 mb-2">Are you sure you want to delete your account?</h4>
                    <p class="text-sm text-gray-600 mb-4">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" placeholder="Password" required>
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="button"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                onclick="document.getElementById('delete-user-form').classList.add('hidden')">
                            Cancel
                        </button>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" type="submit">
                            Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
