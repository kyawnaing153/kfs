@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="'Product Details: ' . $product->product_name" />

    <div class="space-y-6">
        <!-- Product Summary Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                <!-- Product Info -->
                <div class="flex items-start space-x-4">
                    <!-- Product Image -->
                    <div class="relative">
                        <img src="{{ $product->thumb_url }}" alt="{{ $product->product_name }}"
                            class="h-24 w-24 rounded-xl object-cover border border-gray-200 dark:border-gray-700">
                        @if ($product->is_feature)
                            <span
                                class="absolute -top-2 -right-2 inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                Featured
                            </span>
                        @endif
                    </div>

                    <!-- Product Details -->
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $product->product_name }}
                        </h1>

                        <div class="mt-2 flex flex-wrap gap-2">
                            <!-- Product Type Badge -->
                            <span
                                class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                @if ($product->product_type == 'both')
                                    Sale & Rent
                                @else
                                    {{ ucfirst($product->product_type) }}
                                @endif
                            </span>

                            <!-- Status Badge -->
                            <span
                                class="inline-flex items-center rounded-full {{ $product->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }} px-3 py-1 text-sm font-medium">
                                {{ $product->status ? 'Active' : 'Inactive' }}
                            </span>

                            <!-- Product ID -->
                            <span
                                class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                ID: {{ $product->id }}
                            </span>
                        </div>

                        <!-- Created/Updated Info -->
                        <div class="mt-3 space-y-1 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Created: {{ $product->created_at->format('M d, Y') }}
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Updated: {{ $product->updated_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 md:mt-0 flex items-center gap-3">
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600 bg-blue-100 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </a>

                    <div class="h-4 w-px bg-gray-300 dark:bg-gray-700"></div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('products.edit', $product->id) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-brand-50 px-3 py-1.5 text-sm font-medium text-brand-700 hover:bg-brand-100 dark:bg-brand-900/30 dark:text-brand-400 dark:hover:bg-brand-900/50 transition-colors"
                            title="Edit Product">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="sm:inline">Edit</span>
                        </a>

                        <a href="{{ route('products.variants.manage', $product->id) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600 bg-blue-100 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-800 transition-colors"
                            title="View Product Details">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="sm:inline">Manage Variants</span>
                        </a>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left Column: Product Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description Card -->
                <x-common.component-card title="Product Description">
                    @if ($product->description)
                        <div class="prose prose-sm max-w-none text-gray-600 dark:text-gray-400 dark:prose-invert">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 italic">No description provided.</p>
                    @endif
                </x-common.component-card>

                <!-- Variants Card -->
                <x-common.component-card title="Product Variants">
                    @if ($product->variants->isEmpty())
                        <div class="py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No variants yet</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Add variants to enable sales or rentals for this product.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('products.variants.manage', $product->id) }}"
                                    class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add First Variant
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <th
                                            class="px-4 py-3 text-left text-xs min-w-[100px] font-medium text-gray-500 uppercase tracking-wider">
                                            Variant
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs min-w-[140px] font-medium text-gray-500 uppercase tracking-wider">
                                            SKU & Stock
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs min-w-[220px] font-medium text-gray-500 uppercase tracking-wider">
                                            Pricing
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Purchase Price
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
                                                        @if ($variant->size)
                                                            {{ $variant->size }}
                                                            @if ($variant->unit)
                                                                <span
                                                                    class="text-sm text-gray-500">({{ $variant->unit }})</span>
                                                            @endif
                                                        @else
                                                            <span class="text-gray-500 italic">Standard</span>
                                                        @endif
                                                    </div>
                                                    @if ($variant->size || $variant->unit)
                                                        <div class="mt-1 text-xs text-gray-400">
                                                            Variant #{{ $variant->id }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- SKU & Stock -->
                                            <td class="px-4 py-4">
                                                <div class="space-y-2">
                                                    <div>
                                                        <div class="text-sm font-mono text-gray-800 dark:text-gray-300">
                                                            {{ $variant->sku }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">SKU</div>
                                                    </div>
                                                    <div>
                                                        <div class="flex items-center">
                                                            <span
                                                                class="text-sm font-medium {{ $variant->qty > 50 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                                {{ $variant->qty }}
                                                            </span>
                                                            <span class="ml-1 text-sm text-gray-500">in stock</span>
                                                        </div>
                                                        {{-- <div class="text-xs text-gray-500">Quantity</div> --}}
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
                                                            ->sortBy('duration_days')
                                                            ->take(3);
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

                                                    @if ($rentPrices->count() < $variant->prices->where('price_type', 'rent')->count())
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            +{{ $variant->prices->where('price_type', 'rent')->count() - $rentPrices->count() }}
                                                            more rental option(s)
                                                        </div>
                                                    @endif

                                                    @if ($variant->prices->isEmpty())
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">No prices
                                                            set</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Purchase Price -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-900 dark:text-white">
                                                        ${{ number_format($variant->purchase_price, 1) }}
                                                    </span>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Purchase Price
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Variants Summary -->
                        <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
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
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Sale Prices</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $product->variants->flatMap->prices->where('price_type', 'sale')->count() }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Rent Prices</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $product->variants->flatMap->prices->where('price_type', 'rent')->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </x-common.component-card>
            </div>

            <!-- Right Column: Sidebar Info -->
            <div class="space-y-6">
                <!-- Tags Card -->
                <x-common.component-card title="Tags">
                    @if ($product->tags->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach ($product->tags as $tag)
                                <span
                                    class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 italic">No tags assigned.</p>
                    @endif
                </x-common.component-card>

                <!-- Quick Stats Card -->
                <x-common.component-card title="Product Stats">
                    <div class="space-y-4">
                        <!-- Product Type -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Product Type</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">
                                @switch($product->product_type)
                                    @case('sale')
                                        <span class="text-green-600 dark:text-green-400">Sale Only</span>
                                    @break

                                    @case('rent')
                                        <span class="text-blue-600 dark:text-blue-400">Rent Only</span>
                                    @break

                                    @case('both')
                                        <span class="text-purple-600 dark:text-purple-400">Sale & Rent</span>
                                    @break
                                @endswitch
                            </p>
                        </div>

                        <!-- Status -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                            <div class="flex items-center">
                                <div
                                    class="mr-2 h-3 w-3 rounded-full {{ $product->status ? 'bg-green-500' : 'bg-red-500' }}">
                                </div>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                </p>
                            </div>
                        </div>

                        <!-- Featured -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Featured</p>
                            <div class="flex items-center">
                                @if ($product->is_feature)
                                    <svg class="mr-2 h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="text-lg font-medium text-yellow-600 dark:text-yellow-400">Featured</span>
                                @else
                                    <span class="text-lg font-medium text-gray-500 dark:text-gray-400">Not Featured</span>
                                @endif
                            </div>
                        </div>

                        <!-- Creation Date -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Created</p>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $product->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </x-common.component-card>

                <!-- Quick Actions Card -->
                <x-common.component-card title="Quick Actions">
                    <div class="space-y-3">
                        <a href="{{ route('products.edit', $product->id) }}"
                            class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Product Details
                        </a>

                        <a href="{{ route('products.variants.manage', $product->id) }}"
                            class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Add/Manage Variants
                        </a>

                        <a href="{{ route('products.index') }}"
                            class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Products List
                        </a>
                    </div>
                </x-common.component-card>

                <!-- Product Image Card -->
                @if ($product->thumb_url)
                    <x-common.component-card title="Product Image">
                        <div class="relative">
                            <img src="{{ $product->thumb_url }}" alt="{{ $product->product_name }}"
                                class="w-full rounded-lg border border-gray-200 dark:border-gray-700">
                            <a href="{{ $product->thumb_url }}" target="_blank"
                                class="absolute bottom-3 right-3 inline-flex items-center gap-1 rounded-lg bg-black/70 px-2 py-1 text-xs text-white hover:bg-black/90 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Full Size
                            </a>
                        </div>
                    </x-common.component-card>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .prose {
            line-height: 1.75;
        }

        .prose p {
            margin-top: 1.25em;
            margin-bottom: 1.25em;
        }
    </style>
@endpush
