@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Customer Quotations" />

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            <!-- Header -->
            <div class="flex flex-col gap-4 px-5 mb-4 sm:flex-row sm:items-center sm:px-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Customer Quotations
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage and track all customer quotations
                    </p>
                </div>

                <div class="ml-auto flex flex-col gap-2 w-full sm:w-auto sm:flex-row sm:items-center sm:gap-3">
                    <!-- Search with Cancel Icon -->
                    <div class="w-full sm:w-80">
                        <form method="GET" action="{{ route('customer-quotation.index') }}" class="relative w-full">
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Search by customer name or quotation code..."
                                class="w-full h-[42px] rounded-lg border border-gray-300 bg-transparent px-4 pr-10 text-sm
                                focus:border-blue-300 focus:ring-2 focus:ring-blue-500/10
                                dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                            @if ($search)
                                <a href="{{ route('customer-quotation.index') }}"
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

                    <!-- Clear Filters -->
                    @if ($search)
                        <a href="{{ route('customer-quotation.index') }}"
                            class="h-[42px] inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-4 text-sm font-medium
                            hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-white/[0.05]">
                            Clear Search
                        </a>
                    @endif
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto px-5">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quotation Code
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col"
                                class="min-w-[160px] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Items
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Daily Rental Total
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                        @forelse ($customerQuotations as $quotation)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <!-- Quotation Code -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $quotation->quotation_code }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Rent Date: {{ $quotation->rent_date }}
                                    </div>
                                </td>

                                <!-- Customer Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $quotation->customer->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $quotation->customer->phone_number }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Created: {{ $quotation->created_at->format('d/m/Y') }}
                                    </div>
                                </td>

                                <!-- Items Summary -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $quotation->items->count() }} item(s)
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        @foreach ($quotation->items->take(2) as $item)
                                            <div>{{ $item->productVariant->size }} - {{ $item->qty }}
                                                {{ $item->unit ?? 'unit' }}</div>
                                        @endforeach
                                        @if ($quotation->items->count() > 2)
                                            <div class="text-blue-600">+{{ $quotation->items->count() - 2 }} more</div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Sub Total -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        Ks {{ number_format($quotation->sub_total, 0) }}
                                    </div>
                                </td>

                                <!-- Total -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        Ks {{ number_format($quotation->total, 0) }}
                                    </div>
                                    @if ($quotation->deposit > 0)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Deposit: Ks {{ number_format($quotation->deposit, 0) }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'submitted' =>
                                                'bg-yellow-50 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-200',
                                            'approved' =>
                                                'bg-green-50 text-green-600 dark:bg-green-900 dark:text-green-200',
                                            'rejected' => 'bg-red-50 text-red-600 dark:bg-red-900 dark:text-red-200',
                                            'completed' =>
                                                'bg-blue-50 text-blue-600 dark:bg-blue-900 dark:text-blue-200',
                                        ];
                                        $statusText = ucfirst($quotation->status);
                                        $colorClass =
                                            $statusColors[$quotation->status] ??
                                            'bg-gray-50 text-gray-600 dark:bg-gray-700 dark:text-gray-300';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>

                                <!-- Action Column -->
                                <td class="px-4 py-4 text-right">
                                    <div class="relative inline-block text-left" x-data="{ open{{ $quotation->id }}: false }">
                                        <button type="button"
                                            @click="open{{ $quotation->id }} = !open{{ $quotation->id }}"
                                            @click.away="open{{ $quotation->id }} = false"
                                            class="btn btn-secondary dropdown-toggle action-dropdown-toggle flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>

                                        <div x-show="open{{ $quotation->id }}"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="transform opacity-100 scale-100"
                                            x-transition:leave-end="transform opacity-0 scale-95"
                                            class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-gray-800"
                                            role="menu" style="display: none;">
                                            <div class="py-1" role="none">
                                                @if ($quotation->status === 'approved')
                                                    <a href="{{ route('customer-quotation.convert-to-rent', $quotation->id) }}"
                                                        onclick="return confirm('Convert this quotation to a rent? This action cannot be undone.')"
                                                        class="flex items-center gap-2 px-4 py-2 text-sm text-blue-700 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                                        </svg>
                                                        Convert to Rent
                                                    </a>
                                                @endif

                                                <!-- View Details -->
                                                <a href="{{ route('customer-quotation.show', $quotation->id) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                    role="menuitem">
                                                    <svg class="w-4 h-4 text-green-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    View Details
                                                </a>

                                                <!-- Send Email -->
                                                {{-- <button type="button" onclick="sendQuotationEmail({{ $quotation->id }})"
                                                    class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                    role="menuitem">
                                                    <svg class="w-4 h-4 text-blue-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    Send Email
                                                </button> --}}

                                                <!-- Edit (if not completed) -->
                                                @if ($quotation->status != 'completed')
                                                    <a href="{{ route('customer-quotation.edit', $quotation->id) }}"
                                                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                        role="menuitem">
                                                        <svg class="w-4 h-4 text-yellow-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No quotations
                                            found</h3>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            @if ($search)
                                                No quotations match your search criteria. Try adjusting your search.
                                            @else
                                                There are no customer quotations available at the moment.
                                            @endif
                                        </p>
                                        @if ($search)
                                            <div class="mt-6">
                                                <a href="{{ route('customer-quotation.index') }}"
                                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                                    Clear Search
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer with Pagination -->
            @if ($customerQuotations->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-400 mb-4 sm:mb-0">
                            Showing
                            <span class="font-medium">{{ $customerQuotations->firstItem() ?? 0 }}</span>
                            to
                            <span class="font-medium">{{ $customerQuotations->lastItem() ?? 0 }}</span>
                            of
                            <span class="font-medium">{{ $customerQuotations->total() }}</span>
                            results
                        </div>

                        <div class="flex items-center space-x-2">
                            <!-- Previous Button -->
                            @if ($customerQuotations->onFirstPage())
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 dark:text-gray-500 cursor-not-allowed">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $customerQuotations->previousPageUrl() }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                    Previous
                                </a>
                            @endif

                            <!-- Page Numbers -->
                            <div class="hidden sm:flex items-center space-x-1">
                                @php
                                    $currentPage = $customerQuotations->currentPage();
                                    $lastPage = $customerQuotations->lastPage();
                                    $startPage = max(1, $currentPage - 2);
                                    $endPage = min($lastPage, $currentPage + 2);
                                @endphp

                                @if ($startPage > 1)
                                    <a href="{{ $customerQuotations->url(1) }}"
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
                                        <a href="{{ $customerQuotations->url($page) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endfor

                                @if ($endPage < $lastPage)
                                    @if ($endPage < $lastPage - 1)
                                        <span class="px-2 text-gray-500">...</span>
                                    @endif
                                    <a href="{{ $customerQuotations->url($lastPage) }}"
                                        class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                        {{ $lastPage }}
                                    </a>
                                @endif
                            </div>

                            <!-- Next Button -->
                            @if ($customerQuotations->hasMorePages())
                                <a href="{{ $customerQuotations->nextPageUrl() }}"
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

@push('scripts')
    <script>
        function sendQuotationEmail(quotationId) {
            if (confirm('Send this quotation to the customer via email?')) {
                fetch(`{{ route('customer-quotation.index', '') }}/${quotationId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Quotation sent successfully!');
                        } else {
                            alert('Failed to send quotation. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            }
        }
    </script>
@endpush
