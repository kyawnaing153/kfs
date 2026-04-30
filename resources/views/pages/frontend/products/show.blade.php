@extends('layouts.frontend.app')

@section('title', $product->product_name . ' - KFS Scaffolding')

@section('content')
    <div id="page-product-details" class="page active">
        <div class="pt-20 lg:pt-24 min-h-screen bg-steal-500 blueprint-grid-dark">

            {{-- Breadcrumb & Quick Actions Header --}}
            <div class="bg-navy-800 blueprint-grid-dark py-8 sm:py-12 border-b border-navy-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Header Content -->
                    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 {{ $product->status ? 'bg-green-500/15 border border-green-500/25' : 'bg-red-500/15 border border-red-500/25' }} rounded-full">
                                    <div class="w-2 h-2 rounded-full {{ $product->status ? 'bg-green-400' : 'bg-red-400' }} animate-pulse"></div>
                                    <span class="text-xs font-semibold {{ $product->status ? 'text-green-400' : 'text-red-400' }} uppercase tracking-wider">
                                        {{ $product->status ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </div>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 border border-white/20 rounded-full">
                                    <i data-lucide="tag" class="w-3.5 h-3.5 text-steel-300"></i>
                                    <span class="text-xs font-medium text-steel-300 uppercase tracking-wider">
                                        {{ ucfirst($product->product_type) }} Available
                                    </span>
                                </div>
                            </div>
                            <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                                {{ $product->product_name }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Section --}}
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                    {{-- Left Column: Images --}}
                    <div class="lg:col-span-5 flex flex-col gap-4">
                        <div class="aspect-[4/3] sm:aspect-square lg:aspect-[4/3] rounded-2xl overflow-hidden bg-white border border-steel-200 p-2 shadow-sm relative group">
                            <img src="{{ $product->thumb_url }}" alt="{{ $product->product_name }}" 
                                class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition-transform duration-700">
                        </div>
                        
                        <!-- Thumbnail Gallery Base (For when multiple images are available) -->
                        <div class="grid grid-cols-4 gap-3">
                            <div class="aspect-square rounded-xl overflow-hidden border-2 border-navy-800 bg-white shadow-sm cursor-pointer opacity-100 transition-opacity">
                                <img src="{{ $product->thumb_url }}" alt="Thumbnail 1" class="w-full h-full object-cover p-1 rounded-lg">
                            </div>
                            <!-- Placeholders for other thumbnails -->
                            <div class="aspect-square rounded-xl overflow-hidden border border-steel-200 bg-steel-100 flex items-center justify-center opacity-70 cursor-not-allowed">
                                <i data-lucide="image" class="w-6 h-6 text-steel-300"></i>
                            </div>
                            <div class="aspect-square rounded-xl overflow-hidden border border-steel-200 bg-steel-100 flex items-center justify-center opacity-70 cursor-not-allowed">
                                <i data-lucide="image" class="w-6 h-6 text-steel-300"></i>
                            </div>
                            <div class="aspect-square rounded-xl overflow-hidden border border-steel-200 bg-steel-100 flex items-center justify-center opacity-70 cursor-not-allowed">
                                <i data-lucide="image" class="w-6 h-6 text-steel-300"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Details & Pricing --}}
                    <div class="lg:col-span-7 flex flex-col">
                        
                        <!-- Technical Specs & Variations -->
                        <div class="mb-10 flex-1">
                            <h3 class="text-xl font-bold text-navy-800 mb-4 font-display flex items-center gap-2">
                                <i data-lucide="settings-2" class="w-5 h-5 text-orange-500"></i> Specifications & Pricing
                            </h3>
                            
                            <div class="space-y-4">
                                @forelse($product->variants as $variant)
                                    <div class="bg-white rounded-2xl border border-steel-200 p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow group">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                                            
                                            <!-- Variant details -->
                                            <div class="flex items-start gap-4">
                                                <div class="w-12 h-12 bg-steel-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-orange-50 transition-colors">
                                                    <i data-lucide="box" class="w-6 h-6 text-navy-800 group-hover:text-orange-500 transition-colors"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-navy-800 text-lg">{{ $variant->size }}</h4>
                                                    <p class="text-sm text-steel-500 font-medium">Unit: <span class="bg-steel-100 px-2 py-0.5 rounded text-navy-600">{{ $variant->unit }}</span></p>
                                                </div>
                                            </div>
                                            
                                            <!-- Pricing details -->
                                            <div class="flex flex-wrap sm:flex-nowrap items-center gap-4 sm:gap-8 bg-steel-50 p-3 sm:bg-transparent sm:p-0 rounded-xl">
                                                @php
                                                    $rentPrice = $variant->prices->where('price_type', 'rent')->first();
                                                    $salePrice = $variant->prices->where('price_type', 'sale')->first();
                                                @endphp
                                                
                                                @if($product->product_type === 'rent' || $product->product_type === 'both')
                                                    <div class="text-left sm:text-right flex-1 sm:flex-none">
                                                        <div class="text-[10px] sm:text-xs text-steel-500 uppercase tracking-widest font-bold mb-1">Rent Price</div>
                                                        <div class="text-lg font-bold text-orange-500 flex items-baseline gap-1">
                                                            <span class="text-sm">Ks</span> 
                                                            {{ number_format($rentPrice->price ?? 0) }}
                                                            <span class="text-xs font-medium text-steel-400">/day</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if($product->product_type === 'sale' || $product->product_type === 'both')
                                                    @if($product->product_type === 'both')
                                                        <div class="hidden sm:block w-px h-10 bg-steel-200"></div>
                                                    @endif
                                                    <div class="text-left sm:text-right flex-1 sm:flex-none">
                                                        <div class="text-[10px] sm:text-xs text-steel-500 uppercase tracking-widest font-bold mb-1">Sale Price</div>
                                                        <div class="text-lg font-bold text-navy-800 flex items-baseline gap-1">
                                                            <span class="text-sm text-steel-500">Ks</span> 
                                                            {{ number_format($salePrice->price ?? 0) }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="bg-white rounded-2xl border border-steel-200 p-8 text-center text-steel-500">
                                        <i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-2 text-steel-400"></i>
                                        No specifications available for this equipment.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Description Block -->
                        <div class="mb-10">
                            <h3 class="text-xl font-bold text-navy-800 mb-4 font-display flex items-center gap-2">
                                <i data-lucide="info" class="w-5 h-5 text-orange-500"></i> Equipment Overview
                            </h3>
                            <div class="bg-white rounded-2xl border border-steel-200 p-6 shadow-sm">
                                <p class="text-steel-600 leading-relaxed text-base">
                                    {{ $product->description ?? 'No detailed description available for this equipment.' }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="bg-navy-800 rounded-2xl p-6 shadow-xl relative overflow-hidden mt-2">
                            <!-- Background decoration -->
                            <div class="absolute top-0 right-0 -mr-8 -mt-8 opacity-10">
                                <i data-lucide="shopping-cart" class="w-48 h-48 sm:w-64 sm:h-64"></i>
                            </div>
                            
                            <div class="relative z-10">
                                <h4 class="text-white font-semibold text-lg mb-4">Request a Quotation</h4>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    @if($product->product_type === 'sale' || $product->product_type === 'both')
                                        <a href="{{ route('frontend.quotations.create') }}?product={{ $product->id }}&type=sale" 
                                            class="flex-1 flex justify-center items-center gap-2 px-6 py-3.5 bg-white text-navy-800 font-bold rounded-xl hover:bg-steel-50 transition-all">
                                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                            Buy Equipment
                                        </a>
                                    @endif
                                    
                                    @if($product->product_type === 'rent' || $product->product_type === 'both')
                                        <a href="{{ route('frontend.quotations.create') }}?product={{ $product->id }}&type=rent" 
                                            class="flex-1 flex justify-center items-center gap-2 px-6 py-3.5 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/25">
                                            <i data-lucide="file-text" class="w-5 h-5"></i>
                                            Rent Equipment
                                        </a>
                                    @endif
                                </div>
                                <p class="text-steel-400 text-xs text-center mt-4">
                                    <i data-lucide="shield-check" class="w-3.5 h-3.5 inline mb-0.5 text-steel-300"></i> 
                                    All requested equipment is subject to availability confirmation.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Re-initialize lucide icons if applicable
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
@endpush
