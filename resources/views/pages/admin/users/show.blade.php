@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="User Profile" />
    
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Profile</h3>
        
        <!-- Profile Card -->
        <div class="mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                <div class="flex w-full flex-col items-center gap-6 xl:flex-row">
                    <div class="h-20 w-20 overflow-hidden rounded-full border border-gray-200 dark:border-gray-800">
                        <img src="{{ $user->profilePic() }}" alt="{{ $user->name }}" class="h-full w-full object-cover" />
                    </div>
                    <div class="order-3 xl:order-2">
                        <h4 class="mb-2 text-center text-lg font-semibold text-gray-800 xl:text-left dark:text-white/90">
                            {{ $user->name }}
                        </h4>
                        <div class="flex flex-col items-center gap-1 text-center xl:flex-row xl:gap-3 xl:text-left">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->role == 1 ? 'Admin User' : 'General User' }}
                            </p>
                            <div class="hidden h-3.5 w-px bg-gray-300 xl:block dark:bg-gray-700"></div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="order-2 flex grow items-center gap-2 xl:order-3 xl:justify-end">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->status == 1 ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-600' }}">
                        {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Personal Information Card -->
        <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="w-full">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">
                        Personal Information
                    </h4>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Full Name</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $user->name }}</p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Email Address</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $user->email }}</p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Phone Number</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $user->phone ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Account Type</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                @if($user->role == 1)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-600">Admin User</span>
                                @elseif($user->role == 2)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-50 text-gray-600">General User</span>
                                @else
                                    User
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Account Status</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->status == 1 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                    {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Member Since</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $user->created_at->format('F d, Y') }}</p>
                        </div>

                        <div>
                            <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Last Updated</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $user->updated_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Actions -->
        <div class="p-5 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
            <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Account Actions</h4>
            
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('users.edit', $user->id) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit User
                </a>
                
                <form method="POST" action="{{ route('users.toggle-status', $user->id) }}" class="inline">
                    @csrf
                    @method('POST')
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white {{ $user->status == 1 ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="{{ $user->status == 1 ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}" />
                        </svg>
                        {{ $user->status == 1 ? 'Deactivate Account' : 'Activate Account' }}
                    </button>
                </form>
                
                <a href="{{ route('users.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>
    </div>
@endsection