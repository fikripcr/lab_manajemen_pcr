@extends('layouts.admin.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-indigo-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-gray-800">User Profile</h2>
                        <p class="mt-1 text-sm text-gray-600">View details for {{ $user->name }}</p>
                    </div>
                </div>

                <div class="flex space-x-3">
                    @can('update', $user)
                    <a href="{{ route('users.edit', $user) }}"
                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    @endcan
                    @can('delete', $user)
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                                onclick="return confirm('Are you sure you want to delete this user? All their data will be permanently removed.')">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-8">
                <div class="md:w-1/3 flex flex-col items-center">
                    <div class="relative">
                        <img class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg"
                             src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}"
                             alt="{{ $user->name }}">
                        <div class="absolute bottom-0 right-0 bg-indigo-500 rounded-full p-1 border-2 border-white">
                            <div class="h-4 w-4 rounded-full bg-green-400"></div>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mt-4 text-gray-900">{{ $user->name }}</h3>
                    <span class="mt-1 px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800">
                        {{ $user->roles->first()?->name ?? 'No Role' }}
                    </span>
                    <p class="mt-2 text-sm text-gray-500">Member since {{ $user->created_at->format('M Y') }}</p>
                </div>

                <div class="md:w-2/3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Contact Information</h4>
                            <div class="mt-2 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Email</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $user->email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Role</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $user->roles->first()?->name ?? 'No Role' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Personal Information</h4>
                            <div class="mt-2 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">NPM</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $user->npm ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">NIP</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $user->nip ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Account Information</h4>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <span class="text-sm text-gray-600">Account Created</span>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Last Updated</span>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->updated_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Email Verified</span>
                                    <p class="text-sm font-medium text-gray-900">
                                        @if($user->email_verified_at)
                                            <span class="text-green-600">Yes</span>
                                        @else
                                            <span class="text-red-600">No</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
