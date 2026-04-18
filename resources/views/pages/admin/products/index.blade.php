@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Products" />

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            <!-- Header -->
            <div class="flex flex-col gap-4 px-5 mb-4 sm:flex-row sm:items-center sm:px-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Product Management
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage your products for sale and rent
                    </p>
                </div>

                <div class="ml-auto flex flex-col gap-2 w-full sm:w-auto sm:flex-row sm:items-center sm:gap-3">
                    <!-- Search -->
                    <div class="w-full sm:w-64">
                        <form method="GET" action="{{ route('products.index') }}" class="relative w-full">
                            @if (request('product_type'))
                                <input type="hidden" name="product_type" value="{{ request('product_type') }}">
                            @endif
                            @if (request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                            @if (request('is_feature'))
                                <input type="hidden" name="is_feature" value="{{ request('is_feature') }}">
                            @endif
                            @if (request('tag_id'))
                                <input type="hidden" name="tag_id" value="{{ request('tag_id') }}">
                            @endif

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search products..."
                                class="w-full h-[42px] rounded-lg border border-gray-300 bg-transparent px-4 pr-10 text-sm
                                focus:border-blue-300 focus:ring-2 focus:ring-blue-500/10
                                dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                            @if (request('search'))
                                <a href="{{ route(
                                    'products.index',
                                    array_filter([
                                        'product_type' => request('product_type'),
                                        'status' => request('status'),
                                        'is_feature' => request('is_feature'),
                                        'tag_id' => request('tag_id'),
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

                    <!-- Filters -->
                    <div class="w-full flex flex-row gap-2 sm:w-auto">
                        <!-- Product Type Filter -->
                        <form method="GET" action="{{ route('products.index') }}" class="w-full sm:w-auto">
                            @if (request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if (request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                            @if (request('is_feature'))
                                <input type="hidden" name="is_feature" value="{{ request('is_feature') }}">
                            @endif
                            @if (request('tag_id'))
                                <input type="hidden" name="tag_id" value="{{ request('tag_id') }}">
                            @endif

                            <select name="product_type" onchange="this.form.submit()"
                                class="w-full h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 text-sm
                                focus:border-blue-300 focus:ring-2 focus:ring-blue-500/10
                                dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="">All Types</option>
                                <option value="sale" {{ request('product_type') == 'sale' ? 'selected' : '' }}>Sale
                                </option>
                                <option value="rent" {{ request('product_type') == 'rent' ? 'selected' : '' }}>Rent
                                </option>
                                <option value="both" {{ request('product_type') == 'both' ? 'selected' : '' }}>Both
                                </option>
                            </select>
                        </form>

                        <!-- Status Filter -->
                        <form method="GET" action="{{ route('products.index') }}" class="w-full sm:w-auto">
                            @if (request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if (request('product_type'))
                                <input type="hidden" name="product_type" value="{{ request('product_type') }}">
                            @endif
                            @if (request('is_feature'))
                                <input type="hidden" name="is_feature" value="{{ request('is_feature') }}">
                            @endif
                            @if (request('tag_id'))
                                <input type="hidden" name="tag_id" value="{{ request('tag_id') }}">
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
                        @if (request()->hasAny(['search', 'product_type', 'status', 'is_feature', 'tag_id']))
                            <a href="{{ route('products.index') }}"
                                class="h-[42px] inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-3 text-sm font-medium
                                hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-white/[0.05]">
                                Clear
                            </a>
                        @endif
                    </div>

                    <!-- Create Button -->
                    <div class="w-full sm:w-auto">
                        <a href="{{ route('products.create') }}"
                            class="flex h-[42px] w-full items-center justify-center gap-2 rounded-lg px-4 text-sm font-medium
                            bg-blue-600 text-white hover:bg-blue-700 sm:w-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Product
                        </a>
                    </div>
                </div>
            </div>

            <!-- Additional Filters Row -->
            <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center sm:px-6">
                <!-- Tag Filter -->
                <form method="GET" action="{{ route('products.index') }}" class="w-full sm:w-48">
                    @if (request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if (request('product_type'))
                        <input type="hidden" name="product_type" value="{{ request('product_type') }}">
                    @endif
                    @if (request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    @if (request('is_feature'))
                        <input type="hidden" name="is_feature" value="{{ request('is_feature') }}">
                    @endif

                    <select name="tag_id" onchange="this.form.submit()"
                        class="w-full h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 text-sm
                        focus:border-blue-300 focus:ring-2 focus:ring-blue-500/10
                        dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">All Tags</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Feature Filter -->
                <form method="GET" action="{{ route('products.index') }}" class="w-full sm:w-48">
                    @if (request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if (request('product_type'))
                        <input type="hidden" name="product_type" value="{{ request('product_type') }}">
                    @endif
                    @if (request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    @if (request('tag_id'))
                        <input type="hidden" name="tag_id" value="{{ request('tag_id') }}">
                    @endif

                    <select name="is_feature" onchange="this.form.submit()"
                        class="w-full h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 text-sm
                        focus:border-blue-300 focus:ring-2 focus:ring-blue-500/10
                        dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="">All Features</option>
                        <option value="1" {{ request('is_feature') == '1' ? 'selected' : '' }}>Featured</option>
                        <option value="0" {{ request('is_feature') == '0' ? 'selected' : '' }}>Not Featured</option>
                    </select>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto px-5">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Variants
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tags
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Feature
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <!-- Product Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <img class="h-12 w-12 rounded-lg object-cover"
                                                src="{{ $product->thumb_url }}" alt="{{ $product->product_name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $product->product_name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                SKU: {{ $product->variants->first()->sku ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Type Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $typeColors = [
                                            'sale' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                            'rent' =>
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'both' =>
                                                'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                        ];
                                        $colorClass = $typeColors[$product->product_type] ?? $typeColors['sale'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ ucfirst($product->product_type) }}
                                    </span>
                                </td>

                                <!-- Variants Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $product->variants->count() }} variants
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        @if ($product->variants->isNotEmpty())
                                            {{ $product->variants->sum('qty') }} in stock
                                        @else
                                            No variants
                                        @endif
                                    </div>
                                </td>

                                <!-- Tags Column -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($product->tags as $tag)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                                {{ $tag->name }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-gray-500 dark:text-gray-400">No tags</span>
                                        @endforelse
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            1 => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            0 => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        ];
                                        $colorClass = $statusColors[$product->status] ?? $statusColors[0];
                                    @endphp
                                    <form method="POST" action="#" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }} hover:opacity-80">
                                            {{ $product->status ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>

                                <!-- Feature Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $featureColors = [
                                            true => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            false => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                        ];
                                        $colorClass = $featureColors[$product->is_feature] ?? $featureColors[false];
                                    @endphp
                                    <form method="POST" action="#" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }} hover:opacity-80">
                                            {{ $product->is_feature ? 'Featured' : 'Regular' }}
                                        </button>
                                    </form>
                                </td>

                                <!-- Actions Column -->
                                <td class="px-4 py-4 text-right">
                                    <div class="relative inline-block text-left" x-data="{ open{{ $product->id }}: false }">
                                        <button type="button"
                                            @click="open{{ $product->id }} = !open{{ $product->id }}"
                                            @click.away="open{{ $product->id }} = false"
                                            class="btn btn-secondary dropdown-toggle action-dropdown-toggle flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                            aria-haspopup="true" :aria-expanded="open{{ $product->id }}">
                                            <svg xmlns="http://www.w3.org" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>

                                        <div x-show="open{{ $product->id }}"
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
                                                <!-- Show -->
                                                <a href="{{ route('products.show', $product->id) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                    role="menuitem">
                                                    <i class="fas fa-eye text-green-500"></i>
                                                    View
                                                </a>

                                                <!-- Edit -->
                                                <a href="{{ route('products.edit', $product->id) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                    role="menuitem">
                                                    <i class="fas fa-edit text-blue-500"></i>
                                                    Edit
                                                </a>

                                                <!-- Variants -->
                                                <a href="{{ route('products.variants.manage', $product->id) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                    role="menuitem">
                                                    <i class="fas fa-boxes text-purple-500"></i>
                                                    Manage Variants
                                                </a>

                                                <!-- Delete -->
                                                <x-delete-confirm :action="route('products.destroy', $product->id)" :message="json_encode(
                                                    'Are you sure you want to delete product ' .
                                                        $product->product_name .
                                                        '? This will also delete all variants and prices.',
                                                )" />
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No products
                                            found</h3>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            @if (request()->hasAny(['search', 'product_type', 'status', 'is_feature', 'tag_id']))
                                                Try adjusting your search or filter to find what you're looking for.
                                            @else
                                                Get started by creating a new product.
                                            @endif
                                        </p>
                                        <div class="mt-6">
                                            <a href="{{ route('products.create') }}"
                                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                                Add Product
                                            </a>
                                            @if (request()->hasAny(['search', 'product_type', 'status', 'is_feature', 'tag_id']))
                                                <a href="{{ route('products.index') }}"
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

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-400 mb-4 sm:mb-0">
                            Showing
                            <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span>
                            to
                            <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span>
                            of
                            <span class="font-medium">{{ $products->total() }}</span>
                            results
                        </div>

                        <div class="flex items-center space-x-2">
                            <!-- Previous Button -->
                            @if ($products->onFirstPage())
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-800 dark:text-gray-500 cursor-not-allowed">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $products->withQueryString()->previousPageUrl() }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                    Previous
                                </a>
                            @endif

                            <!-- Page Numbers -->
                            <div class="hidden sm:flex items-center space-x-1">
                                @php
                                    $currentPage = $products->currentPage();
                                    $lastPage = $products->lastPage();
                                    $startPage = max(1, $currentPage - 2);
                                    $endPage = min($lastPage, $currentPage + 2);
                                @endphp

                                @if ($startPage > 1)
                                    <a href="{{ $products->url(1) . '&' . http_build_query(request()->except('page')) }}"
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
                                        <a href="{{ $products->url($page) . '&' . http_build_query(request()->except('page')) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endfor

                                @if ($endPage < $lastPage)
                                    @if ($endPage < $lastPage - 1)
                                        <span class="px-2 text-gray-500">...</span>
                                    @endif
                                    <a href="{{ $products->url($lastPage) . '&' . http_build_query(request()->except('page')) }}"
                                        class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                        {{ $lastPage }}
                                    </a>
                                @endif
                            </div>

                            <!-- Next Button -->
                            @if ($products->hasMorePages())
                                <a href="{{ $products->withQueryString()->nextPageUrl() }}"
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
        // You can add any product-specific JavaScript here
    </script>
@endpush
