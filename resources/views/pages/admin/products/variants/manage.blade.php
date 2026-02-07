@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="'Manage Variants: ' . $product->product_name" />

    <div class="space-y-6">
        <!-- Product Summary Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ $product->thumb_url }}" alt="{{ $product->product_name }}"
                        class="h-16 w-16 rounded-lg object-cover">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                            {{ $product->product_name }}
                        </h3>
                        <div class="mt-1 flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ ucfirst($product->product_type) }}
                            </span>
                            <span
                                class="inline-flex items-center rounded-full {{ $product->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }} px-2.5 py-0.5 text-xs font-medium">
                                {{ $product->status ? 'Active' : 'Inactive' }}
                            </span>
                            @if ($product->is_feature)
                                <span
                                    class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Featured
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex items-center gap-3">
                    <!-- Back Button -->
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600 bg-gray-100 hover:text-gray-900 hover:bg-gray-200 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </a>

                    <div class="h-4 w-px bg-gray-300 dark:bg-gray-700"></div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('products.show', $product->id) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600 bg-blue-100 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-800 transition-colors"
                            title="View Product Details">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span class="sm:inline">View</span>
                        </a>

                        <a href="{{ route('products.edit', $product->id) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-brand-50 px-3 py-1.5 text-sm font-medium text-brand-700 hover:bg-brand-100 dark:bg-brand-900/30 dark:text-brand-400 dark:hover:bg-brand-900/50 transition-colors"
                            title="Edit Product">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="sm:inline">Edit</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variants Management Card -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left Column: Add New Variant Form -->
            <div class="lg:col-span-1">
                <x-common.component-card title="Add New Variant">
                    <form method="POST" action="{{ route('products.variants.store', $product->id) }}" id="variantForm">
                        @csrf

                        <div class="space-y-4">
                            <!-- Size -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Size
                                </label>
                                <input type="text" name="size" value="{{ old('size') }}"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    placeholder="e.g., M, 10, 500ml" />
                                @error('size')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Unit
                                </label>
                                <input type="text" name="unit" value="{{ old('unit') }}"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    placeholder="e.g., piece, kg, liter" />
                                @error('unit')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Quantity<span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="qty" value="{{ old('qty', 0) }}" min="0"
                                    step="1"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    required />
                                @error('qty')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Purchase Price -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Purchase Price<span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="purchase_price" value="{{ old('purchase_price', 0) }}"
                                    min="0" step="0.1"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    required />
                                @error('purchase_price')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- SKU -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    SKU<span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="sku" value="{{ old('sku') }}"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    placeholder="Unique stock code" required />
                                @error('sku')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pricing Section -->
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <h4 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Set Pricing
                                </h4>

                                <!-- Sale Price -->
                                @if (in_array($product->product_type, ['sale', 'both']))
                                    <div class="mb-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                Sale Price
                                            </label>
                                            <span class="text-xs text-gray-500">One-time purchase</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <input type="hidden" name="price_type[]" value="sale">
                                            <input type="number" name="price[]" min="0.1" step="0.1"
                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                                placeholder="0.00" />
                                            <input type="hidden" name="duration_days[]" value="">
                                        </div>
                                    </div>
                                @endif

                                <!-- Rent Prices -->
                                @if (in_array($product->product_type, ['rent', 'both']))
                                    <div id="rentPricesContainer">
                                        <div class="flex items-center justify-between mb-3">
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                                Rental Prices
                                            </label>
                                            <button type="button" onclick="addRentPrice()"
                                                class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                + Add Duration
                                            </button>
                                        </div>

                                        <div class="space-y-2" id="rentPricesList">
                                            <!-- Initial rent price row -->
                                            <div class="flex gap-2">
                                                <div class="flex-1">
                                                    <input type="number" name="duration_days_rent[]" min="1"
                                                        placeholder="Days"
                                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                                </div>
                                                <div class="flex-1">
                                                    <input type="number" name="price_rent[]" min="0.1"
                                                        step="0.1" placeholder="Price"
                                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Variant
                                </button>
                            </div>
                        </div>
                    </form>
                </x-common.component-card>
            </div>

            <!-- Right Column: Variants List -->
            <div class="lg:col-span-2">
                <x-common.component-card title="Product Variants">
                    @if ($product->variants->isEmpty())
                        <div class="py-12 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No variants yet</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Start by adding your first variant using the form on the left.
                            </p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Variant Details
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stock & Price
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pricing
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($product->variants as $variant)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            <!-- Variant Details -->
                                            <td class="px-4 py-4">
                                                <div class="flex flex-col">
                                                    <div class="font-medium text-gray-900 dark:text-white">
                                                        {{ $variant->size ?: 'Standard' }}
                                                        @if ($variant->unit)
                                                            <span
                                                                class="text-sm text-gray-500">({{ $variant->unit }})</span>
                                                        @endif
                                                    </div>
                                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                        SKU: <span class="font-mono">{{ $variant->sku }}</span>
                                                    </div>
                                                    <div class="mt-1 text-xs text-gray-400">
                                                        Purchase: ${{ number_format($variant->purchase_price, 1) }}
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Stock & Price -->
                                            <td class="px-4 py-4">
                                                <div class="space-y-2">
                                                    <!-- Stock Management -->
                                                    <div>
                                                        <div class="flex items-center justify-between mb-1">
                                                            <span
                                                                class="text-sm text-gray-600 dark:text-gray-400">Stock</span>
                                                            <span
                                                                class="text-xs font-medium {{ $variant->qty > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                                {{ $variant->qty }} in stock
                                                            </span>
                                                        </div>

                                                        <form method="POST"
                                                            action="{{ route('products.variants.update-stock', [$product->id, $variant->id]) }}"
                                                            class="flex gap-2"
                                                            onsubmit="return confirm('Update stock quantity?')">
                                                            @csrf
                                                            <input type="number" name="quantity"
                                                                value="{{ $variant->qty }}" min="0"
                                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-20 rounded-lg border border-gray-300 bg-transparent px-2 py-1 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                            <button type="submit"
                                                                class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                                                Update
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Pricing -->
                                            <td class="px-4 py-4">
                                                <div class="space-y-1">
                                                    @php
                                                        $salePrice = $variant->prices
                                                            ->where('price_type', 'sale')
                                                            ->first();
                                                        $rentPrices = $variant->prices
                                                            ->where('price_type', 'rent')
                                                            ->sortBy('duration_days');
                                                    @endphp

                                                    @if ($salePrice)
                                                        <div class="text-sm">
                                                            <span class="text-gray-600 dark:text-gray-400">Sale:</span>
                                                            <span
                                                                class="ml-2 font-medium text-green-600 dark:text-green-400">
                                                                ${{ number_format($salePrice->price, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif

                                                    @foreach ($rentPrices as $rentPrice)
                                                        <div class="text-sm">
                                                            <span class="text-gray-600 dark:text-gray-400">
                                                                {{ $rentPrice->duration_days }}
                                                                day{{ $rentPrice->duration_days > 1 ? 's' : '' }}:
                                                            </span>
                                                            <span
                                                                class="ml-2 font-medium text-blue-600 dark:text-blue-400">
                                                                ${{ number_format($rentPrice->price, 1) }}
                                                            </span>
                                                        </div>
                                                    @endforeach

                                                    @if ($variant->prices->isEmpty())
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">No prices
                                                            set</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Actions -->
                                            <td class="px-4 py-4">
                                                <div class="flex items-center space-x-2">
                                                    <!-- Edit Variant Modal Trigger -->
                                                    <button type="button"
                                                        onclick="editVariant({{ $product->id }}, {{ $variant->id }})"
                                                        class="inline-flex items-center rounded-lg border border-gray-300 bg-white p-1.5 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                                        title="Edit Variant">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>

                                                    <!-- Add/Edit Price Modal Trigger -->
                                                    <button type="button"
                                                        onclick="managePrices({{ $product->id }}, {{ $variant->id }})"
                                                        class="inline-flex items-center rounded-lg border border-gray-300 bg-white p-1.5 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                                        title="Manage Prices">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </button>

                                                    <!-- Delete Variant -->
                                                    <form method="POST"
                                                        action="{{ route('products.variants.destroy', [$product->id, $variant->id]) }}"
                                                        onsubmit="return confirm('Are you sure you want to delete this variant? This will also delete all associated prices.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center rounded-lg border border-red-300 bg-white p-1.5 text-red-600 hover:bg-red-50 dark:border-red-700 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30"
                                                            title="Delete Variant">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Stats -->
                        <div class="mt-6 grid grid-cols-2 gap-4 border-t border-gray-200 pt-4 dark:border-gray-700">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Variants</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $product->variants->count() }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Stock</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $product->variants->sum('qty') }}
                                </p>
                            </div>
                        </div>
                    @endif
                </x-common.component-card>
            </div>
        </div>
    </div>

    <!-- Edit Variant Modal -->
    <div id="editVariantModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
            <div
                class="relative inline-block w-full max-w-md transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all dark:bg-gray-800 sm:my-8 sm:align-middle">
                <div class="bg-white px-4 pt-5 pb-4 dark:bg-gray-800 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 w-full text-center sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white" id="modal-title">
                                Edit Variant
                            </h3>
                            <div class="mt-4 space-y-4">
                                <form id="editVariantForm" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="variant_id" id="edit_variant_id">

                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                Size
                                            </label>
                                            <input type="text" name="size" id="edit_size"
                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                Unit
                                            </label>
                                            <input type="text" name="unit" id="edit_unit"
                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                Quantity
                                            </label>
                                            <input type="number" name="qty" id="edit_qty" min="0"
                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                Purchase Price
                                            </label>
                                            <input type="number" name="purchase_price" id="edit_purchase_price"
                                                min="0" step="0.1"
                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                        </div>

                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                SKU
                                            </label>
                                            <input type="text" name="sku" id="edit_sku"
                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 dark:bg-gray-700/50 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="submitEditForm()"
                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        Update Variant
                    </button>
                    <button type="button" onclick="closeEditModal()"
                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Prices Modal -->
    <div id="managePricesModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
            <div
                class="relative inline-block w-full max-w-lg transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all dark:bg-gray-800 sm:my-8 sm:align-middle">
                <div class="bg-white px-4 pt-5 pb-4 dark:bg-gray-800 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 w-full text-center sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white"
                                id="prices-modal-title">
                                Manage Prices
                            </h3>
                            <div class="mt-4">
                                <div id="currentPricesContainer" class="mb-6 space-y-3">
                                    <!-- Current prices will be loaded here -->
                                </div>

                                <div
                                    class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                                    <h4 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Add New Price
                                    </h4>
                                    <form id="addPriceForm">
                                        <input type="hidden" id="price_variant_id" name="variant_id">
                                        <input type="hidden" id="product_id" name="product_id"
                                            value="{{ $product->id }}">

                                        <div class="grid grid-cols-1 gap-3">
                                            <div>
                                                <label
                                                    class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                    Price Type
                                                </label>
                                                <select name="price_type" id="price_type"
                                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                    <option value="sale">Sale Price</option>
                                                    <option value="rent">Rental Price</option>
                                                </select>
                                            </div>

                                            <div id="durationField" class="hidden">
                                                <label
                                                    class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                    Duration (Days)
                                                </label>
                                                <input type="number" name="duration_days" id="duration_days"
                                                    min="1"
                                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                            </div>

                                            <div>
                                                <label
                                                    class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                    Price
                                                </label>
                                                <input type="number" name="price" id="price" min="0.1"
                                                    step="0.1"
                                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 dark:bg-gray-700/50 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="addPrice()"
                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        Add Price
                    </button>
                    <button type="button" onclick="closePricesModal()"
                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Rent price counter
        let rentPriceCount = 1;

        // Add rent price row
        function addRentPrice() {
            const container = document.getElementById('rentPricesList');
            const newRow = document.createElement('div');
            newRow.className = 'flex gap-2';
            newRow.innerHTML = `
            <div class="flex-1">
                <input type="number" name="duration_days_rent[]" min="1" placeholder="Days"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
            </div>
            <div class="flex-1">
                <input type="number" name="price_rent[]" min="0.1" step="0.1" placeholder="Price"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
            </div>
            <div class="flex items-center">
                <button type="button" onclick="removeRentPrice(this)"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white p-2 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        `;
            container.appendChild(newRow);
            rentPriceCount++;
        }

        // Remove rent price row
        function removeRentPrice(button) {
            if (rentPriceCount > 1) {
                button.closest('.flex.gap-2').remove();
                rentPriceCount--;
            }
        }

        // Handle form submission - combine rent prices with main form
        document.getElementById('variantForm').addEventListener('submit', function(e) {
            // Get all rent price inputs
            const durationInputs = document.querySelectorAll('input[name="duration_days_rent[]"]');
            const priceInputs = document.querySelectorAll('input[name="price_rent[]"]');

            // Create hidden inputs for each rent price
            durationInputs.forEach((durationInput, index) => {
                const priceInput = priceInputs[index];

                if (durationInput.value && priceInput.value) {
                    // Add price type
                    const typeInput = document.createElement('input');
                    typeInput.type = 'hidden';
                    typeInput.name = 'price_type[]';
                    typeInput.value = 'rent';
                    this.appendChild(typeInput);

                    // Add duration
                    const durationHidden = document.createElement('input');
                    durationHidden.type = 'hidden';
                    durationHidden.name = 'duration_days[]';
                    durationHidden.value = durationInput.value;
                    this.appendChild(durationHidden);

                    // Add price
                    const priceHidden = document.createElement('input');
                    priceHidden.type = 'hidden';
                    priceHidden.name = 'price[]';
                    priceHidden.value = priceInput.value;
                    this.appendChild(priceHidden);
                }
            });
        });

        // Edit Variant Modal
        function editVariant(productId, variantId) {
            const url = `{{ route('products.variants.details', ['product' => ':productId', 'variant' => ':variantId']) }}`
                .replace(':productId', productId)
                .replace(':variantId', variantId);

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    document.getElementById('edit_variant_id').value = data.id;
                    document.getElementById('edit_size').value = data.size || '';
                    document.getElementById('edit_unit').value = data.unit || '';
                    document.getElementById('edit_qty').value = data.qty;
                    document.getElementById('edit_purchase_price').value = data.purchase_price;
                    document.getElementById('edit_sku').value = data.sku;

                    // Update form action
                    const form = document.getElementById('editVariantForm');
                    form.action =
                        `{{ route('products.variants.update', ['product' => ':productId', 'variant' => ':variantId']) }}`
                        .replace(':productId', productId)
                        .replace(':variantId', variantId);

                    // Show modal
                    document.getElementById('editVariantModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading variant details. Please try again.');
                });
        }

        function closeEditModal() {
            document.getElementById('editVariantModal').classList.add('hidden');
        }

        function submitEditForm() {
            document.getElementById('editVariantForm').submit();
        }

        // Manage Prices Modal
        function managePrices(productId, variantId) {
            document.getElementById('price_variant_id').value = variantId;

            // Store product ID for later use
            document.getElementById('price_variant_id').setAttribute('data-product-id', productId);

            const url = `{{ route('products.variants.details', ['product' => ':productId', 'variant' => ':variantId']) }}`
                .replace(':productId', productId)
                .replace(':variantId', variantId);

            // Load current prices
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    const container = document.getElementById('currentPricesContainer');
                    container.innerHTML = '';

                    if (data.prices && data.prices.length > 0) {
                        data.prices.forEach(price => {
                            const priceDiv = document.createElement('div');
                            priceDiv.className =
                                'flex items-center justify-between rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-900';
                            priceDiv.innerHTML = `
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">
                                    ${price.price_type === 'sale' ? 'Sale Price' : `Rent (${price.duration_days} day${price.duration_days > 1 ? 's' : ''})`}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    $${parseFloat(price.price).toFixed(1)}
                                </div>
                            </div>
                            <button type="button" onclick="deletePrice(${price.id}, ${productId})"
                                class="inline-flex items-center rounded-lg border border-red-300 bg-white p-1.5 text-red-600 hover:bg-red-50 dark:border-red-700 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        `;
                            container.appendChild(priceDiv);
                        });
                    } else {
                        container.innerHTML =
                            '<p class="text-sm text-gray-500 dark:text-gray-400">No prices set yet</p>';
                    }

                    // Show modal
                    document.getElementById('managePricesModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading prices. Please try again.');
                });
        }

        function closePricesModal() {
            document.getElementById('managePricesModal').classList.add('hidden');
        }

        function addPrice() {
            const variantId = document.getElementById('price_variant_id').value;
            const productId = document.getElementById('product_id').value;
            const priceType = document.getElementById('price_type').value;
            const durationDays = document.getElementById('duration_days').value;
            const price = document.getElementById('price').value;

            if (!price || parseFloat(price) <= 0) {
                alert('Please enter a valid price');
                return;
            }

            if (priceType === 'rent' && (!durationDays || parseInt(durationDays) < 1)) {
                alert('Please enter a valid duration for rental price');
                return;
            }

            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const url =
                `{{ route('products.variants.store-price', ['product' => ':productId', 'variant' => ':variantId']) }}`
                .replace(':productId', productId)
                .replace(':variantId', variantId);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        price_type: priceType,
                        duration_days: priceType === 'rent' ? durationDays : null,
                        price: price
                    })
                })
                .then(async response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();
                    if (data.success) {
                        // Reload prices
                        managePrices(productId, variantId);
                        // Reset form
                        document.getElementById('price').value = '';
                        document.getElementById('duration_days').value = '';
                    } else {
                        alert(data.error || 'Error adding price');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error adding price: ' + error.message);
                });
        }

        function deletePrice(priceId, productId) {
            if (!confirm('Are you sure you want to delete this price?')) {
                return;
            }

            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`{{ route('prices.destroy', ['price' => ':priceId']) }}`.replace(':priceId', priceId), {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Get current variant ID from modal
                        const variantId = document.getElementById('price_variant_id').value;
                        // Reload current prices
                        managePrices(productId, variantId);
                    } else {
                        alert(data.error || 'Error deleting price');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting price: ' + error.message);
                });
        }

        // Show/hide duration field based on price type
        document.getElementById('price_type').addEventListener('change', function() {
            const durationField = document.getElementById('durationField');
            if (this.value === 'rent') {
                durationField.classList.remove('hidden');
            } else {
                durationField.classList.add('hidden');
            }
        });

        // Close modals on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
                closePricesModal();
            }
        });

        // Close modals on background click
        document.querySelectorAll('.fixed.inset-0').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (!document.getElementById('editVariantModal').classList.contains('hidden')) {
                        closeEditModal();
                    }
                    if (!document.getElementById('managePricesModal').classList.contains('hidden')) {
                        closePricesModal();
                    }
                }
            });
        });

        // Handle form validation
        document.addEventListener('DOMContentLoaded', function() {
            // Validate variant form
            const variantForm = document.getElementById('variantForm');
            if (variantForm) {
                variantForm.addEventListener('submit', function(e) {
                    const skuInput = this.querySelector('input[name="sku"]');
                    const qtyInput = this.querySelector('input[name="qty"]');
                    const priceInput = this.querySelector('input[name="purchase_price"]');

                    // Basic validation
                    if (!skuInput.value.trim()) {
                        e.preventDefault();
                        alert('SKU is required');
                        skuInput.focus();
                        return false;
                    }

                    if (qtyInput.value < 0) {
                        e.preventDefault();
                        alert('Quantity cannot be negative');
                        qtyInput.focus();
                        return false;
                    }

                    if (priceInput.value < 0) {
                        e.preventDefault();
                        alert('Purchase price cannot be negative');
                        priceInput.focus();
                        return false;
                    }
                });
            }
        });
    </script>
@endpush
