@extends('layouts.frontend.app')

@section('title', 'Products - KFS Scaffolding')

@section('content')
    <div id="page-products" class="page active">
        <div class="pt-20 lg:pt-24 min-h-screen bg-navy-800 blueprint-grid-dark">

            {{-- Page Header --}}
            <div class="bg-navy-800 blueprint-grid-dark py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 bg-orange-500/15 border border-orange-500/25 rounded-full mb-4">
                            <i data-lucide="package" class="w-3.5 h-3.5 text-orange-400"></i>
                            <span class="text-xs font-semibold text-orange-400 uppercase tracking-wider">Equipment
                                Catalog</span>
                        </div>
                        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">
                            Professional <span class="text-orange-400">Equipment</span>
                        </h1>
                        <p class="text-lg text-steel-300 max-w-2xl mx-auto">
                            Browse our comprehensive range of scaffolding equipment, all certified to international safety
                            standards.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Products Grid --}}
            <section class="py-16 bg-steel-50 blueprint-grid">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                    {{-- Filter Tabs --}}
                    <div class="flex justify-center gap-2 mb-10 flex-wrap">
                        <button
                            class="product-filter active px-5 py-2.5 text-sm font-medium rounded-xl transition-all bg-navy-800 text-white"
                            data-filter="both">
                            All Equipment
                        </button>
                        <button
                            class="product-filter px-5 py-2.5 text-sm font-medium rounded-xl transition-all bg-white text-steel-600 border border-steel-200 hover:border-orange-300"
                            data-filter="rent">
                            Rental Equipment
                        </button>
                        <button
                            class="product-filter px-5 py-2.5 text-sm font-medium rounded-xl transition-all bg-white text-steel-600 border border-steel-200 hover:border-orange-300"
                            data-filter="sale">
                            Sales Equipment
                        </button>

                    </div>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6" id="productsGrid">

                        @forelse ($products as $product)

                            @foreach ($product->variants as $variant)
                                <div class="product-card card-lift bg-white rounded-2xl border border-steel-200 overflow-hidden group"
                                    data-type="{{ $product->product_type }}"
                                    data-rent="{{ $variant->prices->where('price_type', 'rent')->first()->price ?? 0 }}"
                                    data-sale="{{ $variant->prices->where('price_type', 'sale')->first()->price ?? 0 }}">

                                    <div class="aspect-[4/3] overflow-hidden relative">

                                        <a href="{{ route('frontend.products.show', $product->id) }}" class="block aspect-[4/3] overflow-hidden relative">
                                            <img src="{{ $product->thumb_url }}" alt="{{ $product->product_name }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            {{-- ...status tags --}}
                                        </a>

                                        <div
                                            class="absolute top-3 right-3 px-2.5 py-1 
                                                {{ $product->status ? 'bg-green-500' : 'bg-red-500' }}
                                                text-white text-[10px] font-bold uppercase tracking-wider rounded-full">

                                            {{ $product->status ? 'In Stock' : 'Out of Stock' }}

                                        </div>

                                    </div>

                                    <div class="p-6">

                                        <div class="flex items-start justify-between mb-2">
                                            <h3 class="font-display text-lg font-bold text-navy-800">
                                                {{ $product->product_name }}
                                            </h3>
                                            {{-- Variant Info --}}
                                            <div class="text-xs text-steel-500 mb-3">
                                                Size: <span class="font-semibold">{{ $variant->size }}</span>
                                                |
                                                Unit: <span class="font-semibold">{{ $variant->unit }}</span>
                                            </div>

                                        </div>

                                        <p class="text-sm text-steel-500 mb-4">
                                            {{ \Illuminate\Support\Str::limit($product->description, 80) }}
                                        </p>

                                        <div class="flex items-center justify-between pt-3 border-t border-steel-100">

                                            @php
                                                $rentPrice = $variant->prices->where('price_type', 'rent')->first();
                                                $salePrice = $variant->prices->where('price_type', 'sale')->first();
                                            @endphp

                                            <div class="price-box">
                                                @if ($product->product_type == 'sale')
                                                    <span class="text-xs font-bold text-orange-500">
                                                        Ks {{ number_format($salePrice->price ?? 0) }}
                                                    </span>
                                                @else
                                                    <span class="text-xs font-bold text-orange-500">
                                                        Ks {{ number_format($rentPrice->price ?? 0) }}
                                                    </span>
                                                    <span class="text-xs text-steel-400">/day</span>
                                                @endif
                                            </div>

                                            <a href="{{ route('frontend.quotations.create') }}"
                                                class="action-btn px-4 py-2 bg-navy-800 text-white text-sm font-medium rounded-lg hover:bg-navy-700">
                                                {{ $product->product_type == 'sale' ? 'Buy Now' : 'Rent Now' }}
                                            </a>

                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        @empty

                            <p class="col-span-3 text-center text-gray-500">
                                No products found.
                            </p>

                        @endforelse

                    </div>

                    {{-- Bottom CTA --}}
                    <div class="text-center mt-12">
                        <button onclick="navigateTo('quotation')"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/25">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                            Request a Quotation
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Product filter functionality
        document.querySelectorAll('.product-filter').forEach(button => {
            button.addEventListener('click', function() {
                // Active button UI
                document.querySelectorAll('.product-filter').forEach(btn => {
                    btn.classList.remove('active', 'bg-navy-800', 'text-white');
                    btn.classList.add('bg-white', 'text-steel-600', 'border', 'border-steel-200');
                });

                this.classList.add('active', 'bg-navy-800', 'text-white');
                this.classList.remove('bg-white', 'text-steel-600', 'border', 'border-steel-200');
                const filter = this.dataset.filter;

                document.querySelectorAll('.product-card').forEach(card => {

                    const type = card.dataset.type;
                    const rentPrice = card.dataset.rent;
                    const salePrice = card.dataset.sale;

                    const btn = card.querySelector('.action-btn');
                    const priceBox = card.querySelector('.price-box');

                    let show = false;

                    if (filter === "both") {
                        show = true;
                    }

                    if (filter === "rent") {
                        show = type === "rent" || type === "both";

                        btn.innerText = "Rent Now";

                        priceBox.innerHTML =
                            `Ks ${Number(rentPrice).toLocaleString()} <span class="text-xs text-steel-400">/day</span>`;
                    }

                    if (filter === "sale") {
                        show = type === "sale" || type === "both";

                        btn.innerText = "Buy Now";

                        priceBox.innerHTML =
                            `Ks ${Number(salePrice).toLocaleString()}`;
                    }

                    if (show) {
                        card.style.display = "";
                    } else {
                        card.style.display = "none";
                    }

                });

            });
        });
    </script>
@endpush
