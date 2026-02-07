@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Users" />

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            <!-- Header -->
            <div class="flex flex-col gap-4 px-5 mb-4 sm:flex-row sm:items-center sm:px-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        User Directory
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage your system users
                    </p>
                </div>

                <div class="ml-auto flex flex-col gap-2 w-full sm:w-auto sm:flex-row sm:items-center sm:gap-3">
                    <!-- Search with Cancel Icon -->
                    <div class="w-full sm:w-64">
                        <form method="GET" action="{{ route('users.index') }}" class="relative w-full">
                            <!-- Preserve other filters when searching -->
                            @if(request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                            @if(request('role'))
                                <input type="hidden" name="role" value="{{ request('role') }}">
                            @endif
                            
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..."
                                class="w-full h-[42px] rounded-lg border border-gray-300 bg-transparent px-4 pr-10 text-sm
                                focus:border-blue-300 focus:ring-2 focus:ring-blue-500/10
                                dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                            @if(request('search'))
                                <a href="{{ route('users.index', array_filter([
                                    'status' => request('status'),
                                    'role' => request('role'),
                                    'name' => request('name')
                                ])) }}"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Additional Filters -->
                    <div class="w-full flex flex-row gap-2 sm:w-auto">
                        <!-- Filter by Status -->
                        <form method="GET" action="{{ route('users.index') }}" class="w-full sm:w-auto">
                            <!-- Preserve search when changing status -->
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(request('role'))
                                <input type="hidden" name="role" value="{{ request('role') }}">
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

                        <!-- Filter by Role -->
                        <form method="GET" action="{{ route('users.index') }}" class="w-full sm:w-auto">
                            <!-- Preserve search when changing role -->
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                            
                            <select name="role" onchange="this.form.submit()"
                                class="w-full h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 text-sm
                                focus:border-blue-300 focus:ring-2 focus:ring-blue-500/10
                                dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="">All Roles</option>
                                <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Admin User</option>
                                <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>General User</option>
                            </select>
                        </form>

                        <!-- Clear Filters -->
                        @if(request()->hasAny(['search', 'status', 'role', 'name']))
                            <a href="{{ route('users.index') }}"
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

                        <!-- Create -->
                        <a href="{{ route('users.create') }}"
                            class="flex-1 h-[42px] inline-flex items-center justify-center gap-2 rounded-lg px-3 text-sm font-medium
                            bg-blue-600 text-white hover:bg-blue-700 sm:flex-none sm:px-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Add User
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto px-5">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact Info
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created At
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                        @forelse ($users as $key => $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <!-- User Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ $user->profilePic() }}"
                                                alt="{{ $user->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                No: {{ ++$key }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Contact Info Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $user->email }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->phone ?? 'No phone' }}
                                    </div>
                                </td>

                                <!-- Role Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleColors = [
                                            1 => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                            2 => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                        ];
                                        $roleText = $user->role == 1 ? 'Admin User' : 'General User';
                                        $colorClass = $roleColors[$user->role] ?? $roleColors[2];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ $roleText }}
                                    </span>
                                </td>

                                <!-- Created At Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->created_at->format('h:i A') }}
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            1 => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            0 => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        ];
                                        $statusText = $user->status == 1 ? 'Active' : 'Inactive';
                                        $colorClass = $statusColors[$user->status] ?? $statusColors[0];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>

                                <!-- Action Column - Dropdown style -->
                                <td class="px-4 py-4 text-right">
                                    <div class="relative inline-block text-left" x-data="{ open{{ $user->id }}: false }">
                                        <button type="button" @click="open{{ $user->id }} = !open{{ $user->id }}"
                                            @click.away="open{{ $user->id }} = false"
                                            class="btn btn-secondary dropdown-toggle action-dropdown-toggle flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                            aria-haspopup="true" :aria-expanded="open{{ $user->id }}">
                                            <!-- Heroicon: Ellipsis Vertical -->
                                            <svg xmlns="http://www.w3.org" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>

                                        <div x-show="open{{ $user->id }}"
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
                                                <form method="POST" action="{{ route('users.toggle-status', $user->id) }}"
                                                    class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit"
                                                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                        role="menuitem">
                                                        <i class="fas fa-window-close text-red-500"></i>
                                                        {{ $user->status == 1 ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>

                                                <!-- Edit -->
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                    role="menuitem">
                                                    <i class="fas fa-edit text-blue-500"></i>
                                                    Edit
                                                </a>

                                                <!-- Show -->
                                                <a href="{{ route('users.show', $user->id) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                    role="menuitem">
                                                    <i class="fas fa-eye text-green-500"></i>
                                                    Show
                                                </a>

                                                <!-- Delete button using component -->
                                                <x-delete-confirm :action="route('users.destroy', $user->id)" :message="json_encode(
                                                    'Are you sure you want to delete user ' .
                                                        $user->name .
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
                                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No users found</h3>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            @if(request()->hasAny(['search', 'status', 'role', 'name']))
                                                Try adjusting your search or filter to find what you're looking for.
                                            @else
                                                Get started by creating a new user.
                                            @endif
                                        </p>
                                        <div class="mt-6">
                                            <a href="{{ route('users.create') }}"
                                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                                Add User
                                            </a>
                                            @if(request()->hasAny(['search', 'status', 'role', 'name']))
                                                <a href="{{ route('users.index') }}"
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
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-400 mb-4 sm:mb-0">
                            Showing 
                            <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span>
                            to 
                            <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span>
                            of 
                            <span class="font-medium">{{ $users->total() }}</span>
                            results
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <!-- Previous Button -->
                            @if ($users->onFirstPage())
                                <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 dark:text-gray-500 cursor-not-allowed">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $users->withQueryString()->previousPageUrl() }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                    Previous
                                </a>
                            @endif

                            <!-- Page Numbers -->
                            <div class="hidden sm:flex items-center space-x-1">
                                @php
                                    // Show limited page numbers for better UX
                                    $currentPage = $users->currentPage();
                                    $lastPage = $users->lastPage();
                                    $startPage = max(1, $currentPage - 2);
                                    $endPage = min($lastPage, $currentPage + 2);
                                @endphp
                                
                                @if($startPage > 1)
                                    <a href="{{ $users->url(1) . '&' . http_build_query(request()->except('page')) }}"
                                        class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                        1
                                    </a>
                                    @if($startPage > 2)
                                        <span class="px-2 text-gray-500">...</span>
                                    @endif
                                @endif
                                
                                @for ($page = $startPage; $page <= $endPage; $page++)
                                    @if ($page == $currentPage)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-white bg-blue-600">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $users->url($page) . '&' . http_build_query(request()->except('page')) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endfor
                                
                                @if($endPage < $lastPage)
                                    @if($endPage < $lastPage - 1)
                                        <span class="px-2 text-gray-500">...</span>
                                    @endif
                                    <a href="{{ $users->url($lastPage) . '&' . http_build_query(request()->except('page')) }}"
                                        class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                        {{ $lastPage }}
                                    </a>
                                @endif
                            </div>

                            <!-- Next Button -->
                            @if ($users->hasMorePages())
                                <a href="{{ $users->withQueryString()->nextPageUrl() }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                    Next
                                </a>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 dark:text-gray-500 cursor-not-allowed">
                                    Next
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for Actions -->
    <script>
        function exportPDF() {
            // Implement PDF export functionality
            alert('PDF export functionality would be implemented here');
        }
    </script>
@endsection