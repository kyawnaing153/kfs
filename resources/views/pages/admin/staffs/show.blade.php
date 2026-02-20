@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="'Staff Details: ' . $staff->name" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Left Column: Staff Profile Card -->
        <div class="lg:col-span-1">
            <x-common.component-card title="Staff Profile">
                <div class="flex flex-col items-center">
                    <!-- Profile Picture -->
                    <div class="mb-6">
                        <img src="{{ $staff->profile_picture_url }}" 
                             alt="{{ $staff->name }}"
                             class="h-40 w-40 rounded-full object-cover border-4 border-white shadow-lg dark:border-gray-800">
                    </div>

                    <!-- Staff Name -->
                    <h2 class="mb-2 text-2xl font-bold text-gray-800 dark:text-white">
                        {{ $staff->name }}
                    </h2>

                    <!-- Staff Department -->
                    <p class="mb-6 text-gray-600 dark:text-gray-400">
                        {{ $staff->department }}
                    </p>

                    <!-- Status Badge -->
                    @php
                        $statusColors = [
                            1 => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                            0 => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                        ];
                        $statusText = $staff->status == 1 ? 'Active' : 'Inactive';
                        $colorClass = $statusColors[$staff->status] ?? $statusColors[0];
                    @endphp
                    <div class="mb-8">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $colorClass }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($staff->status == 1)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @endif
                            </svg>
                            {{ $statusText }}
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 w-full">
                        <a href="{{ route('staffs.edit', $staff->id) }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('staffs.index') }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back
                        </a>
                    </div>
                </div>
            </x-common.component-card>

            <!-- Contact Information Card -->
            <x-common.component-card title="Contact Information" class="mt-6">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $staff->phone_number }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $staff->department }}</p>
                        </div>
                    </div>
                </div>
            </x-common.component-card>
        </div>

        <!-- Right Column: Staff Details -->
        <div class="lg:col-span-2">
            <!-- Personal Information Card -->
            <x-common.component-card title="Personal Information">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Name -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                            Full Name
                        </label>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                            <p class="text-sm text-gray-900 dark:text-white">{{ $staff->name }}</p>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                            Phone Number
                        </label>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                            <p class="text-sm text-gray-900 dark:text-white">{{ $staff->phone_number }}</p>
                        </div>
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                            Department
                        </label>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                            <p class="text-sm text-gray-900 dark:text-white">{{ $staff->department }}</p>
                        </div>
                    </div>

                    <!-- Salary -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                            Salary
                        </label>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                            <p class="text-sm text-gray-900 dark:text-white">
                                ${{ number_format($staff->salary, 2) }}
                            </p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                            Employment Status
                        </label>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                            @php
                                $statusColors = [
                                    1 => 'text-green-700 bg-green-50 dark:text-green-300 dark:bg-green-900/30',
                                    0 => 'text-red-700 bg-red-50 dark:text-red-300 dark:bg-red-900/30',
                                ];
                                $statusText = $staff->status == 1 ? 'Active' : 'Inactive';
                                $colorClass = $statusColors[$staff->status] ?? $statusColors[0];
                            @endphp
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $colorClass }}">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($staff->status == 1)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @endif
                                </svg>
                                {{ $statusText }}
                            </span>
                        </div>
                    </div>

                    <!-- Member Since -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                            Joined Date
                        </label>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $staff->created_at->format('F d, Y') }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ $staff->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            </x-common.component-card>

            <!-- Address Information Card -->
            @if($staff->address)
                <x-common.component-card title="Address Information" class="mt-6">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-900 dark:text-white whitespace-pre-line">{{ $staff->address }}</p>
                            </div>
                        </div>
                    </div>
                </x-common.component-card>
            @endif

            <!-- Employment Information Card -->
            <x-common.component-card title="Employment Information" class="mt-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                            Staff ID
                        </label>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                            <p class="text-sm font-mono text-gray-900 dark:text-white">#{{ str_pad($staff->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                            Last Updated
                        </label>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $staff->updated_at->format('F d, Y h:i A') }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                            Monthly Salary
                        </label>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-900 dark:text-white">
                                    ${{ number_format($staff->salary, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">
                            Annual Salary
                        </label>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-900 dark:text-white">
                                    ${{ number_format($staff->salary * 12, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-common.component-card>

            <!-- Danger Zone Card -->
            <x-common.component-card title="Danger Zone" class="mt-6 border-red-200 dark:border-red-900">
                <div class="space-y-4">
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-900 dark:bg-red-900/20">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-300">
                                    Delete Staff Record
                                </h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                                    <p>
                                        Once you delete a staff record, all of their data will be permanently removed.
                                        This action cannot be undone.
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <x-delete-confirm 
                                        :action="route('staffs.destroy', $staff->id)" 
                                        :message="json_encode('Are you sure you want to delete staff member ' . $staff->name . '? This action cannot be undone.')"
                                        buttonClass="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/30"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete Staff
                                    </x-delete-confirm>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Toggle Status -->
                    <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 dark:border-yellow-900 dark:bg-yellow-900/20">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">
                                    {{ $staff->status == 1 ? 'Deactivate' : 'Activate' }} Staff Account
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-400">
                                    <p>
                                        {{ $staff->status == 1 
                                            ? 'This will prevent the staff member from accessing the system. They can be reactivated later.' 
                                            : 'This will allow the staff member to access the system again.' 
                                        }}
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <form method="POST" action="{{ route('staffs.toggle-status', $staff->id) }}">
                                        @csrf
                                        @method('POST')
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-lg {{ $staff->status == 1 ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} px-4 py-2 text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-red-500/30">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($staff->status == 1)
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                @endif
                                            </svg>
                                            {{ $staff->status == 1 ? 'Deactivate Staff' : 'Activate Staff' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-common.component-card>
        </div>
    </div>
@endsection