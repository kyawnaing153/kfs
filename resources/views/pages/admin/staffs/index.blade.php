@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Staff Management" />

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            <!-- Header -->
            <div class="flex flex-col gap-4 px-5 mb-4 sm:flex-row sm:items-center sm:px-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Staff Directory
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage your organization's staff members
                    </p>
                </div>

                <div class="ml-auto flex flex-col gap-2 w-full sm:w-auto sm:flex-row sm:items-center sm:gap-3">
                    <!-- Search with Cancel Icon -->
                    <div class="w-full sm:w-64">
                        <form method="GET" action="{{ route('staffs.index') }}" class="relative w-full">
                            <!-- Preserve other filters when searching -->
                            @if (request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search staff members..."
                                class="w-full h-[42px] rounded-lg border border-gray-300 bg-transparent px-4 pr-10 text-sm
                                focus:border-blue-300 focus:ring-2 focus:ring-blue-500/10
                                dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                            @if (request('search'))
                                <a href="{{ route(
                                    'staffs.index',
                                    array_filter([
                                        'status' => request('status'),
                                        'name' => request('name'),
                                    ]),
                                ) }}"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Additional Filters -->
                    <div class="w-full flex flex-row gap-2 sm:w-auto">
                        <!-- Filter by Status -->
                        <form method="GET" action="{{ route('staffs.index') }}" class="w-full sm:w-auto">
                            <!-- Preserve search when changing status -->
                            @if (request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if (request('name'))
                                <input type="hidden" name="name" value="{{ request('name') }}">
                            @endif

                            <select name="status" onchange="this.form.submit()"
                                class="w-full h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 text-sm
                                    focus:border-blue-300 focus:ring-2 focus:ring-blue-500/10
                                    dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </form>

                        <!-- Clear Filters -->
                        @if (request()->hasAny(['search', 'status', 'name']))
                            <a href="{{ route('staffs.index') }}"
                                class="h-[42px] inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-3 text-sm font-medium
                    hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-white/[0.05]">
                                Clear
                            </a>
                        @endif
                    </div>

                    <!-- Buttons -->
                    <div class="w-full flex flex-row gap-2 sm:w-auto">
                        <!-- Export -->
                        <button @click="exportPDF"
                            class="flex-1 h-[42px] inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-3 text-sm font-medium
                hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-white/[0.05] sm:flex-none sm:px-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export
                        </button>

                        <!-- Create Staff -->
                        <a href="{{ route('staffs.create') }}"
                            class="flex-1 h-[42px] inline-flex items-center justify-center gap-2 rounded-lg px-3 text-sm font-medium
                                bg-blue-600 text-white hover:bg-blue-700 sm:flex-none sm:px-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Staff
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto px-5">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Staff Member
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact Info
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Address
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                        @forelse ($staffs as $staff)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <!-- Staff Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ $staff->getProfilePictureUrlAttribute() }}"
                                                alt="{{ $staff->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $staff->name }}
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-white">
                                                {{ number_format($staff->salary, 0) }} ks
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Contact Info Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        @if ($staff->phone_number)
                                            <a href="tel:{{ $staff->phone_number }}" class="hover:underline">
                                                {{ $staff->phone_number }}
                                            </a>
                                        @else
                                            No phone
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-400 dark:text-gray-300">
                                        @if($staff->department)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-900 dark:text-green-200">
                                                {{ $staff->department }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Address Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        @if ($staff->address)
                                            {{ Str::limit($staff->address, 30) }}
                                        @else
                                            <span class="text-gray-400">Not specified</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            1 => 'bg-blue-50 text-blue-600 dark:bg-blue-800 dark:text-blue-100',
                                            0 => 'bg-red-50 text-red-600 dark:bg-red-900 dark:text-red-200',
                                        ];
                                        $statusText = $staff->status == 1 ? 'Active' : 'Inactive';
                                        $colorClass = $statusColors[$staff->status] ?? $statusColors[0];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>

                                <!-- Action Column - Dropdown style -->
                                <td class="px-4 py-4 text-right">
                                    <div class="relative inline-block text-left" x-data="{ open{{ $staff->id }}: false }">
                                        <button type="button"
                                            @click="open{{ $staff->id }} = !open{{ $staff->id }}"
                                            @click.away="open{{ $staff->id }} = false"
                                            class="btn btn-secondary dropdown-toggle action-dropdown-toggle flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                            aria-haspopup="true" :aria-expanded="open{{ $staff->id }}">
                                            <!-- Heroicon: Ellipsis Vertical -->
                                            <svg xmlns="http://www.w3.org" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>

                                        <div x-show="open{{ $staff->id }}"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="transform opacity-100 scale-100"
                                            x-transition:leave-end="transform opacity-0 scale-95"
                                            class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-blue-500/10 dark:bg-gray-800"
                                            role="menu" aria-orientation="vertical" aria-labelledby="menu-button"
                                            style="display: none;">
                                            <div class="py-1" role="none">
                                                <!-- Toggle Status -->
                                                <form method="POST" action="{{ route('staffs.toggle-status', $staff->id) }}" class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit"
                                                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                        role="menuitem">
                                                        <i class="fas fa-window-close text-red-500"></i>
                                                        {{ $staff->status == 1 ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>

                                                <!-- Edit -->
                                                <a href="{{ route('staffs.edit', $staff->id) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                    role="menuitem">
                                                    <i class="fas fa-edit text-blue-500"></i>
                                                    Edit
                                                </a>

                                                <!-- Show -->
                                                <a href="{{ route('staffs.show', $staff->id) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                    role="menuitem">
                                                    <i class="fas fa-eye text-green-500"></i>
                                                    Show
                                                </a>

                                                <!-- Delete button using component -->
                                                <x-delete-confirm :action="route('staffs.destroy', $staff->id)" :message="json_encode(
                                                    'Are you sure you want to delete staff member ' .
                                                        $staff->name .
                                                        '? This action cannot be undone.',
                                                )" />
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No staff members
                                            found</h3>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            @if (request()->hasAny(['search', 'status', 'name', 'email']))
                                                Try adjusting your search or filter to find what you're looking for.
                                            @else
                                                Get started by creating a new staff member.
                                            @endif
                                        </p>
                                        <div class="mt-6">
                                            <a href="{{ route('staffs.create') }}"
                                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                                Add Staff Member
                                            </a>
                                            @if (request()->hasAny(['search', 'status', 'name', 'email']))
                                                <a href="{{ route('staffs.index') }}"
                                                    class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                                    Clear Filters
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer with Pagination -->
            @if ($staffs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-400 mb-4 sm:mb-0">
                            Showing
                            <span class="font-medium">{{ $staffs->firstItem() ?? 0 }}</span>
                            to
                            <span class="font-medium">{{ $staffs->lastItem() ?? 0 }}</span>
                            of
                            <span class="font-medium">{{ $staffs->total() }}</span>
                            results
                        </div>

                        <div class="flex items-center space-x-2">
                            <!-- Previous Button -->
                            @if ($staffs->onFirstPage())
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 dark:text-gray-500 cursor-not-allowed">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $staffs->previousPageUrl() }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                    Previous
                                </a>
                            @endif

                            <!-- Page Numbers -->
                            <div class="hidden sm:flex items-center space-x-1">
                                @php
                                    // Show limited page numbers for better UX
                                    $currentPage = $staffs->currentPage();
                                    $lastPage = $staffs->lastPage();
                                    $startPage = max(1, $currentPage - 2);
                                    $endPage = min($lastPage, $currentPage + 2);
                                @endphp

                                @if ($startPage > 1)
                                    <a href="{{ $staffs->url(1) }}"
                                        class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                        1
                                    </a>
                                    @if ($startPage > 2)
                                        <span class="px-2 text-gray-500">...</span>
                                    @endif
                                @endif

                                @for ($page = $startPage; $page <= $endPage; $page++)
                                    @if ($page == $currentPage)
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-white bg-blue-600">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $staffs->url($page) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endfor

                                @if ($endPage < $lastPage)
                                    @if ($endPage < $lastPage - 1)
                                        <span class="px-2 text-gray-500">...</span>
                                    @endif
                                    <a href="{{ $staffs->url($lastPage) }}"
                                        class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                        {{ $lastPage }}
                                    </a>
                                @endif
                            </div>

                            <!-- Next Button -->
                            @if ($staffs->hasMorePages())
                                <a href="{{ $staffs->nextPageUrl() }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                    Next
                                </a>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 dark:text-gray-500 cursor-not-allowed">
                                    Next
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
